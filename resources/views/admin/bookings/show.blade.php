@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>{{ $heading }}</h3>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Customer Info</h5>
            <p>Name: {{ $booking->customer->name }}</p>
            <p>Phone: {{ $booking->customer->phone }}</p>
            <p>Email: {{ $booking->customer->email }}</p>
            <p>Address: {{ $booking->customer->address }}</p>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Event Info</h5>
            <p>Event Type: {{ $booking->event_type }}</p>
            <p>Hall Name: {{ $booking->hall_name }}</p>
            <p>Booking Date: {{ $booking->booking_date->format('d-M-Y') }}</p>
            <p>Time Slot: {{ $booking->time_slot }} ({{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }})</p>
            <p>Guests Count: {{ $booking->guests_count }}</p>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Menu Package</h5>
            <p>Package: {{ $booking->dishPackage->name ?? '' }}</p>
            <ul>
              @foreach($booking->dishPackage->dishes as $dish)
                <li>{{ $dish->name }}</li>
              @endforeach
            </ul>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Decorations</h5>
            <p>Selected: {{ implode(', ', $booking->decoration ?? []) }}</p>
            <p>Decoration Charges: {{ number_format($booking->decoration_amount,2) }}</p>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Payment Info</h5>
            <p>Per Head Price: {{ number_format($booking->per_head_price,2) }}</p>
            <p>Tax: {{ number_format($booking->tax,2) }}</p>
            <p>Total Amount: {{ number_format($booking->total_amount,2) }}</p>
            <p>Advance Payment: {{ number_format($booking->advance_payment,2) }}</p>
            <p>Remaining/Pending Amount: {{ number_format($booking->remaining_amount,2) }}</p>
            <p>Payment Method: {{ $booking->payment_method }}</p>
            <p>Status: {{ $booking->status }}</p>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Notes</h5>
            <p>{{ $booking->notes }}</p>
        </div>
    </div>

    <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
