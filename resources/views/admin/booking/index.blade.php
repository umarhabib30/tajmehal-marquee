@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <h5 class="card-header">{{ $heading }}</h5>
            <div class="card-body">

                {{-- Add New Booking --}}
                <a href="{{ route('admin.booking.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Add New Booking
                </a>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered first">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Event Type</th>
                                <th>Start Date</th>
                                <th>Advance Payment</th>
                                <th>Signature</th>
                                <th colspan="3" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->event_type }}</td>
                                    <td>{{ $booking->start_date }}</td>
                                    <td>{{ number_format($booking->advance_payment, 0) }}</td>

                                    {{-- Signature Display --}}
                                    <td class="text-center">
                                        @if($booking->customer_signature)
                                            <img src="{{ $booking->customer_signature }}" alt="Signature"
                                                style="width: 120px; height: 50px; border: 1px solid #ccc;">
                                        @else
                                            <span class="text-muted">No Signature</span>
                                        @endif
                                    </td>

                                    {{-- View Button --}}
                                    <td>
                                        <a href="{{ route('admin.booking.show', $booking->id) }}"
                                            class="btn btn-info btn-sm w-100">View</a>
                                    </td>

                                    {{-- Edit Button --}}
                                    <td>
                                        <a href="{{ route('admin.booking.edit', $booking->id) }}"
                                            class="btn btn-primary btn-sm w-100">Edit</a>
                                    </td>

                                    {{-- Delete Button --}}
                                    <td>
                                        <form action="{{ route('admin.booking.destroy', $booking->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
