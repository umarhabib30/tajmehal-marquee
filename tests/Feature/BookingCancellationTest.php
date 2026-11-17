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
            $table->integer('total_amount')->default(10000);
            $table->integer('customer_id')->nullable();
            foreach (['event_type', 'hall_name', 'event_date', 'time_slot', 'start_time', 'end_time', 'dishes', 'decorations'] as $field) {
                $table->string($field)->nullable();
            }
            foreach (['guests_count', 'per_head_price', 'decore_price', 'tax_amount', 'discount', 'advance_payment'] as $field) {
                $table->integer($field)->default(0);
            }
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
        });
        DB::table('customers')->insert(['id' => 1]);
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

    public function test_cancelled_booking_releases_slot_and_refunds_can_be_returned_later(): void
    {
        $booking = $this->paidBooking();
        $url = route('admin.booking.updateStatus', $booking->id);
        $this->assertTrue(Booking::occupiesSlot()->whereKey($booking->id)->exists());
        $this->patch($url, ['status' => 'Cancelled', 'refund_amount' => 1500])->assertSessionHasNoErrors();
        $this->assertFalse(Booking::occupiesSlot()->whereKey($booking->id)->exists());
        $this->patch($url, ['status' => 'Cancelled', 'record_refund' => 1, 'refund_amount' => 1000])
            ->assertSessionHasNoErrors();
        $this->assertEquals(2500, $booking->payments()->sum('amount'));
        $this->patch($url, ['status' => 'Cancelled', 'record_refund' => 1, 'refund_amount' => 2501])
            ->assertSessionHasErrors('refund_amount');
        $this->assertEquals(2500, $booking->payments()->sum('amount'));
        session()->forget('errors');
        $this->patch($url, ['status' => 'Cancelled', 'record_refund' => 1, 'refund_amount' => 2500])
            ->assertSessionHasNoErrors();
        $this->assertEquals(0, $booking->payments()->sum('amount'));
        $this->assertEquals(10000, $booking->fresh()->remaining_amount);
    }

    public function test_edit_cancellation_records_refund_even_when_another_booking_uses_the_slot(): void
    {
        $booking = $this->paidBooking();
        $payload = [
            'id' => $booking->id, 'customer_id' => 1, 'event_type' => 'Barat',
            'hall_name' => 'Hall 1', 'event_date' => '2026-10-30', 'time_slot' => 'Lunch',
            'start_time' => '13:00', 'end_time' => '16:00', 'guests_count' => 10,
            'per_head_price' => 1000, 'total_amount' => 10000, 'remaining_amount' => 5000,
            'status' => 'Cancelled', 'refund_amount' => 1500,
        ];
        Booking::create(array_merge($payload, ['status' => 'Active']));
        $this->post(route('admin.booking.update'), $payload)->assertSessionHasNoErrors();
        $this->assertSame('Cancelled', $booking->fresh()->status);
        $this->assertEquals(3500, $booking->payments()->sum('amount'));
        $this->assertEquals(6500, $booking->fresh()->remaining_amount);
        $payload['refund_amount'] = 3501;
        $this->post(route('admin.booking.update'), $payload)->assertSessionHasErrors('refund_amount');
        $this->assertEquals(3500, $booking->payments()->sum('amount'));
    }
}
