@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <h5 class="card-header">{{ $heading }}</h5>
                <div class="card-body">
                    <a href="{{ route('booking.index') }}" class="btn btn-secondary mb-3">Back</a>

                    <table class="table table-bordered">
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $booking->customer->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $booking->customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Event Type</th>
                            <td>{{ $booking->event_type }}</td>
                        </tr>
                        <tr>
                            <th>Guests Count</th>
                            <td>{{ $booking->guests_count }}</td>
                        </tr>
                        <tr>
                            <th>Booking Date</th>
                            <td>{{ $booking->booking_date }}</td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td>{{ $booking->start_date }}</td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td>{{ $booking->end_date }}</td>
                        </tr>
                        <tr>
                            <th>Booking Time</th>
                            <td>{{ $booking->booking_time }}</td>
                        </tr>
                        <tr>
                            <th>Time Slot</th>
                            <td>{{ $booking->time_slot }}</td>
                        </tr>
                        <tr>
                            <th>Hall Name</th>
                            <td>{{ $booking->hall_name }}</td>
                        </tr>
                        <tr>
                            <th>Decoration Type</th>
                            <td>{{ $booking->decoration_type }}</td>
                        </tr>
                        <tr>
                            <th>Menu Package</th>
                            <td>{{ $booking->menu_package }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td>{{ number_format($booking->total_amount, 0) }}</td>
                        </tr>
                        <tr>
                            <th>Discount (%)</th>
                            <td>{{ $booking->discount_percent }}</td>
                        </tr>
                        <tr>
                            <th>Advance Payment</th>
                            <td>{{ number_format($booking->advance_payment, 0) }}</td>
                        </tr>
                        <tr>
                            <th>Remaining Amount</th>
                            <td>{{ number_format($booking->remaining_amount, 0) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Status</th>
                            <td>{{ $booking->payment_status }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $booking->status }}</td>
                        </tr>
                        <tr>
                            <th>Special Request</th>
                            <td>{{ $booking->special_request }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
