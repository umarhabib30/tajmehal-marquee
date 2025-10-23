@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>{{ $heading }}</h1>

    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @csrf

        <!-- Customer -->
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select id="customer_id" name="customer_id" class="form-control">
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Auto-filled Customer Info -->
        <div class="form-group">
            <label>Phone</label>
            <input type="text" id="customer_phone" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" id="customer_email" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea id="customer_address" class="form-control" readonly></textarea>
        </div>

        <!-- Event Type -->
        <div class="form-group">
            <label for="event_type">Event Type</label>
            <select name="event_type" id="event_type" class="form-control">
                <option value="">Select Event</option>
                <option value="Barat">Barat</option>
                <option value="Waliema">Waliema</option>
                <option value="Get Together">Get Together</option>
                <option value="Mehndi">Mehndi</option>
                <option value="Meeting">Meeting</option>
            </select>
        </div>

        <!-- Guest Count -->
        <div class="form-group">
            <label for="guests_count">Guest Count</label>
            <input type="number" name="guests_count" id="guests_count" class="form-control">
        </div>

        <!-- Hall Name -->
        <div class="form-group">
            <label for="hall_name">Hall Name</label>
            <select name="hall_name" id="hall_name" class="form-control">
                <option value="">Select Hall</option>
                <option value="Hall 1">Hall 1</option>
                <option value="Hall 2">Hall 2</option>
                <option value="Hall 3">Hall 3</option>
                <option value="Full Hall">Full Hall</option>
            </select>
        </div>

        <!-- Booking Date -->
        <div class="form-group">
            <label for="booking_date">Booking Date</label>
            <input type="date" name="booking_date" id="booking_date" class="form-control">
        </div>

        <!-- Time Slot -->
        <div class="form-group">
            <label for="time_slot">Time Slot</label>
            <select name="time_slot" id="time_slot" class="form-control">
                <option value="">Select Time Slot</option>
                <option value="Lunch">Lunch (1 PM - 4 PM)</option>
                <option value="Dinner">Dinner (7 PM - 10 PM)</option>
            </select>
        </div>

        <!-- Start & End Time -->
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" name="end_time" id="end_time" class="form-control" readonly>
        </div>

        <!-- Dish Package -->
        <div class="form-group">
            <label for="dish_package_id">Menu (Dish Package)</label>
            <select name="dish_package_id" id="dish_package_id" class="form-control">
                <option value="">Select Package</option>
                @foreach($dishPackages as $package)
                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Dishes Left/Right Panel -->
        <div class="row mt-3">
            <div class="col-md-7">
                <h5>Selected Dishes in Package</h5>
                <ul id="selected_dishes" class="list-group">
                    <!-- AJAX will fill this -->
                </ul>
            </div>
            <div class="col-md-5">
                <h5>All Dishes</h5>
                <ul id="all_dishes" class="list-group">
                    @foreach($dishes as $dish)
                        <li class="list-group-item" data-dish-id="{{ $dish->id }}">
                            {{ $dish->name }}
                            <button type="button" class="btn btn-sm btn-primary float-right add-dish">Add</button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Decorations -->
        <div class="form-group mt-3">
            <label>Decoration</label>
            <div class="form-check">
                <input type="checkbox" name="decoration[]" value="Floral Decor" class="form-check-input deco"> Floral Decor
            </div>
            <div class="form-check">
                <input type="checkbox" name="decoration[]" value="DJ" class="form-check-input deco"> DJ
            </div>
            <div class="form-check">
                <input type="checkbox" name="decoration[]" value="Bridal Room" class="form-check-input deco"> Bridal Room
            </div>
            <div class="form-check">
                <input type="checkbox" name="decoration[]" value="Entry" class="form-check-input deco"> Entry
            </div>
        </div>

        <!-- Decoration Amount -->
        <div class="form-group">
            <label>Decoration Amount</label>
            <input type="number" step="0.01" name="decoration_amount" id="decoration_amount" class="form-control">
        </div>

        <!-- Price / Tax / Total / Remaining -->
        <div class="form-group">
            <label>Price Per Head</label>
            <input type="number" step="0.01" name="price_per_head" id="price_per_head" class="form-control">
        </div>
        <div class="form-group">
            <label>Tax Amount</label>
            <input type="number" step="0.01" name="tax" id="tax" class="form-control">
        </div>
        <div class="form-group">
            <label>Total Amount</label>
            <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label>Advance Payment</label>
            <input type="number" step="0.01" name="advance_payment" id="advance_payment" class="form-control">
        </div>
        <div class="form-group">
            <label>Remaining Amount</label>
            <input type="number" step="0.01" name="remaining_amount" id="remaining_amount" class="form-control" readonly>
        </div>

        <!-- Customer & Manager Signature -->
        <div class="form-group">
            <label>Customer Signature</label>
            <input type="text" name="customer_signature" class="form-control">
        </div>
        <div class="form-group">
            <label>Manager Signature</label>
            <input type="text" name="manager_signature" class="form-control">
        </div>

        <!-- Notes -->
        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Save Booking</button>
    </form>
</div>

@section('scripts')
<script>
$(document).ready(function() {

    // Auto-fill customer info
    $('#customer_id').change(function() {
        var customerId = $(this).val();
        if(customerId) {
            $.get('/admin/bookings/ajax/customer/' + customerId, function(data) {
                $('#customer_phone').val(data.phone);
                $('#customer_email').val(data.email);
                $('#customer_address').val(data.address);
            });
        } else {
            $('#customer_phone').val('');
            $('#customer_email').val('');
            $('#customer_address').val('');
        }
    });

    // Auto-fill start/end time based on slot
    $('#time_slot').change(function() {
        var slot = $(this).val();
        if(slot == 'Lunch') {
            $('#start_time').val('13:00');
            $('#end_time').val('16:00');
        } else if(slot == 'Dinner') {
            $('#start_time').val('19:00');
            $('#end_time').val('22:00');
        } else {
            $('#start_time').val('');
            $('#end_time').val('');
        }
    });

    // Load package dishes via AJAX
    $('#dish_package_id').change(function() {
        var packageId = $(this).val();
        if(packageId) {
            $.get('/admin/bookings/ajax/package-dishes/' + packageId, function(dishes) {
                var html = '';
                $.each(dishes, function(i, dish){
                    html += '<li class="list-group-item">'+dish.name+'</li>';
                });
                $('#selected_dishes').html(html);
            });
        } else {
            $('#selected_dishes').html('');
        }
    });

    // Add dish from all_dishes to selected (optional for manager)
    $(document).on('click', '.add-dish', function() {
        var li = $(this).closest('li').clone();
        li.find('button').remove();
        $('#selected_dishes').append(li);
        calculateTotal();
    });

    // Amount calculations
    function calculateTotal() {
        var guests = parseFloat($('#guests_count').val()) || 0;
        var price = parseFloat($('#price_per_head').val()) || 0;
        var tax = parseFloat($('#tax').val()) || 0;
        var deco = parseFloat($('#decoration_amount').val()) || 0;
        var advance = parseFloat($('#advance_payment').val()) || 0;

        var total = (guests * price) + tax + deco;
        var remaining = total - advance;

        $('#total_amount').val(total.toFixed(2));
        $('#remaining_amount').val(remaining.toFixed(2));
    }

    // Trigger calculation on relevant inputs
    $('#guests_count, #price_per_head, #tax, #decoration_amount, #advance_payment').on('input', calculateTotal);

});
</script>
@endsection
