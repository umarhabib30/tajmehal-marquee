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

                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="text" id="email" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" id="phone" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Event Type</label>
                            <select name="event_type" class="form-control" required>
                                <option value="">Select Event Type</option>
                                <option>Birthday</option>
                                <option>Wedding</option>
                                <option>Anniversary</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Guests Count</label>
                            <input type="number" name="guests_count" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Booking Date</label>
                            <input type="date" name="booking_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Booking Time</label>
                            <input type="time" name="booking_time" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Time Slot</label>
                            <select name="time_slot" class="form-control">
                                <option>Morning</option>
                                <option>Evening</option>
                                <option>Night</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Hall Name</label>
                            <input type="text" name="hall_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Decoration Type</label>
                            <input type="text" name="decoration_type" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Menu Package</label>
                            <input type="text" name="menu_package" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Total Amount</label>
                            <input type="number" name="total_amount" id="total_amount" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Discount (%)</label>
                            <input type="number" name="discount_percent" id="discount_percent" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Advance Payment</label>
                            <input type="number" name="advance_payment" id="advance_payment" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Remaining Amount</label>
                            <input type="number" id="remaining_amount" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-control">
                                <option>Pending</option>
                                <option>Paid</option>
                                <option>Partial</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option>Active</option>
                                <option>Cancelled</option>
                                <option>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Special Request</label>
                            <textarea name="special_request" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Save Booking</button>
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
