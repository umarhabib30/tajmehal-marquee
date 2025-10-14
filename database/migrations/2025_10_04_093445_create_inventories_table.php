<?php

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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // item name
            $table->enum('category', ['Food', 'Electronics', 'Furniture', 'Decoration', 'Crockery']);
            $table->integer('quantity');
            $table->decimal('price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('expiry_date')->nullable(); // for food
            $table->string('condition')->nullable(); // Good, Broken, etc.
            $table->string('warranty_period')->nullable(); // for electronics
            $table->string('location')->nullable(); // for furniture
            $table->string('supplier_name')->nullable();
            $table->string('status')->default('Available'); // Available, In Use, Damaged
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
