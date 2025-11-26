@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="fw-bold">{{ $heading }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="bookingTable" class="table table-striped table-bordered first">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Phone Number</th>
                                    <th>Event Type</th>
                                    <th>Hall</th>
                                    <th>Event Date</th>
                                    <th>Payment Status</th>
                                    <th>Details</th>
                                    <th>Print Details</th>
                                    <th>Invoice</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                    @php
                                        $paidAmount = $booking->payments->sum('amount');
                                        $totalAmount = $booking->total_amount;
                                        $isPaid = $paidAmount >= $totalAmount;
                                        $status = $isPaid ? 'Paid' : 'Pending';
                                    @endphp
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->customer->phone ?? 'N/A' }}</td>
                                        <td>{{ $booking->event_type }}</td>
                                        <td>{{ $booking->hall_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('d-M-Y') }}</td>
                                        <td>
                                            <span class="badge {{ $isPaid ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $status }}
                                            </span>
                                            <small class="text-muted d-block">
                                                Paid: ₨ {{ number_format($paidAmount, 0) }} /
                                                {{ number_format($totalAmount, 0) }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.booking.show', $booking->id) }}"
                                                class="btn btn-sm btn-primary text-white">
                                                Show
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.booking.invoice', $booking->id) }}"
                                                class="btn btn-sm btn-primary text-white">
                                                Invoice
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.booking.details.print', $booking->id) }}"
                                                class="btn btn-sm btn-primary text-white">
                                                Print Details
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.booking.edit', $booking->id) }}"
                                                class="btn btn-sm btn-primary text-white">
                                                Edit
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-success add-payment-btn"
                                                href="{{ route('admin.booking.addPaymentPage', $booking->id) }}">
                                                <i class="fa fa-plus"></i> Add Payment
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Include DataTables + SweetAlert --}}
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {


            // Delete button
            $(document).on('click', '.delete-btn', function() {
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will permanently delete the booking!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Deleted!', 'Booking has been removed.', 'success');
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                Swal.fire('Error', data.message || 'Failed to delete booking.',
                                    'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'Request failed.', 'error'));
                });
            });

        });
    </script>
@endsection
@endsection
