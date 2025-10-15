<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    // Show calendar page
    public function index()
    {
        $title = 'Calendar';
        $heading = 'Bookings Calendar';
        $active = 'calendar';

        return view('admin.calendar.index', compact('title', 'heading', 'active'));
    }

    // Return all bookings as events for FullCalendar
    public function events()
    {
        $bookings = Booking::with('customer')->get();

        $events = $bookings->map(function ($booking) {
            // Determine color based on status
            $color = match ($booking->status) {
                'Completed' => '#28a745',   // green
                'Cancelled' => '#dc3545',   // red
                default => '#007bff',       // blue for Active/Pending
            };

            // Use start_date and end_date with booking_time
            $startTime = $booking->booking_time ?? '00:00:00';
            $endTime   = $booking->booking_time ?? '23:59:59'; // default full day if no time

            return [
                'id' => $booking->id,
                'title' => $booking->event_type . ' - ' . ($booking->customer->name ?? 'Unknown'),
                'start' => $booking->start_date . 'T' . $startTime,
                'end' => $booking->end_date . 'T' . $endTime,
                'color' => $color,
                'allDay' => false,
                'extendedProps' => [
                    'customer_name'     => $booking->customer->name ?? '',
                    'customer_email'    => $booking->customer->email ?? '',
                    'customer_phone'    => $booking->customer->phone ?? '',
                    'event_type'        => $booking->event_type,
                    'guests_count'      => $booking->guests_count ?? 0,
                    'booking_date'      => $booking->booking_date,
                    'booking_time'      => $booking->booking_time,
                    'time_slot'         => $booking->time_slot ?? '',
                    'hall_name'         => $booking->hall_name ?? '',
                    'decoration_type'   => $booking->decoration_type ?? '',
                    'menu_package'      => $booking->menu_package ?? '',
                    'total_amount'      => $booking->total_amount ?? 0,
                    'discount_percent'  => $booking->discount_percent ?? 0,
                    'advance_payment'   => $booking->advance_payment ?? 0,
                    'remaining_amount'  => $booking->remaining_amount ?? 0,
                    'payment_status'    => $booking->payment_status ?? 'Pending',
                    'status'            => $booking->status ?? 'Active',
                    'special_request'   => $booking->special_request ?? 'No request',
                ],
            ];
        });

        return response()->json($events);
    }
}
