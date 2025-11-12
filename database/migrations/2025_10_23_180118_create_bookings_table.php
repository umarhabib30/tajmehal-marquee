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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('customer_phone')->nullable();
            $table->string('customer_idcard')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('event_type');
            $table->string('hall_name');
            $table->date('event_date');
            $table->string('time_slot');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('dish_packages')->onDelete('set null');
            $table->json('dishes')->nullable();
            $table->json('decorations')->nullable();
            $table->integer('decore_price')->default(0);
            $table->integer('guests_count')->default(0);
            $table->integer('per_head_price')->default(0);
            $table->integer('tax_amount')->default(0);
            $table->integer('advance_payment')->default(0);
            $table->integer('total_amount')->default(0);
            $table->integer('remaining_amount')->default(0);
            $table->string('payment_method')->nullable();
            $table->longText('customer_signature')->nullable();
            $table->longText('manager_signature')->nullable();
            $table->text('notes')->nullable();
            $table->date('booking_date');
            $table->integer('extra_guests')->default(0);
            $table->integer('extra_guest_per_head_price')->default(0);
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
