<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::create([
            'code' => 'FR-001a',
            'description' => 'Citrusdal Oranges - Grade A',
        ]);

        ProductPrice::insert([
            ['product_code' => $product->code, 'year' => 2023, 'price' => 125.5],
            ['product_code' => $product->code, 'year' => 2024, 'price' => 135.5],
            ['product_code' => $product->code, 'year' => 2025, 'price' => 145.5],
        ]);

        $product = Product::create([
            'code' => 'FR-001b',
            'description' => 'Citrusdal Oranges - Grade B',
        ]);

        ProductPrice::insert([
            ['product_code' => $product->code, 'year' => 2023, 'price' => 136.75],
            ['product_code' => $product->code, 'year' => 2024, 'price' => 148.75],
            ['product_code' => $product->code, 'year' => 2025, 'price' => 195],
        ]);

        $product = Product::create([
            'code' => 'FR-002',
            'description' => 'Citrusdal Limes',
        ]);

        ProductPrice::insert([
            ['product_code' => $product->code, 'year' => 2023, 'price' => 131.5],
            ['product_code' => $product->code, 'year' => 2024, 'price' => 151.95],
            ['product_code' => $product->code, 'year' => 2025, 'price' => 165],
        ]);

        $product = Product::create([
            'code' => 'FR-003',
            'description' => 'Citrusdal Grapefruit - Grade 1',
        ]);

        ProductPrice::insert([
            ['product_code' => $product->code, 'year' => 2023, 'price' => 109.5],
            ['product_code' => $product->code, 'year' => 2024, 'price' => 132.5],
            ['product_code' => $product->code, 'year' => 2025, 'price' => 141],
        ]);

        $product = Product::create([
            'code' => 'FR-004',
            'description' => 'Citrusdal Lemons - Grade 1',
        ]);

        ProductPrice::insert([
            ['product_code' => $product->code, 'year' => 2023, 'price' => 99.25],
            ['product_code' => $product->code, 'year' => 2024, 'price' => 120.5],
            ['product_code' => $product->code, 'year' => 2025, 'price' => 59.0],
        ]);

        $product = Product::create([
            'code' => 'GT01',
            'description' => 'Grahamstown Oranges - Type A',
        ]);

        ProductPrice::insert([
            ['product_code' => $product->code, 'year' => 2023, 'price' => 98.5],
            ['product_code' => $product->code, 'year' => 2024, 'price' => 101.45],
            ['product_code' => $product->code, 'year' => 2025, 'price' => 115.6],
        ]);

        $product = Product::create([
            'code' => 'GT02',
            'description' => 'Grahamstown Oranges - Type B',
        ]);

        ProductPrice::insert([
            ['product_code' => $product->code, 'year' => 2023, 'price' => 97.6],
            ['product_code' => $product->code, 'year' => 2024, 'price' => 103.65],
            ['product_code' => $product->code, 'year' => 2025, 'price' => 123.5],
        ]);
    }
}
