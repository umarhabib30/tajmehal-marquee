<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_stock_reset_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('inventory_stock_reset_batches')->cascadeOnDelete();
            $table->unsignedBigInteger('original_stock_id')->nullable();
            $table->foreignId('inventory_id')->nullable()->constrained('inventories')->nullOnDelete();
            $table->string('inventory_name')->nullable();
            $table->string('inventory_category')->nullable();
            $table->string('inventory_unit')->nullable();
            $table->date('date')->nullable();
            $table->decimal('quantity_in', 10, 2)->default(0);
            $table->decimal('quantity_out', 10, 2)->default(0);
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->string('warranty_period')->nullable();
            $table->string('supplier_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_reset_records');
    }
};
