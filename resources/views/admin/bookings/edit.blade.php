@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ $heading }}</h3>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>

    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" id="bookingForm">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <!-- Customer Selection -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Select Customer</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Select Customer</option>
                    @foreach ($customers as $cust)
                        <option value="{{ $cust->id }}" {{ $booking->customer_id == $cust->id ? 'selected' : '' }}>
                            {{ $cust->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Customer Info -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Phone</label>
                <input type="text" id="customer_phone" class="form-control" value="{{ $booking->customer->phone ?? '' }}" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="text" id="customer_email" class="form-control" value="{{ $booking->customer->email ?? '' }}" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Address</label>
                <input type="text" id="customer_address" class="form-control" value="{{ $booking->customer->address ?? '' }}" readonly>
            </div>

            <!-- Event & Hall -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Event Type</label>
                <select name="event_type" class="form-control" required>
                    <option value="">Select Event Type</option>
                    @foreach ($eventTypes as $event)
                        <option value="{{ $event }}" {{ $booking->event_type == $event ? 'selected' : '' }}>
                            {{ $event }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Hall Name</label>
                <select name="hall_name" class="form-control" required>
                    <option value="">Select Hall</option>
                    @foreach ($halls as $hall)
                        <option value="{{ $hall }}" {{ $booking->hall_name == $hall ? 'selected' : '' }}>
                            {{ $hall }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date & Time -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Booking Date</label>
                <input type="date" name="booking_date" class="form-control" value="{{ $booking->booking_date->format('Y-m-d') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Time Slot</label>
                <select name="time_slot" id="time_slot" class="form-control" required>
                    <option value="">Select Time Slot</option>
                    @foreach ($timeSlots as $slot)
                        <option value="{{ $slot }}" {{ $booking->time_slot == $slot ? 'selected' : '' }}>
                            {{ $slot }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Start Time</label>
                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ $booking->start_time->format('H:i') }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">End Time</label>
                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ $booking->end_time->format('H:i') }}" required>
            </div>

            <!-- Menu -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Menu</label>
                <select name="menu[]" id="menu" class="form-control" multiple>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu }}" {{ in_array($menu, $booking->menu ?? []) ? 'selected' : '' }}>
                            {{ $menu }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Decorations + Extra Amount -->
            <div class="col-12">
                <label class="form-label fw-semibold">Decorations</label>
                <div class="d-flex flex-wrap align-items-start gap-2 border rounded p-3 bg-light">
                    <div class="d-flex flex-wrap gap-2 flex-grow-1">
                        @foreach ($decorations as $dec)
                            <div class="form-check small" style="min-width: 110px;">
                                <input class="form-check-input me-1" type="checkbox" name="decoration[]"
                                       value="{{ $dec }}" id="dec-{{ $loop->index }}"
                                       {{ in_array($dec, $booking->decoration ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="dec-{{ $loop->index }}">{{ $dec }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="ms-auto" style="min-width: 200px;">
                        <label class="form-label fw-semibold mb-1">Additional Charges (₨)</label>
                        <input type="number" id="extra_amount" name="extra_amount" class="form-control" value="{{ old('extra_amount', $booking->extra_amount ?? 0) }}">
                    </div>
                </div>
            </div>

            <!-- Guests & Prices -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">Guests Count</label>
                <input type="number" name="guests_count" id="guests_count" class="form-control" value="{{ $booking->guests_count }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Per Head Price (₨)</label>
                <input type="number" name="per_head_price" id="per_head_price" class="form-control" value="{{ $booking->per_head_price }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tax (₨)</label>
                <input type="number" name="tax" id="tax" class="form-control" value="{{ $booking->tax ?? 0 }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Total Amount (₨)</label>
                <input type="text" name="total_amount" id="total_amount" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Advance Payment (₨)</label>
                <input type="number" name="advance_payment" id="advance_payment" class="form-control" value="{{ $booking->advance_payment }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Remaining Amount (₨)</label>
                <input type="text" name="remaining_amount" id="remaining_amount" class="form-control" readonly>
            </div>

            <!-- Payment Method -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method }}" {{ $booking->payment_method == $method ? 'selected' : '' }}>
                            {{ $method }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Signatures -->
            <div class="row mt-3">
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <label class="form-label fw-semibold">Customer Signature</label>
                    <canvas id="customerPad" class="border rounded bg-white w-100 shadow-sm" height="100"></canvas>
                    <input type="hidden" name="customer_signature" id="customer_signature" value="{{ $booking->customer_signature }}">
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="clearPad('customerPad')">Clear</button>
                </div>

                <div class="col-md-6 d-flex flex-column align-items-center">
                    <label class="form-label fw-semibold">Manager Signature</label>
                    <canvas id="managerPad" class="border rounded bg-white w-100 shadow-sm" height="100"></canvas>
                    <input type="hidden" name="manager_signature" id="manager_signature" value="{{ $booking->manager_signature }}">
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="clearPad('managerPad')">Clear</button>
                </div>
            </div>

            <!-- Notes -->
            <div class="col-md-12 mt-3">
                <label class="form-label fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ $booking->notes }}</textarea>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save"></i> Update Booking
            </button>
        </div>
    </form>
</div>

<script>
    // === Time Slot Logic ===
    document.getElementById('time_slot').addEventListener('change', function() {
        const slot = this.value.toLowerCase();
        const start = document.getElementById('start_time');
        const end = document.getElementById('end_time');
        if (slot.includes('lunch')) {
            start.value = '13:00';
            end.value = '16:00';
        } else if (slot.includes('dinner')) {
            start.value = '19:00';
            end.value = '22:00';
        }
    });

    // === Amount Calculation (Guests + Per Head + Tax + Menu + Extra) ===
    const guestInput = document.getElementById('guests_count');
    const priceInput = document.getElementById('per_head_price');
    const taxInput = document.getElementById('tax');
    const extraInput = document.getElementById('extra_amount');
    const advanceInput = document.getElementById('advance_payment');
    const menuSelect = document.getElementById('menu');
    const totalField = document.getElementById('total_amount');
    const remainingField = document.getElementById('remaining_amount');

    function calculateAmounts() {
        const guests = parseFloat(guestInput.value) || 0;
        const perHead = parseFloat(priceInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;
        const extra = parseFloat(extraInput.value) || 0;

        // Menu cost per selected menu (example: 500 per menu)
        let menuTotal = 0;
        Array.from(menuSelect.selectedOptions).forEach(opt => menuTotal += 500);

        const total = (guests * perHead) + tax + extra + menuTotal;
        const advance = parseFloat(advanceInput.value) || 0;
        const remaining = total - advance;

        totalField.value = total.toFixed(2);
        remainingField.value = remaining.toFixed(2);
    }

    [guestInput, priceInput, taxInput, extraInput, advanceInput, menuSelect].forEach(el => {
        el.addEventListener('input', calculateAmounts);
        el.addEventListener('change', calculateAmounts);
    });
    calculateAmounts();

    // === Signature Pads ===
    function initPad(canvasId, inputId) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        let drawing = false;
        canvas.addEventListener('mousedown', () => drawing = true);
        canvas.addEventListener('mouseup', () => {
            drawing = false;
            document.getElementById(inputId).value = canvas.toDataURL();
            ctx.beginPath();
        });
        canvas.addEventListener('mousemove', e => {
            if (!drawing) return;
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#000';
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
            document.getElementById(inputId).value = canvas.toDataURL();
        });
    }
    initPad('customerPad', 'customer_signature');
    initPad('managerPad', 'manager_signature');

    function loadSignature(canvasId, base64) {
        if (!base64) return;
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.onload = () => ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        img.src = base64;
    }
    loadSignature('customerPad', '{{ $booking->customer_signature }}');
    loadSignature('managerPad', '{{ $booking->manager_signature }}');

    function clearPad(canvasId) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById(canvasId.replace('Pad', '_signature')).value = '';
    }

    // === Fetch Customer Info ===
    document.getElementById('customer_id').addEventListener('change', function() {
        const id = this.value;
        if (!id) return;
        fetch(`/admin/get-customer/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('customer_phone').value = data.phone || '';
                document.getElementById('customer_email').value = data.email || '';
                document.getElementById('customer_address').value = data.address || '';
            });
    });
</script>
@endsection
