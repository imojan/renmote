<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\District;
use App\Models\Wishlist;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

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

        $vehicles = $query->get();

        // Filter by availability (date range)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $vehicles = $vehicles->filter(function ($vehicle) use ($request) {
                return $this->availabilityService->checkAvailability(
                    $vehicle->id,
                    $request->start_date,
                    $request->end_date
                );
            });
        }

        $selectedCategorySlug = $resolvedCategory !== null
            ? $this->categoryToSlug($resolvedCategory)
            : (($rawCategory !== null && $rawCategory !== '')
                ? $this->normalizeCategorySlug($rawCategory)
                : null);

        $wishlistedVehicleIds = [];
        if (Auth::check() && Auth::user()->role === 'user') {
            $wishlistedVehicleIds = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('wishlistable_type', Vehicle::class)
                ->pluck('wishlistable_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return view('front.search', compact('vehicles', 'districts', 'selectedCategorySlug', 'wishlistedVehicleIds'));
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
