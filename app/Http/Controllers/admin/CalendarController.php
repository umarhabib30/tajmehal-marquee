<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display the calendar page.
     */
    public function index()
    {
        $data = [
            'title'   => 'Calendar',
            'heading' => 'Bookings Calendar',
            'active'  => 'calendar',
        ];

        return view('admin.calendar.index', $data);
    }

    /**
     * Fetch all bookings as FullCalendar event objects.
     */
    public function events()
    {
        $bookings = Booking::with('customer')->get();

        $events = $bookings->map(function ($booking) {
            // Determine color based on booking status
            $color = match (strtolower($booking->status ?? '')) {
                'completed' => '#28a745', // Green
                'cancelled' => '#dc3545', // Red
                'pending'   => '#ffc107', // Yellow
                default     => '#007bff', // Blue for Active/Other
            };

            // Ensure valid dates and times
            $startDate = $booking->start_date ?? $booking->booking_date;
            $endDate   = $booking->end_date   ?? $booking->booking_date;
            $startTime = $booking->booking_time ?? '00:00:00';
            $endTime   = $booking->end_time ?? $booking->booking_time ?? '23:59:59';

            // Combine date and time safely
            $start = $startDate ? "{$startDate}T{$startTime}" : null;
            $end   = $endDate   ? "{$endDate}T{$endTime}"     : null;

            // Build FullCalendar-compatible event object
            return [
                'id'    => $booking->id,
                'title' => trim(($booking->event_type ?? 'Event') . ' - ' . ($booking->customer->name ?? 'Unknown')),
                'start' => $start,
                'end'   => $end,
                'color' => $color,
                'allDay' => false,

                // Extra booking info for event click/details
                'extendedProps' => [
                    'customer_name'     => $booking->customer->name ?? 'N/A',
                    'customer_email'    => $booking->customer->email ?? 'N/A',
                    'customer_phone'    => $booking->customer->phone ?? 'N/A',
                    'event_type'        => $booking->event_type ?? 'Not specified',
                    'guests_count'      => $booking->guests_count ?? 0,
                    'booking_date'      => $booking->booking_date ?? 'N/A',
                    'booking_time'      => $booking->booking_time ?? 'N/A',
                    'time_slot'         => $booking->time_slot ?? 'N/A',
                    'hall_name'         => $booking->hall_name ?? 'N/A',
                    'decoration_type'   => $booking->decoration_type ?? 'N/A',
                    'menu_package'      => $booking->menu_package ?? 'N/A',
                    'total_amount'      => $booking->total_amount ?? 0,
                    'discount_percent'  => $booking->discount_percent ?? 0,
                    'advance_payment'   => $booking->advance_payment ?? 0,
                    'remaining_amount'  => $booking->remaining_amount ?? 0,
                    'payment_status'    => $booking->payment_status ?? 'Pending',
                    'status'            => $booking->status ?? 'Active',
                    'special_request'   => $booking->special_request ?? 'No special request',
                ],
            ];
        });

        return response()->json($events);
    }
}
