@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Payments for Booking #{{ $booking->id }}</h3>
            <a href="{{ route('admin.booking.index') }}" class="btn btn-primary">← Back to Bookings</a>
        </div>

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
                        <strong>Paid:</strong> ₨ {{ number_format($booking->payments->sum('amount'), 0) }}
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
                <h5 class="fw-bold mb-3">Existing Payments</h5>

                @if ($booking->payments->count() > 0)
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Amount (₨)</th>
                                <th>Date</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->payments as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>₨ {{ number_format($payment->amount, 0) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                                    <td>{{ $payment->payment_method ?? '-' }}</td>
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
            </div>
        </div>
    </div>


@endsection
@section('script')
    {{-- ✅ JavaScript Validation --}}
    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('amount').value);
            const remaining = parseFloat(document.getElementById('remaining_amount').value);

            if (amount > remaining) {
                e.preventDefault();
                toastr.error('Payment amount cannot exceed the remaining balance (₨ ' + remaining.toLocaleString() + ').');
                return false;
            }
        });
    </script>
@endsection
