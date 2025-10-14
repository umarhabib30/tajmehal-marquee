@extends('layouts.admin')
@section('content')

<div class="row">
    <!-- ============================================================== -->
    <!-- bookings table  -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">{{ $heading ?? 'Bookings Table' }}</h5>
            <div class="card-body">
                <a href="{{ route('booking.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Add New Booking
                </a>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered first">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Event Type</th>
                                <th>Guests</th>
                                <th>Date</th>
                                <th>Time Slot</th>
                                <th>Hall</th>
                                <th>Total</th>
                                <th>Advance</th>
                                <th>Remaining</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->customer->email ?? 'N/A' }}</td>
                                    <td>{{ $booking->customer->phone ?? 'N/A' }}</td>
                                    <td>{{ $booking->event_type }}</td>
                                    <td>{{ $booking->guests_count }}</td>
                                    <td>{{ $booking->booking_date }}</td>
                                    <td>{{ $booking->time_slot }}</td>
                                    <td>{{ $booking->hall_name }}</td>
                                    <td>{{ number_format($booking->total_amount, 0) }}</td>
                                    <td>{{ number_format($booking->advance_payment, 0) }}</td>
                                    <td>{{ number_format($booking->remaining_amount, 0) }}</td>
                                    <td>
                                        @if ($booking->payment_status === 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif ($booking->payment_status === 'Partial')
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @else
                                            <span class="badge bg-danger">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($booking->status === 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif ($booking->status === 'Completed')
                                            <span class="badge bg-primary">Completed</span>
                                        @elseif ($booking->status === 'Cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('booking.edit', $booking->id) }}" 
                                           class="btn btn-primary btn-sm w-100">Edit</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('booking.delete', $booking->id) }}" 
                                              method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this booking?')"
                                                    class="btn btn-danger btn-sm w-100">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center text-muted py-3">
                                        No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end bookings table  -->
    <!-- ============================================================== -->
</div>

@endsection
