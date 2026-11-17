@extends('layouts.admin')

@section('content')
    @php
        $availableRefund = max(0, (int) $booking->payments->sum('amount'));
    @endphp
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Payments for Booking #{{ $booking->id }}</h3>
            <div>
                <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn btn-outline-secondary">View Booking</a>
                <a href="{{ route('admin.booking.index') }}" class="btn btn-primary">← Back to Bookings</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Booking summary --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Booking Summary</h5>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Customer:</strong> {{ $booking->customer->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Event Type:</strong> {{ $booking->event_type }}
                    </div>
                    <div class="col-md-4">
                        <strong>Total Amount:</strong> ₨ {{ number_format($booking->total_amount, 0) }}
                    </div>
                    <div class="col-md-4 mt-2">
                        <strong>Net Paid:</strong> ₨ {{ number_format($booking->payments->sum('amount'), 0) }}
                    </div>
                    <div class="col-md-4 mt-2">
                        <strong>Remaining:</strong> ₨ {{ number_format($booking->remaining_amount, 0) }}
                    </div>
                    <div class="col-md-4 mt-2">
                        <strong>Event Date:</strong> {{ \Carbon\Carbon::parse($booking->event_date)->format('d-M-Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Existing Payments --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Existing Payments</h5>
                    @modulePerm('booking', 'edit')
                        @if ($availableRefund > 0)
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#add-refund-modal">
                                <i class="fas fa-undo"></i> Add Refund
                            </button>
                        @endif
                    @endmodulePerm
                </div>

                @if ($booking->payments->count() > 0)
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Amount (₨)</th>
                                <th>Date</th>
                                <th>Method</th>
                                @modulePerm('booking', 'edit')
                                    <th style="width: 90px;">Action</th>
                                @endmodulePerm
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->payments as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="{{ $payment->amount < 0 ? 'text-danger' : 'text-success' }}">
                                        ₨ {{ number_format($payment->amount, 0) }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                                    <td>{{ $payment->payment_method ?? '-' }}</td>
                                    @modulePerm('booking', 'edit')
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary edit-payment-btn"
                                                data-id="{{ $payment->id }}"
                                                data-amount="{{ abs((int) $payment->amount) }}"
                                                data-date="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}"
                                                data-method="{{ $payment->payment_method }}"
                                                data-is-refund="{{ $payment->amount < 0 ? 1 : 0 }}"
                                                data-url="{{ route('admin.booking.updatePayment', $payment) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    @endmodulePerm
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info mb-0">No payments added yet.</div>
                @endif
            </div>
        </div>

        {{-- Add New Payment Form --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Add New Payment</h5>

                @modulePerm('booking', 'edit')
                    <form id="paymentForm" method="POST" action="{{ route('admin.booking.addPayment') }}">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" id="remaining_amount" value="{{ $booking->remaining_amount }}">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Payment Amount (₨)</label>
                                <input type="number" name="amount" id="amount" class="form-control" min="1"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control"
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Payment Method</label>
                                <select name="payment_method" class="form-control">
                                    <option value="Cash">Cash</option>
                                    <option value="Online Payment">Online Payment</option>
                                </select>
                            </div>

                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-success mt-3">
                                    <i class="fa fa-plus"></i> Add Payment
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning mb-0">You can view payment history, but you do not have permission to add payments.</div>
                @endmodulePerm
            </div>
        </div>
    </div>

    @modulePerm('booking', 'edit')
        @if ($availableRefund > 0)
            <div class="modal fade" id="add-refund-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('admin.booking.addRefund', $booking->id) }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Refund</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p>Amount paid and not yet refunded: <strong>Rs {{ number_format($availableRefund) }}</strong></p>
                            <div class="form-group">
                                <label>Refund Amount (Rs)</label>
                                <input type="number" name="refund_amount" min="1" max="{{ $availableRefund }}" step="1"
                                    value="{{ old('refund_amount') }}" class="form-control @error('refund_amount') is-invalid @enderror" required>
                                @error('refund_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Refund Date</label>
                                <input type="date" name="refund_date" class="form-control"
                                    value="{{ old('refund_date', now()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group mb-0">
                                <label>Method / Note</label>
                                <select name="refund_method" class="form-control">
                                    <option value="Refund">Refund</option>
                                    <option value="Cancellation refund">Cancellation refund</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Online Payment">Online Payment</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Record Refund</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="modal fade" id="edit-payment-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="edit-payment-form" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment Entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p id="edit-payment-type-hint" class="text-muted small"></p>
                        <div class="form-group">
                            <label>Amount (Rs)</label>
                            <input id="edit-payment-amount" type="number" name="amount" min="1" step="1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input id="edit-payment-date" type="date" name="payment_date" class="form-control" required>
                        </div>
                        <div class="form-group mb-0">
                            <label>Method</label>
                            <input id="edit-payment-method" type="text" name="payment_method" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endmodulePerm
@endsection

@section('script')
    <script>
        const paymentForm = document.getElementById('paymentForm');
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                const amount = parseFloat(document.getElementById('amount').value);
                const remaining = parseFloat(document.getElementById('remaining_amount').value);
                if (amount > remaining) {
                    e.preventDefault();
                    alert('Payment amount cannot exceed remaining amount (₨ ' + remaining + ').');
                }
            });
        }

        $(function() {
            @if ($errors->has('refund_amount') && $availableRefund > 0)
                $('#add-refund-modal').modal('show');
            @endif

            $(document).on('click', '.edit-payment-btn', function() {
                const btn = $(this);
                const isRefund = btn.data('is-refund') == 1;
                $('#edit-payment-form').attr('action', btn.data('url'));
                $('#edit-payment-amount').val(btn.data('amount'));
                $('#edit-payment-date').val(btn.data('date'));
                $('#edit-payment-method').val(btn.data('method') || (isRefund ? 'Refund' : 'Cash'));
                $('#edit-payment-type-hint').text(isRefund
                    ? 'Editing a refund entry. Enter the refund amount as a positive number.'
                    : 'Editing a payment entry.');
                $('#edit-payment-modal').modal('show');
            });
        });
    </script>
@endsection
