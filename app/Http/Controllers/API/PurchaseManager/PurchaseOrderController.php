<?php

namespace App\Http\Controllers\API\PurchaseManager;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Product;
use App\Enums\VendorType;
use App\Enums\PurchaseOrderStatus;
use Illuminate\Http\Request;
use Exception;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PurchaseOrder::class);

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $orders = PurchaseOrder::with('vendor', 'product')->paginate($perPage, ['*'], 'page', $page);

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
        ]);

        $order = $this->createPOD($data);

        if ($order instanceof Exception) {
            return response()->json([
                'message' => $order->getMessage()
            ], 422);
        }

        // TODO: Uncomment this when POS is implemented. Where is the supplier?
        // $this->createPOS(Arr::except($data, ['supplier_id']));

        // if ($order instanceof Exception) {
        //     Log::error($order->getMessage());
        // }

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

        $order = $this->createPOS($data);

        if ($order instanceof Exception) {
            return response()->json([
                'message' => $order->getMessage()
            ], 422);
        }

        return response()->json($order, 201);
    }

    /**
     * Create a new POD
     *
     * @param array $data
     * @return PurchaseOrder | Exception
     */
    private function createPOD(array $data)
    {
        // Verify vendor is a distributor
        $vendor = Vendor::findOrFail($data['vendor_id']);
        if ($vendor->type !== VendorType::DISTRIBUTOR->value) {
            return new Exception('Vendor must be a distributor for POD creation');
        }

        // workout total price
        $data = [
            ...$data,
            'total' => Product::findOrFail($data['product_code'])->current_price * $data['quantity'],
            'status' => PurchaseOrderStatus::NEW->value,
            'vendor_type' => $vendor->type,
        ];

        return PurchaseOrder::create($data);
    }

    /**
     * Create a new POS
     *
     * @param array $data
     * @return PurchaseOrder | Exception
     */
    private function createPOS(array $data): PurchaseOrder | Exception
    {
        $vendor = Vendor::findOrFail($data['vendor_id']);
        if ($vendor->type !== VendorType::SUPPLIER->value) {
            return new Exception('Vendor must be a supplier for POS creation');
        }

        $data = [
            ...$data,
            'total' => Product::findOrFail($data['product_code'])->current_price * $data['quantity'],
            'status' => PurchaseOrderStatus::ACCEPTED_BY_SUPPLIER->value,
            'vendor_type' => $vendor->type,
        ];

        return PurchaseOrder::create($data);
    }
}
