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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->enum('event_type', ['Barat', 'Waliema', 'Get Together', 'Mehndi', 'Meeting']);
            $table->enum('hall_name', ['Hall 1', 'Hall 2', 'Hall 3', 'Full Hall']);
            $table->date('booking_date');
            $table->enum('time_slot', ['Lunch', 'Dinner']);
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('dish_package_id')->constrained('dish_packages')->onDelete('cascade');
            $table->string('decoration')->nullable();
            $table->decimal('decoration_amount', 10, 2)->default(0);
            $table->integer('guests_count');
            $table->decimal('price_per_head', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('remaining_amount', 12, 2);
            $table->string('customer_signature')->nullable();
            $table->string('manager_signature')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
