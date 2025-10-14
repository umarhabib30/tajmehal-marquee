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
            $table->string('event_type');
            $table->integer('guests_count');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('time_slot'); // Morning / Evening
            $table->string('hall_name');
            $table->string('decoration_type')->nullable();
            $table->string('menu_package')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('advance_payment', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->default(0);
            $table->string('payment_status')->default('Pending');
            $table->string('status')->default('Active');
            $table->text('special_request')->nullable();
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
