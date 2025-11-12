@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 fw-bold">{{ $heading ?? 'Booking Details' }}</h3>

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="fas fa-file-alt me-2"></i> Booking Overview
            </div>

            <div class="card-body p-4">

                {{-- ================= CUSTOMER INFO ================= --}}
                <h5 class="border-bottom pb-2 mb-3 text-uppercase text-secondary">Customer Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6"><strong>Name:</strong> {{ $booking->customer->name ?? 'N/A' }}</div>
                    <div class="col-md-6"><strong>Phone:</strong> {{ $booking->customer->phone ?? 'N/A' }}</div>
                    <div class="col-md-6"><strong>Email:</strong> {{ $booking->customer->email ?? 'N/A' }}</div>
                    <div class="col-md-6"><strong>Address:</strong> {{ $booking->customer->address ?? 'N/A' }}</div>
                </div>

                {{-- ================= EVENT INFO ================= --}}
                <h5 class="border-bottom pb-2 mb-3 text-uppercase text-secondary">Event Details</h5>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Event Type:</strong> {{ $booking->event_type ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Hall Name:</strong> {{ $booking->hall_name ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Event Date:</strong>
                        {{ $booking->event_date }}
                    </div>
                    <div class="col-md-4"><strong>Time Slot:</strong> {{ $booking->time_slot ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Start Time:</strong>
                        {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : 'N/A' }}
                    </div>
                    <div class="col-md-4"><strong>End Time:</strong>
                        {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : 'N/A' }}
                    </div>
                    <div class="col-md-4"><strong>Guests Count:</strong> {{ $booking->guests_count ?? 0 }}</div>
                    <div class="col-md-4"><strong>Extra Guest:</strong> {{ $booking->extra_guests ?? 0 }}</div>
                </div>

                {{-- ================= MENU PACKAGE ================= --}}
                <h5 class="border-bottom pb-2 mb-3 text-uppercase text-secondary">Menu Package</h5>
                <p><strong>Package:</strong> {{ $booking->dishPackage->name ?? 'Custom Selection' }}</p>

                <div class="d-flex flex-wrap gap-2 mt-2">
                    @if (!empty($booking->dishPackage?->dishes))
                        @foreach ($booking->dishPackage->dishes as $dish)
                            <span class="badge rounded-pill bg-primary px-3 py-2  ml-2">{{ $dish->name }}</span>
                        @endforeach
                    @elseif(!empty($dishes) && $dishes->count())
                        @foreach ($dishes as $dish)
                            <span class="badge rounded-pill bg-primary px-3 py-2  ml-2">{{ $dish->name }}</span>
                        @endforeach
                    @else
                        <span class="text-muted fst-italic">No dishes selected.</span>
                    @endif
                </div>


                {{-- ================= DECORATIONS ================= --}}
                <h5 class="border-bottom pb-2 mb-3 text-uppercase text-secondary">Decorations</h5>
                @php
                    $decorations = is_array($booking->decorations)
                        ? $booking->decorations
                        : json_decode($booking->decorations, true) ?? [];
                @endphp
                <p><strong>Selected:</strong>
                    @if (!empty($decorations))
                        {{ implode(', ', $decorations) }}
                    @else
                        <span class="text-muted fst-italic">No decorations selected.</span>
                    @endif
                </p>
                <p><strong>Decoration Charges:</strong> ₨ {{ number_format($booking->decore_price ?? 0, 2) }}</p>

                {{-- ================= PAYMENT INFO ================= --}}
                <h5 class="border-bottom pb-2 mb-3 text-uppercase text-secondary">Payment Information</h5>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Per Head Price:</strong> ₨
                        {{ number_format($booking->per_head_price ?? 0, ) }}</div>
                    <div class="col-md-4"><strong>Tax:</strong> ₨ {{ number_format($booking->tax_amount ?? 0,) }}</div>
                    <div class="col-md-4"><strong>Total Amount:</strong> ₨
                        {{ number_format($booking->total_amount ?? 0,) }}</div>
                    <div class="col-md-4"><strong>Advance Payment:</strong> ₨
                        {{ number_format($booking->advance_payment ?? 0,) }}</div>
                    <div class="col-md-4"><strong>Remaining Amount:</strong> ₨
                        {{ number_format($booking->remaining_amount ?? 0,) }}</div>
                    <div class="col-md-4"><strong>Payment Method:</strong> {{ $booking->payment_method ?? 'N/A' }}</div>
                </div>

                {{-- ================= PAYMENT HISTORY ================= --}}
                @if ($booking->payments && $booking->payments->count() > 0)
                    <h5 class="border-bottom pb-2 mb-3 text-uppercase text-secondary">Payment History</h5>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Payment Date</th>
                                    <th>Amount (₨)</th>
                                    <th>Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($booking->payments as $index => $payment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                                        <td>₨ {{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Total Paid:</th>
                                    <th colspan="2">₨ {{ number_format($booking->payments->sum('amount'), 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif


                {{-- ================= SIGNATURES ================= --}}
                @if ($booking->customer_signature || $booking->manager_signature)
                    <h5 class="border-bottom pb-2 mb-3 text-uppercase text-secondary">Signatures</h5>
                    <div class="row text-center mb-3">
                        <div class="col-md-6">
                            <p class="fw-semibold">Customer Signature</p>
                            @if ($booking->customer_signature)
                                <img src="{{ $booking->customer_signature }}" class="border rounded shadow-sm"
                                    style="max-height:130px;">
                            @else
                                <p class="text-muted fst-italic">Not provided</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p class="fw-semibold">Manager Signature</p>
                            @if ($booking->manager_signature)
                                <img src="{{ $booking->manager_signature }}" class="border rounded shadow-sm"
                                    style="max-height:130px;">
                            @else
                                <p class="text-muted fst-italic">Not provided</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ================= NOTES ================= --}}
                <h5 class="border-bottom pb-2 mb-3 text-uppercase text-secondary">Notes</h5>
                <p>{{ $booking->notes ?: 'No additional notes provided.' }}</p>

            </div>

            <div class="card-footer text-end bg-light">
                <a href="{{ route('admin.booking.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Bookings
                </a>
                <a href="{{ route('admin.booking.invoice', $booking->id) }}" class="btn btn-primary">
                    Generate Invoice
                </a>
            </div>
        </div>
    </div>
@endsection
