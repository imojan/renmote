<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SearchController extends Controller
{
    /**
     * Halaman pencarian kendaraan
     */
    public function index(Request $request, ?string $categorySlug = null)
    {
        $districts = District::all();
        $resolvedCategory = null;
        $rawCategory = null;

        // Canonical category URL: /kategori/{slug}
        if ($categorySlug !== null) {
            $resolvedCategory = $this->resolveCategoryFromSlug($categorySlug);

            if ($resolvedCategory === null) {
                abort(404);
            }

            $canonicalSlug = $this->categoryToSlug($resolvedCategory);
            $normalizedCurrentSlug = $this->normalizeCategorySlug($categorySlug);

            if ($canonicalSlug !== $normalizedCurrentSlug) {
                $queryParams = $request->query();
                unset($queryParams['category']);

                return redirect()->route('search.category', array_merge([
                    'categorySlug' => $canonicalSlug,
                ], $queryParams), 301);
            }
        } elseif ($request->filled('category')) {
            // Legacy URL support: /search?category=...
            $rawCategory = trim((string) $request->query('category'));
            $resolvedCategory = $this->resolveCategoryFromSlug($rawCategory);

            if ($resolvedCategory !== null) {
                $queryParams = $request->query();
                unset($queryParams['category']);

                return redirect()->route('search.category', array_merge([
                    'categorySlug' => $this->categoryToSlug($resolvedCategory),
                ], $queryParams), 301);
            }
        }
        
        $query = Vehicle::with('vendor.district')
            ->where('status', 'available');

        // Filter by keyword (search by name, category, description)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('category', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by district
        if ($request->filled('district_id')) {
            $query->whereHas('vendor', function ($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        // Filter by category
        $categoryFilter = $resolvedCategory ?? $rawCategory;
        if ($categoryFilter !== null && $categoryFilter !== '') {
            $normalizedCategory = $this->normalizeCategorySlug($categoryFilter);
            $query->whereRaw("LOWER(REPLACE(REPLACE(category, ' ', '-'), '_', '-')) = ?", [$normalizedCategory]);
        }

        // Auto hide kendaraan bentrok:
        // - Jika user isi tanggal, pakai rentang yang dipilih.
        // - Jika user tidak isi tanggal, pakai hari ini.
        $requestedStartDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->toDateString()
            : null;

        $requestedEndDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->toDateString()
            : null;

        if ($requestedStartDate && !$requestedEndDate) {
            $requestedEndDate = $requestedStartDate;
        }

        if (!$requestedStartDate && $requestedEndDate) {
            $requestedStartDate = $requestedEndDate;
        }

        if (!$requestedStartDate && !$requestedEndDate) {
            $today = now()->toDateString();
            $requestedStartDate = $today;
            $requestedEndDate = $today;
        }

        $query->whereDoesntHave('bookings', function ($bookingQuery) use ($requestedStartDate, $requestedEndDate) {
            $bookingQuery->where('status', '!=', 'cancelled')
                ->where('start_date', '<=', $requestedEndDate)
                ->where('end_date', '>=', $requestedStartDate);
        });

        $vehicles = $query->get();

        $selectedCategorySlug = $resolvedCategory !== null
            ? $this->categoryToSlug($resolvedCategory)
            : (($rawCategory !== null && $rawCategory !== '')
                ? $this->normalizeCategorySlug($rawCategory)
                : null);

        return view('front.search', compact('vehicles', 'districts', 'selectedCategorySlug'));
    }

    private function resolveCategoryFromSlug(string $input): ?string
    {
        $normalized = $this->normalizeCategorySlug($input);

        $map = [
            'matic' => 'matic',
            'manual' => 'manual',
            'sport' => 'sport',
            'bebek' => 'bebek',
            'trail' => 'trail',
            'skutik-premium' => 'skutik_premium',
            'bigbike' => 'bigbike',
        ];

        return $map[$normalized] ?? null;
    }

    private function categoryToSlug(string $category): string
    {
        return $this->normalizeCategorySlug($category);
    }

    private function normalizeCategorySlug(string $value): string
    {
        return Str::of($value)
            ->lower()
            ->replace('_', '-')
            ->replace(' ', '-')
            ->slug('-')
            ->toString();
    }
}
