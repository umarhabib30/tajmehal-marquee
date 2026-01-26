<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventory_stocks', function (Blueprint $table) {
            $table->decimal('quantity_in', 10, 2)->default(0)->change();
            $table->decimal('quantity_out', 10, 2)->default(0)->change();
            $table->decimal('price_per_unit', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_stocks', function (Blueprint $table) {
            $table->integer('quantity_in')->default(0)->change();
            $table->integer('quantity_out')->default(0)->change();
            $table->integer('price_per_unit')->nullable()->change();
        });
    }
};
