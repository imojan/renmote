<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\District;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $districts = District::all();
        
        $query = Vehicle::with('vendor.district')
            ->where('status', 'available');

        // Filter by district
        if ($request->filled('district_id')) {
            $query->whereHas('vendor', function ($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
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

        return view('front.search', compact('vehicles', 'districts'));
    }
}
