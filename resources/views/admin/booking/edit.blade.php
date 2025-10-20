@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $heading }}</h5>
                <span>{{ $title }}</span>
            </div>

            <div class="card-body">
                <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                {{-- Alert Messages --}}
                <div id="alertMessage"></div>

                {{-- Booking Form --}}
                <form id="bookingForm">
                    @csrf
                    <div class="row">
                        {{-- Customer --}}
                        <div class="col-md-6 mb-3">
                            <label>Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $booking->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
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

                        {{-- Event Type --}}
                        <div class="col-md-6 mb-3">
                            <label>Event Type</label>
                            <select name="event_type" class="form-control" required>
                                <option value="">Select Event Type</option>
                                <option value="Birthday" {{ $booking->event_type == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                                <option value="Wedding" {{ $booking->event_type == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                                <option value="Anniversary" {{ $booking->event_type == 'Anniversary' ? 'selected' : '' }}>Anniversary</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Guests Count</label>
                            <input type="number" name="guests_count" class="form-control" value="{{ $booking->guests_count }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Booking Date</label>
                            <input type="date" name="booking_date" id="booking_date" class="form-control"
                                value="{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d') }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d') : '' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('Y-m-d') : '' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Booking Time</label>
                            <input type="time" name="booking_time" class="form-control" value="{{ $booking->booking_time }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Time Slot</label>
                            <select name="time_slot" class="form-control">
                                <option value="Morning" {{ $booking->time_slot == 'Morning' ? 'selected' : '' }}>Morning</option>
                                <option value="Evening" {{ $booking->time_slot == 'Evening' ? 'selected' : '' }}>Evening</option>
                                <option value="Night" {{ $booking->time_slot == 'Night' ? 'selected' : '' }}>Night</option>
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
                                <option value="Pending" {{ $booking->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Paid" {{ $booking->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Partial" {{ $booking->payment_status == 'Partial' ? 'selected' : '' }}>Partial</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="Active" {{ $booking->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Cancelled" {{ $booking->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Completed" {{ $booking->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Special Request</label>
                            <textarea name="special_request" class="form-control" rows="3">{{ $booking->special_request }}</textarea>
                        </div>

                        {{-- Signature Section --}}
                        <div class="col-md-12 mb-3">
                            <label>Customer Signature</label><br>
                            <canvas id="signaturePad" style="border:1px solid #ccc; width:100%; height:200px;"></canvas>
                            <input type="hidden" name="customer_signature" id="signatureInput" value="{{ $booking->customer_signature }}">
                            <button type="button" id="clearSignature" class="btn btn-warning btn-sm mt-2">Clear Signature</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Update Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Signature Pad
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    canvas.addEventListener('mousedown', () => drawing = true);
    canvas.addEventListener('mouseup', () => drawing = false);
    canvas.addEventListener('mouseout', () => drawing = false);
    canvas.addEventListener('mousemove', draw);

    function draw(e) {
        if (!drawing) return;
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = 'black';
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(e.offsetX, e.offsetY);
    }

    document.getElementById('clearSignature').addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });

    // Load existing signature
    @if($booking->customer_signature)
        let img = new Image();
        img.src = "{{ $booking->customer_signature }}";
        img.onload = function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        }
    @endif

    // Fetch and fill customer details
    function fillCustomerDetails(customerId) {
        if(customerId){
            $.get(`{{ url('admin/booking/get-customer') }}/${customerId}`, function(data){
                $('#email').val(data.email ?? '');
                $('#phone').val(data.phone ?? '');
            });
        } else {
            $('#email').val('');
            $('#phone').val('');
        }
    }

    $(document).ready(function(){
        fillCustomerDetails($('#customer_id').val());

        $('#customer_id').on('change', function() {
            fillCustomerDetails($(this).val());
        });

        // Remaining amount calculation
        ['#total_amount', '#discount_percent', '#advance_payment'].forEach(id => {
            $(id).on('input', function(){
                let total = parseFloat($('#total_amount').val()) || 0;
                let discount = parseFloat($('#discount_percent').val()) || 0;
                let advance = parseFloat($('#advance_payment').val()) || 0;
                let discounted = total - (total * discount / 100);
                $('#remaining_amount').val((discounted - advance).toFixed(2));
            });
        });

        // AJAX form submit
        $('#bookingForm').submit(function(e){
            e.preventDefault();
            $('#signatureInput').val(canvas.toDataURL('image/png'));
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('admin.booking.update', $booking->id) }}",
                type: 'POST',
                data: formData,
                success: function(response){
                    $('#alertMessage').html(`<div class="alert alert-success">Booking updated successfully!</div>`);
                },
                error: function(xhr){
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function(key, value){
                        errorHtml += `<li>${value[0]}</li>`;
                    });
                    errorHtml += '</ul></div>';
                    $('#alertMessage').html(errorHtml);
                }
            });
        });
    });
</script>
@endsection
