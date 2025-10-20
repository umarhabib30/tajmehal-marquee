@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <h5 class="card-header">{{ $heading }}</h5>
            <div class="card-body">
                
                {{-- ✅ Fixed Route --}}
                <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Customer Name</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->customer->name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Email</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->customer->email ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Phone</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->customer->phone ?? 'N/A' }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Event Type</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->event_type }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Guests Count</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->guests_count }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Booking Date</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->booking_date }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Start Date</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->start_date }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>End Date</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->end_date }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Booking Time</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->booking_time }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Time Slot</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->time_slot }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Hall Name</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->hall_name }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Decoration Type</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->decoration_type }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Menu Package</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->menu_package }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Total Amount</strong></label>
                        <input type="text" class="form-control" value="{{ number_format($booking->total_amount, 0) }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Discount (%)</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->discount_percent }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Advance Payment</strong></label>
                        <input type="text" class="form-control" value="{{ number_format($booking->advance_payment, 0) }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Remaining Amount</strong></label>
                        <input type="text" class="form-control" value="{{ number_format($booking->remaining_amount, 0) }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Payment Status</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->payment_status }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Status</strong></label>
                        <input type="text" class="form-control" value="{{ $booking->status }}" readonly>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label"><strong>Special Request</strong></label>
                        <textarea class="form-control" rows="2" readonly>{{ $booking->special_request }}</textarea>
                    </div>

                    {{-- ✅ Customer Signature --}}
                    @if($booking->customer_signature)
                        <div class="col-md-12 mt-4 text-center">
                            <label class="form-label d-block"><strong>Customer Signature</strong></label>
                            <div class="border p-2 d-inline-block">
                                <img src="{{ $booking->customer_signature }}" alt="Customer Signature" class="img-fluid"
                                    style="max-height:200px; border:1px solid #ddd;">
                            </div>
                        </div>
                    @else
                        <div class="col-md-12 mt-4 text-center">
                            <span class="text-muted">No signature available for this booking.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
