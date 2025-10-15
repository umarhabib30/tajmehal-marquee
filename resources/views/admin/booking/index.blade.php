@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <h5 class="card-header">{{ $heading }}</h5>
                <div class="card-body">

                    <a href="{{ route('booking.create') }}" class="btn btn-primary mb-3">
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
                                    <th>Action</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->event_type }}</td>
                                        <td>{{ $booking->start_date }}</td>
                                        <td>{{ number_format($booking->advance_payment, 0) }}</td>
                                        <!-- View button -->
                                        <td>
                                            <a href="{{ route('booking.show', $booking->id) }}"
                                                class="btn btn-info btn-sm w-100">View</a>
                                        </td>
                                        <!-- Edit button -->
                                        <td>
                                            <a href="{{ route('booking.edit', $booking->id) }}"
                                                class="btn btn-primary btn-sm w-100">Edit</a>
                                        </td>
                                        <!-- Delete button -->
                                        <td>
                                             <form action="{{ route('booking.delete', $booking->id) }}" method="POST"

                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>
                                        </form>
                                        </td>
                                      


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No bookings found.</td>
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
