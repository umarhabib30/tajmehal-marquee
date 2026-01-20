@extends('layouts.admin')

@section('style')
    <style>
        .dish-chip {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease-in-out;
        }

        .dish-chip:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
        }

        .remove-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            line-height: 20px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            padding: 0;
            transition: all 0.2s ease-in-out;
        }

        .remove-btn:hover {
            background-color: #b02a37;
            transform: scale(1.1);
        }

        .is-invalid {
            border: 2px solid #dc3545 !important;
            background-color: #fff6f6 !important;
        }
    </style>
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="mb-4">Edit Booking (ID: {{ $booking->id }})</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.booking.update') }}" method="POST" id="bookingForm">
                @csrf
                <input type="hidden" name="id" value="{{ $booking->id }}" id="">
                <div class="row g-3">
                    {{-- Customer --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $cust)
                                <option value="{{ $cust->id }}" data-phone="{{ $cust->phone }}"
                                    data-idcard="{{ $cust->idcardnumber }}" data-address="{{ $cust->address }}"
                                    {{ $booking->customer_id == $cust->id ? 'selected' : '' }}>
                                    {{ $cust->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="customer_phone" value="{{ $booking->customer_phone }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">ID-Card #</label>
                        <input type="text" name="customer_idcard" value="{{ $booking->customer_idcard }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="customer_address" value="{{ $booking->customer_address }}"
                            class="form-control">
                    </div>

                    {{-- Package --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Select Dish Package</label>
                        <select name="package_id" id="package_id" class="form-control">
                            <option value="">Select Package</option>
                            @foreach ($dishPackages as $pkg)
                                <option value="{{ $pkg->id }}"
                                    {{ $booking->package_id == $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dishes --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Add/Remove Dishes</label>
                        <select id="dish_selector" class="form-control">
                            <option value="">Select Dish to Add</option>
                            @foreach ($dishes as $dish)
                                <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                            @endforeach
                        </select>
                        <div id="selected_dishes" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>

                    <div class="col-md-12">
                        <div id="package_dishes_box" class="p-3 border rounded bg-light"></div>
                        <input type="hidden" name="dishes" id="dishes_input" value='@json($booking->dishes)'>
                    </div>

                    {{-- Event Details --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Event Type</label>
                        <select name="event_type" class="form-control">
                            @foreach (['Barat', 'Walime', 'Mehndi', 'Meeting', 'Get Together'] as $event)
                                <option value="{{ $event }}"
                                    {{ $booking->event_type == $event ? 'selected' : '' }}>
                                    {{ $event }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Hall</label>
                        <select name="hall_name" class="form-control">
                            @foreach (['Hall 1', 'Hall 2', 'Hall 3', 'Full Hall'] as $hall)
                                <option value="{{ $hall }}" {{ $booking->hall_name == $hall ? 'selected' : '' }}>
                                    {{ $hall }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Event Date</label>
                        <input type="date" name="event_date" class="form-control" value="{{ $booking->event_date }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Time Slot</label>
                        <select name="time_slot" id="time_slot" class="form-control">
                            <option value="">Select Time Slot</option>
                            <option value="Lunch" {{ $booking->time_slot == 'Lunch' ? 'selected' : '' }}>Lunch</option>
                            <option value="Dinner" {{ $booking->time_slot == 'Dinner' ? 'selected' : '' }}>Dinner</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control"
                            value="{{ $booking->start_time }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control"
                            value="{{ $booking->end_time }}">
                    </div>

                    {{-- Financials --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Guests Count</label>
                        <input type="number" name="guests_count" id="guests_count"
                            value="{{ $booking->guests_count }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Per Head Price</label>
                        <input type="number" name="per_head_price" id="per_head_price"
                            value="{{ $booking->per_head_price }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Advance Payment</label>
                        <input type="number" name="advance_payment" id="advance_payment"
                            value="{{ $booking->advance_payment }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold d-block">Decorations</label>
                        @php $decorations = ['Bridal Groom Entry','DJ','Floral Decore']; @endphp
                        @foreach ($decorations as $dec)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="decorations[]"
                                    value="{{ $dec }}"
                                    {{ in_array($dec, $booking->decorations ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $dec }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Decore Price</label>
                        <input type="number" name="decore_price" class="form-control"
                            value="{{ $booking->decore_price }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tax Amount</label>
                        <input type="number" name="tax_amount" id="tax_percent" value="{{ $booking->tax_amount }}"
                            class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Total Amount</label>
                        <input type="text" id="total_amount" name="total_amount"
                            value="{{ $booking->total_amount }}" class="form-control bg-light" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="Cash" {{ $booking->payment_method == 'Cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="Online Payment"
                                {{ $booking->payment_method == 'Online Payment' ? 'selected' : '' }}>Online Payment
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Remaining Amount</label>
                        <input type="text" id="remaining_amount" name="remaining_amount"
                            class="form-control bg-light" value="{{ $booking->remaining_amount }}" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $booking->notes }}</textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-save me-1"></i> Update
                            Booking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // ===================== Auto-fill customer details =====================
        document.getElementById('customer_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.querySelector('input[name="customer_phone"]').value = selected.getAttribute('data-phone') ||
                '';
            document.querySelector('input[name="customer_idcard"]').value = selected.getAttribute('data-idcard') ||
                '';
            document.querySelector('input[name="customer_address"]').value = selected.getAttribute(
                'data-address') || '';
        });

        // ===================== Time slot autofill =====================
        document.getElementById('time_slot').addEventListener('change', function() {
            const startTime = document.getElementById('start_time');
            const endTime = document.getElementById('end_time');
            if (this.value === 'Lunch') {
                startTime.value = '13:00';
                endTime.value = '16:00';
            } else if (this.value === 'Dinner') {
                startTime.value = '19:00';
                endTime.value = '22:00';
            } else {
                startTime.value = '';
                endTime.value = '';
            }
        });

        // ===================== Amount Calculations (Edit-safe) =====================
        const guestInput = document.getElementById('guests_count');
        const priceInput = document.getElementById('per_head_price');
        const taxInput = document.getElementById('tax_percent');
        const advanceInput = document.getElementById('advance_payment');
        const decoreInput = document.querySelector('input[name="decore_price"]');
        const totalField = document.getElementById('total_amount');
        const remainingField = document.getElementById('remaining_amount');

        // Snapshot of original booking monetary state (from server)
        const ORIGINAL = {
            total: parseFloat(@json((float) ($booking->total_amount ?? 0))) || 0,
            remaining: parseFloat(@json((float) ($booking->remaining_amount ?? 0))) || 0,
            advance: parseFloat(@json((float) ($booking->advance_payment ?? 0))) || 0,
        };
        // Everything paid up to now (including original advance + any other payments)
        ORIGINAL.paid = Math.max(0, ORIGINAL.total - ORIGINAL.remaining);

        function number(val) {
            const n = parseFloat(val);
            return Number.isFinite(n) ? n : 0;
        }

        function calculateAmounts() {
            const guests = number(guestInput.value);
            const perHead = number(priceInput.value);
            const tax = number(taxInput.value); // flat tax amount
            const decore = number(decoreInput.value); // flat decore amount
            const advNow = number(advanceInput.value); // current advance field

            // New total from editable inputs
            const newTotal = (guests * perHead) + tax + decore;

            // If user changed "Advance Payment", treat it as editing the *advance figure*,
            // not an extra payment. So adjust paid_so_far by the delta vs original advance:
            const deltaAdvance = advNow - ORIGINAL.advance;
            const paidSoFar = Math.max(0, ORIGINAL.paid + deltaAdvance);

            // Remaining should subtract what has already been paid (after delta)
            let newRemaining = newTotal - paidSoFar;

            // Never go below zero; if it does, you may want to show "refund due" separately
            if (newRemaining < 0) newRemaining = 0;

            totalField.value = newTotal.toFixed(2);
            remainingField.value = newRemaining.toFixed(2);
        }

        // Recalculate on input changes
        [guestInput, priceInput, taxInput, advanceInput, decoreInput].forEach(el => {
            el.addEventListener('input', calculateAmounts);
            el.addEventListener('change', calculateAmounts);
        });

        // Initialize once the DOM is ready so fields reflect correct remaining on load
        document.addEventListener('DOMContentLoaded', calculateAmounts);

        // Ensure correct values get posted even if user submits without touching fields
        document.getElementById('bookingForm').addEventListener('submit', function() {
            calculateAmounts();
        });
        // ===================== Dishes =====================
        const packages = @json($dishPackages);
        const pkgSelect = document.getElementById('package_id');
        const pkgBox = document.getElementById('package_dishes_box');
        const dishSelect = document.getElementById('dish_selector');
        const dishesInput = document.getElementById('dishes_input');

        let selectedDishes = [];
        const allDishes = @json($dishes);

        function renderSelectedDishes() {
            pkgBox.innerHTML = '';
            if (!selectedDishes.length) {
                pkgBox.innerHTML = '<span class="text-muted">No dishes selected.</span>';
                return;
            }
            const container = document.createElement('div');
            container.classList.add('d-flex', 'flex-wrap', 'gap-3', 'mt-2');
            selectedDishes.forEach(dish => {
                const chip = document.createElement('div');
                chip.classList.add('dish-chip', 'd-flex', 'align-items-center', 'px-3', 'py-2', 'shadow-sm');
                chip.innerHTML = `<span>${dish.name}</span>
                <button type="button" class="remove-btn">×</button>`;
                chip.querySelector('button').addEventListener('click', () => {
                    selectedDishes = selectedDishes.filter(d => d.id !== dish.id);
                    renderSelectedDishes();
                    updateDishesInput();
                });
                container.appendChild(chip);
            });
            pkgBox.appendChild(container);
        }

        function updateDishesInput() {
            dishesInput.value = JSON.stringify(selectedDishes.map(d => d.id));
        }

        pkgSelect.addEventListener('change', function() {
            const pkgId = this.value;
            if (!pkgId) {
                pkgBox.innerHTML = '<div class="text-muted">Select a package...</div>';
                selectedDishes = [];
                updateDishesInput();
                renderSelectedDishes();
                return;
            }
            const pkg = packages.find(p => p.id == pkgId);
            if (!pkg || !pkg.dishes.length) {
                pkgBox.innerHTML = '<div class="text-danger">No dishes found.</div>';
                selectedDishes = [];
                updateDishesInput();
                renderSelectedDishes();
                return;
            }
            selectedDishes = pkg.dishes.map(d => ({
                id: d.id,
                name: d.name
            }));
            renderSelectedDishes();
            updateDishesInput();
        });

        dishSelect.addEventListener('change', function() {
            const id = this.value;
            const name = this.options[this.selectedIndex].text;
            if (id && !selectedDishes.find(d => d.id == id)) {
                selectedDishes.push({
                    id,
                    name
                });
                renderSelectedDishes();
                updateDishesInput();
            }
            this.value = '';
        });

        // Load existing dishes on page load
        // Load existing dishes on page load (FIXED)
        document.addEventListener('DOMContentLoaded', function() {
            const existing = (@json($booking->dishes ?? [])).map(Number);

            selectedDishes = allDishes.filter(d =>
                existing.includes(Number(d.id))
            );

            renderSelectedDishes();
            updateDishesInput(); // ✅ VERY IMPORTANT
        });


        // ===================== Validation =====================
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const required = ['customer_id', 'package_id', 'event_type', 'hall_name', 'event_date', 'time_slot',
                'guests_count', 'per_head_price'
            ];
            for (const name of required) {
                const el = document.querySelector(`[name="${name}"]`);
                if (!el.value.trim()) {
                    e.preventDefault();
                    alert('Please fill all required fields properly.');
                    el.focus();
                    return;
                }
            }
            calculateAmounts();
        });
    </script>
@endsection
