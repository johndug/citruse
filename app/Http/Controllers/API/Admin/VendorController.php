<?php

namespace App\Http\Controllers\API\Admin;

use App\Enums\UserRole;
use App\Enums\VendorType;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Vendor::class);

        $perPage = $request->input('per_page', 100);
        $page = $request->input('page', 1);

        $vendors = Vendor::with('salesContact', 'logisticsContact')->paginate($perPage, ['*'], 'page', $page);

        return response()->json($vendors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Vendor::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'country' => 'required',
            'vat_number' => 'required|string',
            'type' => 'required|in:'.implode(',', array_column(VendorType::cases(), 'value')),
            'sales_contact_id' => 'required|exists:users,id,role,'.UserRole::SALES->value,
            'logistics_contact_id' => 'required|exists:users,id,role,'.UserRole::LOGISTICS->value,
        ]);

        $vendor = Vendor::create($data);

        return response()->json($vendor->load('salesContact', 'logisticsContact'), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'country' => 'required',
            'vat_number' => 'required|string',
            'type' => 'required|in:'.implode(',', array_column(VendorType::cases(), 'value')),
            'sales_contact_id' => 'required|exists:users,id,role,'.UserRole::SALES->value,
            'logistics_contact_id' => 'required|exists:users,id,role,'.UserRole::LOGISTICS->value,
        ]);

        $vendor->update($data);

        return response()->json($vendor->load('salesContact', 'logisticsContact'), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $this->authorize('delete', $vendor);

        $vendor->delete();

        return response()->json(null, 204);
    }
}
