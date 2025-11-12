<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('quantity_in')->default(0);
            $table->integer('quantity_out')->default(0);
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->integer('price_per_unit')->nullable();
            $table->string('warranty_period')->nullable();  // for electronics
            $table->string('supplier_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};
