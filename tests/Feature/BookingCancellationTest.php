<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BookingCancellationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->integer('remaining_amount');
            $table->timestamps();
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->integer('amount');
            $table->date('payment_date');
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
        $this->withoutMiddleware();
    }

    private function paidBooking(): Booking
    {
        $booking = Booking::create(['status' => Booking::STATUS_ACTIVE, 'remaining_amount' => 5000]);
        foreach ([3000, 2000] as $amount) {
            $booking->payments()->create(['amount' => $amount, 'payment_date' => '2026-10-29']);
        }
        return $booking;
    }

    public function test_full_refund_defaults_to_sum_of_payments_and_is_not_duplicated(): void
    {
        $booking = $this->paidBooking();
        $payload = ['status' => 'Cancelled', 'refund_amount' => 5000];
        $url = route('admin.booking.updateStatus', $booking->id);
        $this->patch($url, $payload)->assertSessionHasNoErrors()->assertRedirect(route('admin.booking.show', $booking->id));
        $this->patch($url, $payload)->assertSessionHasNoErrors();
        $this->assertSame('Cancelled', $booking->fresh()->status);
        $this->assertEquals(0, $booking->payments()->sum('amount'));
        $this->assertSame(3, $booking->payments()->count());
        $this->assertEquals(10000, $booking->fresh()->remaining_amount);
    }

    public function test_partial_refund_preserves_retained_payment(): void
    {
        $booking = $this->paidBooking();
        $this->patch(route('admin.booking.updateStatus', $booking->id), [
            'status' => 'Cancelled', 'refund_amount' => 1500,
        ])->assertSessionHasNoErrors();
        $this->assertEquals(3500, $booking->payments()->sum('amount'));
        $this->assertEquals(6500, $booking->fresh()->remaining_amount);
    }

    public function test_invalid_refunds_do_not_change_status_or_payments(): void
    {
        $booking = $this->paidBooking();
        foreach ([null, -1, 5001, 1.5, 'invalid'] as $amount) {
            $this->patch(route('admin.booking.updateStatus', $booking->id), [
                'status' => 'Cancelled', 'refund_amount' => $amount,
            ])->assertSessionHasErrors('refund_amount');
            $this->assertSame('Active', $booking->fresh()->status);
            $this->assertSame(2, $booking->payments()->count());
        }
    }

    public function test_zero_refund_and_other_status_changes_are_supported(): void
    {
        $booking = $this->paidBooking();
        $url = route('admin.booking.updateStatus', $booking->id);
        $this->patch($url, ['status' => 'Pending'])->assertSessionHasNoErrors();
        $this->patch($url, ['status' => 'Cancelled', 'refund_amount' => 0])->assertSessionHasNoErrors();
        $this->assertSame('Cancelled', $booking->fresh()->status);
        $this->assertSame(2, $booking->payments()->count());
    }
}
