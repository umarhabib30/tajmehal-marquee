<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the calendar page.
     */
    public function index()
    {
        $data = [
            'title' => 'Calendar',
            'heading' => 'Bookings Calendar',
            'active' => 'calendar',
        ];

        return view('admin.calendar.index', $data);
    }

    /**
     * Fetch all bookings as FullCalendar event objects.
     */
    public function events()
    {
        $bookings = \App\Models\Booking::with(['customer', 'payments'])->get();

        $events = $bookings->map(function ($booking) {
            $eventDate = $booking->event_date;
            if (empty($eventDate))
                return null;

            $totalPaid = $booking->payments->sum('amount');
            $pendingAmount = max($booking->total_amount - $totalPaid, 0);
            $rawStatus = trim((string) ($booking->status ?? Booking::STATUS_ACTIVE));
            $status = $rawStatus !== '' ? $rawStatus : Booking::STATUS_ACTIVE;
            $isPastEvent = Carbon::parse($eventDate)->isBefore(Carbon::today());
            $isPending = strcasecmp($status, Booking::STATUS_PENDING) === 0;
            $isActive = strcasecmp($status, Booking::STATUS_ACTIVE) === 0;

            // Business rule:
            // - Pending bookings are always yellow
            // - Active bookings in the past are treated as payment-pending (yellow)
            // - Active bookings today/future stay active (grey)
            if ($isPending || ($isActive && $isPastEvent)) {
                $status = Booking::STATUS_PENDING;
                $color = '#ffc107';
            } else {
                $color = Booking::calendarColor($status);
            }
            $textColor = $color === '#ffc107' ? '#212529' : '#ffffff';

            return [
                'id' => $booking->id,
                // ✅ Only show event_type and time_slot
                'title' => trim(($booking->event_type ?? 'Event') . ' - ' . ($booking->time_slot ?? 'N/A')),
                'start' => "{$eventDate}T" . ($booking->start_time ?? '00:00:00'),
                'end' => "{$eventDate}T" . ($booking->end_time ?? '23:59:59'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => $textColor,
                'allDay' => false,
                'extendedProps' => [
                    // Customer Info
                    'customer_name' => $booking->customer->name ?? 'N/A',
                    'customer_phone' => $booking->customer->phone ?? 'N/A',
                    'customer_address' => $booking->customer->address ?? 'N/A',
                    // Event Info
                    'event_type' => $booking->event_type ?? 'N/A',
                    'event_date' => $booking->event_date ?? 'N/A',
                    'time_slot' => $booking->time_slot ?? 'N/A',
                    'start_time' => $booking->start_time ?? 'N/A',
                    'end_time' => $booking->end_time ?? 'N/A',
                    'hall_name' => $booking->hall_name ?? 'N/A',
                    'guests_count' => $booking->guests_count ?? 0,
                    'status' => $status,
                    'status_color' => $color,
                    'status_text_color' => $textColor,
                    // Payments
                    'total_amount' => $booking->total_amount ?? 0,
                    'total_paid' => $totalPaid,
                    'pending_amount' => $pendingAmount,
                    // Other details
                    'special_request' => $booking->notes ?? 'No special request',
                ],
            ];
        })->filter();

        return response()->json($events->values());
    }
}
