<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Enums\VendorType;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 distributors
        for ($i = 1; $i <= 10; $i++) {
            Vendor::create([
                'name' => 'Distributor ' . $i,
                'address' => 'distributor' . $i . '@example.com',
                'country' => '1234567890',
                'vat_number' => '123 Main St, Anytown, USA',
                'type' => VendorType::DISTRIBUTOR,
                'sales_contact_id' => User::create([
                    'name' => 'Sales Contact ' . $i,
                    'email' => 'sales' . $i . '@example.com',
                    'password' => Hash::make('password'),
                    'role' => UserRole::SALES,
                ])->id,
                'logistics_contact_id' => User::create([
                    'name' => 'Logistics Contact ' . $i,
                    'email' => 'logistics' . $i . '@example.com',
                    'password' => Hash::make('password'),
                    'role' => UserRole::LOGISTICS,
                ])->id,
            ]);

        }

        // Create 10 suppliers
        for ($i = 11; $i <= 20; $i++) {
            Vendor::create([
                'name' => 'Supplier ' . $i,
                'address' => 'supplier' . $i . '@example.com',
                'country' => '1234567890',
                'vat_number' => '123 Main St, Anytown, USA',
                'type' => VendorType::SUPPLIER,
                'sales_contact_id' => User::create([
                    'name' => 'Sales Contact ' . $i,
                    'email' => 'sales' . $i . '@example.com',
                    'password' => Hash::make('password'),
                    'role' => UserRole::SALES,
                ])->id,
                'logistics_contact_id' => User::create([
                    'name' => 'Logistics Contact ' . $i,
                    'email' => 'logistics' . $i . '@example.com',
                    'password' => Hash::make('password'),
                    'role' => UserRole::LOGISTICS,
                ])->id,
            ]);
        }
    }
}
