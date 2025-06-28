<?php

use App\Enums\PurchaseOrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('vendor_type'); // 'App\Models\Supplier' or 'App\Models\Distributer'
            $table->string('product_code');
            $table->float('quantity');
            $table->date('delivery_date');
            $table->decimal('total', 10, 2);
            $table->enum('status', array_column(PurchaseOrderStatus::cases(), 'value'));
            $table->timestamps();

            $table->index(['vendor_id', 'vendor_type']);

            $table->foreign('product_code')->references('code')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
