@extends('layouts.admin')
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Increase height of the Select2 input */
        .select2-container--default .select2-selection--single {
            height: 36px !important;
            /* change value to your preference */
            padding: 8px 12px !important;
            display: flex;
            align-items: center;
            border: 1px solid #ced4da;
            border-radius: 2px;
        }

        /* Adjust text alignment inside the box */
        .select2-container--default .select2-selection__rendered {
            line-height: 28px !important;
            font-size: 16px;
        }

        /* Adjust dropdown arrow alignment */
        .select2-container--default .select2-selection__arrow {
            height: 46px !important;
            right: 10px;
        }

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
            <h3 class="mb-4">Add New Booking</h3>
        </div>
        <div class="card-body">

            <form action="{{ route('admin.booking.store') }}" method="POST" id="bookingForm">
                @csrf
                <div class="row g-3">

                    {{-- Customer --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control select2">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $cust)
                                <option value="{{ $cust->id }}" data-phone="{{ $cust->phone }}"
                                    data-idcard="{{ $cust->idcardnumber }}" data-address="{{ $cust->address }}"
                                    @if (!empty($customer) && $customer->id == $cust->id) selected @endif>
                                    {{ $cust->name }} ({{ $cust->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="customer_phone" readonly class="form-control"
                            value="{{ $customer?->phone ?? '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">ID-Card #</label>
                        <input type="text" name="customer_idcard" readonly class="form-control"
                            value="{{ $customer?->idcardnumber ?? '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="customer_address" readonly class="form-control"
                            value="{{ $customer?->address ?? '' }}">
                    </div>


                    {{-- Dish Package --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Select Dish Package</label>
                        <select name="package_id" id="package_id" class="form-control">
                            <option value="">Select Package</option>
                            @foreach ($dishPackages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                            @endforeach
                        </select>
                    </div>

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

                    {{-- Dishes Box --}}
                    <div class="col-md-12">
                        <div id="package_dishes_box" class="p-3 border rounded bg-light">
                            <div class="text-muted">Select a package to view its dishes...</div>
                        </div>
                        <input type="hidden" name="dishes" id="dishes_input">
                    </div>

                    {{-- Event Type --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Event Type</label>
                        <select name="event_type" class="form-control">
                            <option value="">Select Event Type</option>
                            <option value="Barat">Barat</option>
                            <option value="Walime">Walime</option>
                            <option value="Mehndi">Mehndi</option>
                            <option value="Meeting">Meeting</option>
                            <option value="Get Together">Get Together</option>
                        </select>
                    </div>

                    {{-- Hall --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Select Hall</label>
                        <select name="hall_name" class="form-control">
                            <option value="">Select Hall</option>
                            <option value="Hall 1">Hall 1</option>
                            <option value="Hall 2">Hall 2</option>
                            <option value="Hall 3">Hall 3</option>
                            <option value="Full Hall">Full Hall</option>
                        </select>
                    </div>

                    {{-- Event Date --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Event Date</label>
                        <input type="date" name="event_date" class="form-control">
                    </div>

                    {{-- Time Slot --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Time Slot</label>
                        <select name="time_slot" id="time_slot" class="form-control">
                            <option value="">Select Time Slot</option>
                            <option value="Lunch">Lunch (01:00 PM - 04:00 PM)</option>
                            <option value="Dinner">Dinner (07:00 PM - 10:00 PM)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control">
                    </div>

                    {{-- Guests --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Guests Count</label>
                        <input type="number" name="guests_count" id="guests_count" class="form-control"
                            min="1">
                    </div>

                    {{-- Per Head --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Per Head Price (₨)</label>
                        <input type="number" name="per_head_price" id="per_head_price" class="form-control"
                            min="0">
                    </div>

                    {{-- Advance --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Advance Payment (₨)</label>
                        <input type="number" name="advance_payment" id="advance_payment" class="form-control"
                            min="0">
                    </div>

                    {{-- Decorations --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold d-block">Decorations</label>
                        @php
                            $decorations = ['Bridal Groom Entry', 'DJ', 'Floral Decore'];
                        @endphp
                        @foreach ($decorations as $dec)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="decorations[]"
                                    value="{{ $dec }}">
                                <label class="form-check-label">{{ $dec }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Decore Price</label>
                        <input type="number" name="decore_price" class="form-control" min="0">
                    </div>

                    {{-- Total Amount --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Total Amount (₨)</label>
                        <input type="text" id="total_amount" name="total_amount" class="form-control bg-light"
                            readonly>
                    </div>

                    {{-- Tax --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tax Amount (₨)</label>
                        <input type="number" name="tax_amount" id="tax_percent" class="form-control" min="0">
                    </div>

                    {{-- Payment --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="">Select Payment Method</option>
                            <option value="Cash">Cash</option>
                            <option value="Online Payment">Online Payment</option>
                        </select>
                    </div>

                    {{-- Remaining --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Remaining Amount (₨)</label>
                        <input type="text" id="remaining_amount" name="remaining_amount"
                            class="form-control bg-light" readonly>
                    </div>

                    {{-- Signatures --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Customer Signature</label>
                        <canvas id="customerPad" class="border w-100 rounded bg-white" height="130"></canvas>
                        <input type="hidden" name="customer_signature" id="customer_signature">
                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                            onclick="clearPad(customerPad)">
                            Clear
                        </button>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Manager Signature</label>
                        <canvas id="managerPad" class="border w-100 rounded bg-white" height="130"></canvas>
                        <input type="hidden" name="manager_signature" id="manager_signature">
                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                            onclick="clearPad(managerPad)">
                            Clear
                        </button>
                    </div>

                    {{-- Notes --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Write additional booking details..."></textarea>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-save me-1"></i> Save Booking
                        </button>
                    </div>
                </div>
            </form>

        </div>

    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customer_id').select2({
                placeholder: "Select Customer",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <script>
        // ========================= TOASTR VALIDATION (Enhanced) =========================
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const showError = (msg, el) => {
                toastr.error(msg, 'Validation Error', {
                    timeOut: 2500
                });
                if (el) {
                    el.classList.add('is-invalid');
                    el.focus();
                }
            };

            // Clear all previous invalid highlights
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Get all relevant inputs
            const customerEl = document.getElementById('customer_id');
            const pkgEl = document.getElementById('package_id');
            const eventTypeEl = document.querySelector('select[name="event_type"]');
            const hallEl = document.querySelector('select[name="hall_name"]');
            const eventDateEl = document.querySelector('input[name="event_date"]');
            const timeSlotEl = document.getElementById('time_slot');
            const guestsEl = document.getElementById('guests_count');
            const perHeadEl = document.getElementById('per_head_price');
            const advanceEl = document.getElementById('advance_payment');
            const totalEl = document.getElementById('total_amount');
            const paymentEl = document.querySelector('select[name="payment_method"]');
            const customerSigEl = document.getElementById('customer_signature');
            const managerSigEl = document.getElementById('manager_signature');

            // Get numeric values safely
            const guests = parseFloat(guestsEl.value) || 0;
            const perHead = parseFloat(perHeadEl.value) || 0;
            const total = parseFloat(totalEl.value) || 0;
            const advance = parseFloat(advanceEl.value) || 0;

            // === VALIDATION RULES ===
            if (!customerEl.value.trim()) return showError('Please select a customer.', customerEl);
            if (!pkgEl.value.trim()) return showError('Please select a dish package.', pkgEl);
            if (!eventTypeEl.value.trim()) return showError('Please select an event type.', eventTypeEl);
            if (!hallEl.value.trim()) return showError('Please select a hall.', hallEl);
            if (!eventDateEl.value.trim()) return showError('Please select an event date.', eventDateEl);
            if (!timeSlotEl.value.trim()) return showError('Please select a time slot.', timeSlotEl);
            if (!guests || guests <= 0) return showError('Please enter a valid guest count.', guestsEl);
            if (!perHead || perHead <= 0) return showError('Please enter a per-head price.', perHeadEl);
            if (!paymentEl.value.trim()) return showError('Please select a payment method.', paymentEl);
            if (total <= 0) return showError('Total amount cannot be zero. Please check guests or prices.',
                totalEl);
            if (advance < 0) return showError('Advance payment cannot be negative.', advanceEl);
            if (advance > total)
                return showError('Advance payment cannot exceed total amount.', advanceEl);
            if (!customerSigEl.value.trim()) return showError('Please capture the customer signature.',
                customerSigEl);
            if (!managerSigEl.value.trim()) return showError('Please capture the manager signature.', managerSigEl);

            // ✅ If validation passes
            toastr.success('All fields validated successfully! Submitting...', 'Success', {
                timeOut: 1000
            });
            setTimeout(() => e.target.submit(), 1000);
        });

        // ========================= REMOVE HIGHLIGHT ON INPUT =========================
        document.querySelectorAll('input, select, textarea').forEach(el => {
            el.addEventListener('input', () => el.classList.remove('is-invalid'));
            el.addEventListener('change', () => el.classList.remove('is-invalid'));
        });
    </script>

    {{-- ========================= JS SCRIPT ========================= --}}
    <script>
        document.getElementById('time_slot').addEventListener('change', function() {
            const startTime = document.getElementById('start_time');
            const endTime = document.getElementById('end_time');

            if (this.value === 'Lunch') {
                startTime.value = '13:00'; // 1:00 PM
                endTime.value = '16:00'; // 4:00 PM
            } else if (this.value === 'Dinner') {
                startTime.value = '19:00'; // 7:00 PM
                endTime.value = '22:00'; // 10:00 PM
            } else {
                startTime.value = '';
                endTime.value = '';
            }
        });

        // ========== Auto-fill customer details ==========
        // Works correctly with Select2
        $('#customer_id').on('select2:select', function(e) {
            const selected = e.params.data.element;

            const phone = $(selected).data('phone') || '';
            const idcard = $(selected).data('idcard') || '';
            const address = $(selected).data('address') || '';

            $('input[name="customer_phone"]').val(phone);
            $('input[name="customer_idcard"]').val(idcard);
            $('input[name="customer_address"]').val(address);
        });

        // Optional: clear fields if user clears the Select2 selection
        $('#customer_id').on('select2:clear', function() {
            $('input[name="customer_phone"], input[name="customer_idcard"], input[name="customer_address"]').val(
            '');
        });


        const guestInput = document.getElementById('guests_count');
        const priceInput = document.getElementById('per_head_price');
        const taxInput = document.getElementById('tax_percent');
        const advanceInput = document.getElementById('advance_payment');
        const decoreInput = document.querySelector('input[name="decore_price"]'); // ✅ new
        const totalField = document.getElementById('total_amount');
        const remainingField = document.getElementById('remaining_amount');


        function calculateAmounts() {
            const guests = parseFloat(guestInput.value) || 0;
            const perHead = parseFloat(priceInput.value) || 0;
            const tax = parseFloat(taxInput.value) || 0; // flat tax amount
            const advance = parseFloat(advanceInput.value) || 0;
            const decore = parseFloat(decoreInput.value) || 0; // ✅ new

            // Base total
            let total = (guests * perHead) + tax + decore;

            // Remaining after advance
            const remaining = total - advance;

            totalField.value = total.toFixed(2);
            remainingField.value = remaining.toFixed(2);
        }



        [guestInput, priceInput, taxInput, advanceInput, decoreInput].forEach(el => {
            el.addEventListener('input', calculateAmounts);
        });

        // ================= Signature Pads =================
        function initPad(canvasId, inputId) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            let drawing = false;

            // Adjust canvas for high-DPI and responsive scaling
            function resizeCanvas() {
                const rect = canvas.getBoundingClientRect();
                const scale = window.devicePixelRatio || 1;
                canvas.width = rect.width * scale;
                canvas.height = rect.height * scale;
                ctx.scale(scale, scale);
            }

            // Call resize once initially
            resizeCanvas();

            // Recalculate on window resize
            window.addEventListener('resize', resizeCanvas);

            // Get accurate cursor position relative to canvas size
            function getPosition(e) {
                const rect = canvas.getBoundingClientRect();
                return {
                    x: (e.clientX - rect.left),
                    y: (e.clientY - rect.top)
                };
            }

            canvas.addEventListener('mousedown', e => {
                drawing = true;
                const pos = getPosition(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            });

            canvas.addEventListener('mouseup', () => {
                drawing = false;
                ctx.beginPath(); // reset path
                document.getElementById(inputId).value = canvas.toDataURL();
            });

            canvas.addEventListener('mousemove', e => {
                if (!drawing) return;
                const pos = getPosition(e);
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000';
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            });

            // Touch support (for mobile/tablet)
            canvas.addEventListener('touchstart', e => {
                e.preventDefault();
                const touch = e.touches[0];
                const pos = getPosition(touch);
                drawing = true;
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            });

            canvas.addEventListener('touchmove', e => {
                e.preventDefault();
                if (!drawing) return;
                const touch = e.touches[0];
                const pos = getPosition(touch);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            });

            canvas.addEventListener('touchend', e => {
                e.preventDefault();
                drawing = false;
                ctx.beginPath();
                document.getElementById(inputId).value = canvas.toDataURL();
            });

            return canvas;
        }


        const customerPad = initPad('customerPad', 'customer_signature');
        const managerPad = initPad('managerPad', 'manager_signature');

        function clearPad(canvas) {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }


        const packages = @json($dishPackages);
        const pkgSelect = document.getElementById('package_id');
        const pkgBox = document.getElementById('package_dishes_box');
        const dishSelect = document.getElementById('dish_selector');
        const selectedBox = document.getElementById('selected_dishes');
        const dishesInput = document.getElementById('dishes_input');

        let selectedDishes = [];

        // Display dishes when a package is selected
        pkgSelect.addEventListener('change', function() {
            const pkgId = this.value;
            pkgBox.innerHTML = '';

            if (!pkgId) {
                pkgBox.innerHTML = '<div class="text-muted">Select a package to view its dishes...</div>';
                selectedDishes = [];
                updateDishesInput();
                renderSelectedDishes();
                return;
            }

            const pkg = packages.find(p => p.id == pkgId);
            if (!pkg || !pkg.dishes.length) {
                pkgBox.innerHTML = '<div class="text-danger">No dishes found for this package.</div>';
                selectedDishes = [];
                updateDishesInput();
                renderSelectedDishes();
                return;
            }

            pkgBox.innerHTML = `
            <div class="d-flex flex-wrap gap-2">
                ${pkg.dishes.map(d => `<span class="badge bg-secondary px-3 py-2">${d.name}</span>`).join('')}
            </div>
        `;

            // Load dishes into selected list
            selectedDishes = pkg.dishes.map(d => ({
                id: d.id,
                name: d.name
            }));
            renderSelectedDishes();
            updateDishesInput();
        });

        // Add new dish manually
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

        // Render selected dishes as chips
        function renderSelectedDishes() {
            const dishesBox = document.getElementById('package_dishes_box');
            dishesBox.innerHTML = '';

            if (!selectedDishes.length) {
                dishesBox.innerHTML = '<span class="text-muted">No dishes selected.</span>';
                return;
            }

            const container = document.createElement('div');
            // Increased overall spacing between chips
            container.classList.add('d-flex', 'flex-wrap', 'gap-3', 'mt-2');

            selectedDishes.forEach(dish => {
                const chip = document.createElement('div');
                chip.classList.add('dish-chip', 'd-flex', 'align-items-center', 'px-3', 'py-2', 'shadow-sm');

                chip.innerHTML = `
            <span>${dish.name}</span>
            <button type="button" class="remove-btn" title="Remove">×</button>
        `;

                chip.querySelector('button').addEventListener('click', () => {
                    selectedDishes = selectedDishes.filter(d => d.id !== dish.id);
                    renderSelectedDishes();
                    updateDishesInput();
                });

                container.appendChild(chip);
            });

            dishesBox.appendChild(container);
        }


        // Update hidden input for backend submission
        function updateDishesInput() {
            dishesInput.value = JSON.stringify(selectedDishes.map(d => d.id));
        }
    </script>
@endsection
