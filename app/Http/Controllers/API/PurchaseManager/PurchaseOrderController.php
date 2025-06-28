<?php

namespace App\Http\Controllers\API\PurchaseManager;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Product;
use App\Enums\VendorType;
use App\Enums\PurchaseOrderStatus;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PurchaseOrder::class);

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $orders = PurchaseOrder::paginate($perPage, ['*'], 'page', $page);

        return response()->json($orders);
    }

    public function createNewPOD(Request $request)
    {
        $this->authorize('create', PurchaseOrder::class);

        $data = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'product_code' => 'required|exists:products,code',
            'quantity' => 'required|numeric|min:1',
            'delivery_date' => 'required|date|after:now',
            'supplier_id' => 'required|exists:vendors,id',
        ]);

        // Verify vendor is a distributor
        $vendor = Vendor::findOrFail($data['vendor_id']);
        if ($vendor->type !== VendorType::DISTRIBUTOR->value) {
            return response()->json([
                'message' => 'Vendor must be a distributor for POD creation'
            ], 422);
        }

        // workout total price
        $data = [
            ...$data,
            'total' => Product::findOrFail($data['product_code'])->current_price * $data['quantity'],
            'status' => PurchaseOrderStatus::NEW->value,
            'vendor_type' => $vendor->type,
        ];

        // Create the purchase order
        $order = PurchaseOrder::create($data);

        $this->createNewPOS(Request::create('/', 'POST', [
            'vendor_id' => $data['supplier_id'],
            'product_code' => $data['product_code'],
            'quantity' => $data['quantity'],
            'delivery_date' => $data['delivery_date'],
        ], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $request->bearerToken(),
        ]));

        return response()->json($order, 201);
    }

    public function createNewPOS(Request $request)
    {
        $this->authorize('create', PurchaseOrder::class);

        $data = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'product_code' => 'required|exists:products,code',
            'quantity' => 'required|numeric|min:1',
            'delivery_date' => 'required|date|after:now',
        ]);

        $vendor = Vendor::findOrFail($data['vendor_id']);
        if ($vendor->type !== VendorType::SUPPLIER->value) {
            return response()->json([
                'message' => 'Vendor must be a supplier for POS creation'
            ], 422);
        }

        $data = [
            ...$data,
            'total' => Product::findOrFail($data['product_code'])->current_price * $data['quantity'],
            'status' => PurchaseOrderStatus::ACCEPTED_BY_SUPPLIER->value,
            'vendor_type' => $vendor->type,
        ];

        $order = PurchaseOrder::create($data);
    }
}
