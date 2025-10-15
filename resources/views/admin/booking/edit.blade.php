@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <h5 class="card-header">{{ $heading }} - {{ $title }}</h5>
            <div class="card-body">
                <a href="{{ route('booking.index') }}" class="btn btn-secondary mb-3">Back</a>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('booking.update', $booking->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $booking->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="text" id="email" class="form-control" value="{{ $booking->customer->email ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" id="phone" class="form-control" value="{{ $booking->customer->phone ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Event Type</label>
                            <select name="event_type" class="form-control" required>
                                <option value="Birthday" {{ $booking->event_type=='Birthday'?'selected':'' }}>Birthday</option>
                                <option value="Wedding" {{ $booking->event_type=='Wedding'?'selected':'' }}>Wedding</option>
                                <option value="Anniversary" {{ $booking->event_type=='Anniversary'?'selected':'' }}>Anniversary</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Guests Count</label>
                            <input type="number" name="guests_count" class="form-control" value="{{ $booking->guests_count }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Booking Date</label>
                            <input type="date" name="booking_date" class="form-control" value="{{ $booking->booking_date }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $booking->start_date }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $booking->end_date }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Booking Time</label>
                            <input type="time" name="booking_time" class="form-control" value="{{ $booking->booking_time }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Time Slot</label>
                            <select name="time_slot" class="form-control">
                                <option {{ $booking->time_slot=='Morning'?'selected':'' }}>Morning</option>
                                <option {{ $booking->time_slot=='Evening'?'selected':'' }}>Evening</option>
                                <option {{ $booking->time_slot=='Night'?'selected':'' }}>Night</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Hall Name</label>
                            <input type="text" name="hall_name" class="form-control" value="{{ $booking->hall_name }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Decoration Type</label>
                            <input type="text" name="decoration_type" class="form-control" value="{{ $booking->decoration_type }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Menu Package</label>
                            <input type="text" name="menu_package" class="form-control" value="{{ $booking->menu_package }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Total Amount</label>
                            <input type="number" name="total_amount" id="total_amount" class="form-control" value="{{ $booking->total_amount }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Discount (%)</label>
                            <input type="number" name="discount_percent" id="discount_percent" class="form-control" value="{{ $booking->discount_percent }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Advance Payment</label>
                            <input type="number" name="advance_payment" id="advance_payment" class="form-control" value="{{ $booking->advance_payment }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Remaining Amount</label>
                            <input type="number" id="remaining_amount" class="form-control" value="{{ $booking->remaining_amount }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-control">
                                <option {{ $booking->payment_status=='Pending'?'selected':'' }}>Pending</option>
                                <option {{ $booking->payment_status=='Paid'?'selected':'' }}>Paid</option>
                                <option {{ $booking->payment_status=='Partial'?'selected':'' }}>Partial</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option {{ $booking->status=='Active'?'selected':'' }}>Active</option>
                                <option {{ $booking->status=='Cancelled'?'selected':'' }}>Cancelled</option>
                                <option {{ $booking->status=='Completed'?'selected':'' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Special Request</label>
                            <textarea name="special_request" class="form-control" rows="3">{{ $booking->special_request }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Update Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('customer_id').addEventListener('change', function() {
    let customerId = this.value;
    if(customerId){
        fetch(`{{ url('admin/booking/get-customer') }}/${customerId}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('email').value = data.email || '';
            document.getElementById('phone').value = data.phone || '';
        });
    }
});

['total_amount', 'discount_percent', 'advance_payment'].forEach(id => {
    document.getElementById(id).addEventListener('input', calcRemaining);
});

function calcRemaining(){
    let total = parseFloat(document.getElementById('total_amount').value) || 0;
    let discount = parseFloat(document.getElementById('discount_percent').value) || 0;
    let advance = parseFloat(document.getElementById('advance_payment').value) || 0;
    let discounted = total - (total * discount / 100);
    document.getElementById('remaining_amount').value = (discounted - advance).toFixed(2);
}
</script>
@endsection
