<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $title = 'Calendar';
        $heading = 'Bookings Calendar';
        $active = 'calendar';
        return view('admin.calendar.index', compact('title', 'heading', 'active'));
    }

    public function events()
    {
        $bookings = Booking::with('customer')->get();

        $events = $bookings->map(function ($booking) {
            // Set color based on booking status
            $color = match ($booking->status) {
                'Completed' => '#28a745', // green
                'Cancelled' => '#dc3545', // red
                default => '#007bff', // blue
            };

            return [
                'id' => $booking->id,
                'title' => $booking->event_type . ' - ' . ($booking->customer->name ?? 'Unknown'),
                'start' => $booking->booking_date . 'T' . $booking->booking_time,
                'color' => $color,
                'extendedProps' => [
                    'customer_name' => $booking->customer->name ?? '',
                    'event_type' => $booking->event_type,
                    'booking_date' => $booking->booking_date,
                    'booking_time' => $booking->booking_time,
                    'hall_name' => $booking->hall_name,
                    'special_request' => $booking->special_request,
                    'status' => $booking->status,
                ]
            ];
        });

        return response()->json($events);
    }
}
