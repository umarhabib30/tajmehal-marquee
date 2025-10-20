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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->integer('month'); // numeric month (1–12)
            $table->integer('year');
            $table->decimal('basic', 10, 2)->default(0);
            $table->integer('overtime_hours')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('leave_days')->default(0);
            $table->decimal('overtime_rate', 10, 2)->default(0);
            $table->decimal('deduction_per_absent', 10, 2)->default(0);
            $table->decimal('deduction_per_leave', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
