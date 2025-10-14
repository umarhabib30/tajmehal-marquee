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
        Schema::create('dish_packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_name');
            $table->json('selected_dishes')->nullable(); // Store selected dish IDs
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('additional_dishes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_packages');
    }
};
