@extends('layouts.admin')

@section('style')
    <style>
        .bookings-page #bookingTable th,
        .bookings-page #bookingTable td {
            vertical-align: middle;
        }

        .bookings-page #bookingTable th.action-col,
        .bookings-page #bookingTable td.action-col {
            width: 52px;
            text-align: center;
            white-space: nowrap;
            padding-left: 6px;
            padding-right: 6px;
        }

        .bookings-page #bookingTable th.action-col {
            font-size: 0.78rem;
            line-height: 1.2;
        }

        .bookings-page .booking-icon-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #352a86;
            font-size: 1rem;
            line-height: 1;
            cursor: pointer;
            text-decoration: none;
            transition: color 0.15s ease, transform 0.15s ease;
        }

        .bookings-page .booking-icon-link:hover {
            color: #4b3eb6;
            transform: scale(1.1);
            text-decoration: none;
        }

        .bookings-page .booking-icon-link.text-info {
            color: #17a2b8;
        }

        .bookings-page .booking-icon-link.text-info:hover {
            color: #138496;
        }

        .bookings-page .booking-icon-link.text-success {
            color: #28a745;
        }

        .bookings-page .booking-icon-link.text-success:hover {
            color: #1e7e34;
        }

        .bookings-page .booking-icon-link.text-danger {
            color: #dc3545;
        }

        .bookings-page .booking-icon-link.text-danger:hover {
            color: #bd2130;
        }

        .bookings-page .amount-cell {
            white-space: nowrap;
        }

        .bookings-page .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.55em;
        }

        .bookings-page .status-badge.badge-success {
            background-color: #21ae41;
            color: #fff;
        }

        .bookings-page .status-badge.badge-warning {
            background-color: #f3b600;
            color: #2e2f39;
        }

        .bookings-page .status-badge.badge-danger {
            background-color: #da0419;
            color: #fff;
        }

        .bookings-page .status-badge.badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="row bookings-page">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="fw-bold">{{ $heading }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="bookingTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Event Type</th>
                                    <th>Hall</th>
                                    <th>Event Date</th>
                                    <th>Total Amount</th>
                                    <th>Pending Amount</th>
                                    <th>Status</th>
                                    <th class="action-col">Details</th>
                                    <th class="action-col">Print</th>
                                    <th class="action-col">Invoice</th>
                                    @modulePerm('booking', 'edit')
                                        <th class="action-col">Edit</th>
                                    @endmodulePerm
                                    @modulePerm('booking', 'delete')
                                        <th class="action-col">Delete</th>
                                    @endmodulePerm
                                    @modulePerm('booking', 'edit')
                                        <th class="action-col">Add Pay</th>
                                    @endmodulePerm


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                    @php
                                        $totalAmount = $booking->total_amount;
                                        $paidAmount = $booking->payments->sum('amount');
                                        $pendingAmount = max($totalAmount - $paidAmount, 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->event_type }}</td>
                                        <td>{{ $booking->hall_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('d-M-Y') }}</td>
                                        <td class="amount-cell">Rs {{ number_format($totalAmount, 0) }}</td>
                                        <td class="amount-cell">Rs {{ number_format($pendingAmount, 0) }}</td>
                                        <td>
                                            <span class="badge status-badge {{ $booking->statusBadgeClass() }}">
                                                {{ $booking->status ?? 'Active' }}
                                            </span>
                                        </td>

                                        <td class="action-col">
                                            <a href="{{ route('admin.booking.show', $booking->id) }}"
                                                class="booking-icon-link" title="View details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                        <td class="action-col">
                                            <a href="{{ route('admin.booking.details.print', $booking->id) }}"
                                                class="booking-icon-link" title="Print details">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                        <td class="action-col">
                                            <a href="{{ route('admin.booking.invoice', $booking->id) }}"
                                                class="booking-icon-link text-info" title="Invoice">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                        </td>
                                        @modulePerm('booking', 'edit')
                                            <td class="action-col">
                                                <a href="{{ route('admin.booking.edit', $booking->id) }}"
                                                    class="booking-icon-link" title="Edit booking">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        @endmodulePerm
                                        @modulePerm('booking', 'delete')
                                            <td class="action-col">
                                                <button type="button"
                                                    class="booking-icon-link text-danger delete-btn"
                                                    data-url="{{ route('admin.booking.delete', $booking->id) }}"
                                                    title="Delete booking">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        @endmodulePerm
                                        @modulePerm('booking', 'edit')
                                            <td class="action-col">
                                                <a href="{{ route('admin.booking.addPaymentPage', $booking->id) }}"
                                                    class="booking-icon-link text-success" title="Add payment">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            </td>
                                        @endmodulePerm

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
            $('#bookingTable').DataTable({
                dom: "<'row align-items-center mb-2'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'f><'col-sm-12 col-md-4'p>>" +
                    "rt" +
                    "<'row'<'col-sm-12 col-md-5'i>>",
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: 'action-col' }
                ]
            });

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
