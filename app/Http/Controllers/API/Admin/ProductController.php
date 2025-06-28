<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // admin can see all products and their prices
        $this->authorize('viewAny', Product::class);

        $perPage = $request->input('per_page', 50);
        $page = $request->input('page', 1);

        $products = Product::with('prices')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($products);
    }

    public function show(Product $product)
    {
        $product->load('prices');

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $request->validate([
            'code' => 'required|string|unique:products,code',
            'description' => 'required|string',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'description' => 'required|string',
        ]);

        $data = $request->all();
        unset($data['code']); // assumptioin: code is not updatable

        $product->update($data);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        // $this->authorize('delete', $product);

        $product->delete();

        return response()->json(null, 204);
    }
}
