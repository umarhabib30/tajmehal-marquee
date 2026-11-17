@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 fw-bold">{{ $heading ?? 'Booking Details' }}</h3>

        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="d-flex flex-wrap mb-4" style="gap: .5rem;">
            <a href="{{ route('admin.booking.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Bookings
            </a>
            <a href="{{ route('admin.booking.details.print', $booking->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-print mr-1"></i> Print Details
            </a>
            <a href="{{ route('admin.booking.invoice', $booking->id) }}" class="btn btn-primary">
                <i class="fas fa-file-invoice mr-1"></i> Generate Invoice
            </a>
            @modulePerm('booking', 'edit')
                <a href="{{ route('admin.booking.edit', $booking->id) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit mr-1"></i> Edit Booking
                </a>
                <a href="{{ route('admin.booking.addPaymentPage', $booking->id) }}" class="btn btn-success">
                    <i class="fas fa-plus-circle mr-1"></i> Add Payment
                </a>
            @endmodulePerm
            @modulePerm('booking', 'delete')
                <button type="button" class="btn btn-outline-danger" id="delete-booking">
                    <i class="fas fa-trash mr-1"></i> Delete Booking
                </button>
                <div id="delete-booking-error" class="alert alert-danger w-100 mb-0" role="alert" hidden></div>
            @endmodulePerm
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <p class="mb-3"><strong>Current Status:</strong>
                    <span class="badge {{ $booking->statusBadgeClass() }}"
                        style="background-color: {{ \App\Models\Booking::calendarColor($booking->status) }}; color: {{ strtolower((string) $booking->status) === 'pending' ? '#212529' : '#fff' }};">
                        {{ $booking->status ?? 'Not set' }}
                    </span>
                </p>
                @modulePerm('booking', 'edit')
                    <form id="booking-status-form" novalidate action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="row align-items-end">
                            <div class="col-md-6 mb-2">
                                <label for="booking-status">Booking Status</label>
                                <select id="booking-status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    @foreach (\App\Models\Booking::allowedStatuses() as $status)
                                        <option value="{{ $status }}" @selected(old('status', $booking->status) === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </div>
                        @if ($booking->status !== \App\Models\Booking::STATUS_CANCELLED)
                            <div class="modal fade" id="refund-modal" tabindex="-1" role="dialog" aria-labelledby="refund-modal-title" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="refund-modal-title">Cancel Booking &amp; Record Refund</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            @php
                                                $availableRefund = max(0, (int) $booking->payments->sum('amount'));
                                            @endphp
                                            <p>Amount paid and not yet refunded: <strong>Rs {{ number_format($availableRefund) }}</strong></p>
                                            <label for="refund-amount">Refund Amount (Rs)</label>
                                            <input id="refund-amount" name="refund_amount" type="number" min="0" max="{{ $availableRefund }}" step="1"
                                                value="{{ old('refund_amount', $availableRefund) }}" class="form-control @error('refund_amount') is-invalid @enderror">
                                            <small class="form-text text-muted">Enter a full or partial refund, or 0 for no refund. Confirming records the refund in payment history.</small>
                                            @error('refund_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                                            <button type="button" id="confirm-cancellation" class="btn btn-danger">Confirm Cancellation &amp; Refund</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                @endmodulePerm
            </div>
        </div>

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
                    <div class="col-md-4"><strong>Difference:</strong> ₨
                        {{ number_format($booking->discount ?? 0,) }}</div>
                    <div class="col-md-4"><strong>Total Amount:</strong> ₨
                        {{ number_format($booking->total_amount ?? 0,) }}</div>
                    <div class="col-md-4"><strong>Advance Payment:</strong> ₨
                        {{ number_format($booking->advance_payment ?? 0,) }}</div>
                    <div class="col-md-4"><strong>Remaining Amount:</strong> ₨
                        {{ number_format($booking->remaining_amount ?? 0,) }}</div>
                    <div class="col-md-4"><strong>Payment Method:</strong> {{ $booking->payment_method ?? 'N/A' }}</div>
                </div>

                @if ($booking->payments->where('amount', '<', 0)->count())
                    <div class="alert alert-info">
                        <strong>Total Refunded:</strong> Rs {{ number_format(-$booking->payments->where('amount', '<', 0)->sum('amount')) }}
                        <span class="ml-3"><strong>Net Amount Retained:</strong> Rs {{ number_format($booking->payments->sum('amount')) }}</span>
                    </div>
                @endif

                {{-- ================= PAYMENT HISTORY ================= --}}
                @php
                    $availableRefund = max(0, (int) $booking->payments->sum('amount'));
                @endphp

                <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h5 class="mb-0 text-uppercase text-secondary">Payment History</h5>
                    @modulePerm('booking', 'edit')
                        <div class="d-flex flex-wrap" style="gap: .5rem;">
                            <a href="{{ route('admin.booking.addPaymentPage', $booking->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> Add Payment
                            </a>
                            @if ($availableRefund > 0)
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#add-refund-modal">
                                    <i class="fas fa-undo"></i> Add Refund
                                </button>
                            @endif
                        </div>
                    @endmodulePerm
                </div>

                @if ($booking->payments && $booking->payments->count() > 0)
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Payment Date</th>
                                    <th>Amount (₨)</th>
                                    <th>Method</th>
                                    @modulePerm('booking', 'edit')
                                        <th style="width: 90px;">Action</th>
                                    @endmodulePerm
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($booking->payments as $index => $payment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                                        <td class="{{ $payment->amount < 0 ? 'text-danger' : 'text-success' }}">
                                            ₨ {{ number_format($payment->amount, 0) }}
                                        </td>
                                        <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                        @modulePerm('booking', 'edit')
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary edit-payment-btn"
                                                    data-id="{{ $payment->id }}"
                                                    data-amount="{{ abs((int) $payment->amount) }}"
                                                    data-date="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}"
                                                    data-method="{{ $payment->payment_method }}"
                                                    data-is-refund="{{ $payment->amount < 0 ? 1 : 0 }}"
                                                    data-url="{{ route('admin.booking.updatePayment', $payment) }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </td>
                                        @endmodulePerm
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Net Paid (after refunds):</th>
                                    @modulePerm('booking', 'edit')
                                        <th colspan="3">₨ {{ number_format($booking->payments->sum('amount'), 0) }}</th>
                                    @else
                                        <th colspan="2">₨ {{ number_format($booking->payments->sum('amount'), 0) }}</th>
                                    @endmodulePerm
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted fst-italic mb-4">No payments recorded yet.</p>
                @endif

                @modulePerm('booking', 'edit')
                    @if ($availableRefund > 0)
                        <div class="modal fade" id="add-refund-modal" tabindex="-1" role="dialog" aria-labelledby="add-refund-title" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.booking.addRefund', $booking->id) }}" method="POST" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="add-refund-title">Add Refund</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Amount paid and not yet refunded: <strong>Rs {{ number_format($availableRefund) }}</strong></p>
                                        <div class="form-group">
                                            <label for="standalone-refund-amount">Refund Amount (Rs)</label>
                                            <input id="standalone-refund-amount" type="number" name="refund_amount"
                                                min="1" max="{{ $availableRefund }}" step="1"
                                                value="{{ old('refund_amount') }}" class="form-control @error('refund_amount') is-invalid @enderror" required>
                                            @error('refund_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="refund-date">Refund Date</label>
                                            <input id="refund-date" type="date" name="refund_date" class="form-control"
                                                value="{{ old('refund_date', now()->format('Y-m-d')) }}" required>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="refund-method">Method / Note</label>
                                            <select id="refund-method" name="refund_method" class="form-control">
                                                <option value="Refund" @selected(old('refund_method', 'Refund') === 'Refund')>Refund</option>
                                                <option value="Cancellation refund" @selected(old('refund_method') === 'Cancellation refund')>Cancellation refund</option>
                                                <option value="Cash" @selected(old('refund_method') === 'Cash')>Cash</option>
                                                <option value="Online Payment" @selected(old('refund_method') === 'Online Payment')>Online Payment</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Record Refund</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="modal fade" id="edit-payment-modal" tabindex="-1" role="dialog" aria-labelledby="edit-payment-title" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="edit-payment-form" method="POST" class="modal-content">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="edit-payment-title">Edit Payment Entry</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <p id="edit-payment-type-hint" class="text-muted small"></p>
                                    <div class="form-group">
                                        <label for="edit-payment-amount">Amount (Rs)</label>
                                        <input id="edit-payment-amount" type="number" name="amount" min="1" step="1"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-payment-date">Date</label>
                                        <input id="edit-payment-date" type="date" name="payment_date" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="edit-payment-method">Method</label>
                                        <input id="edit-payment-method" type="text" name="payment_method" class="form-control" list="payment-method-options">
                                        <datalist id="payment-method-options">
                                            <option value="Cash"></option>
                                            <option value="Online Payment"></option>
                                            <option value="Refund"></option>
                                            <option value="Cancellation refund"></option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endmodulePerm


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

@section('script')
    @modulePerm('booking', 'edit')
        <script>
            $(function () {
                @if ($booking->status !== \App\Models\Booking::STATUS_CANCELLED)
                    const form = document.getElementById('booking-status-form');
                    const status = document.getElementById('booking-status');
                    const amount = document.getElementById('refund-amount');
                    let confirmed = false;
                    let submitted = false;
                    const isCancellation = () => status.value === @json(\App\Models\Booking::STATUS_CANCELLED);

                    status.addEventListener('change', function () {
                        confirmed = false;
                        if (isCancellation()) $('#refund-modal').modal('show');
                    });
                    form.addEventListener('submit', function (event) {
                        if (submitted) { event.preventDefault(); return; }
                        if (isCancellation() && !confirmed) {
                            event.preventDefault();
                            $('#refund-modal').modal('show');
                            return;
                        }
                        submitted = true;
                    });
                    document.getElementById('confirm-cancellation').addEventListener('click', function () {
                        amount.required = true;
                        if (!amount.reportValidity()) return;
                        confirmed = true;
                        form.requestSubmit();
                    });
                    $('#refund-modal').on('shown.bs.modal', function () { amount.focus(); amount.select(); });
                    @if ($errors->has('refund_amount') && !old('refund_method'))
                        $('#refund-modal').modal('show');
                    @endif
                @endif

                @if ($errors->has('refund_amount') && $availableRefund > 0)
                    $('#add-refund-modal').modal('show');
                @endif

                $(document).on('click', '.edit-payment-btn', function () {
                    const btn = $(this);
                    const isRefund = btn.data('is-refund') == 1;
                    $('#edit-payment-form').attr('action', btn.data('url'));
                    $('#edit-payment-amount').val(btn.data('amount'));
                    $('#edit-payment-date').val(btn.data('date'));
                    $('#edit-payment-method').val(btn.data('method') || (isRefund ? 'Refund' : 'Cash'));
                    $('#edit-payment-type-hint').text(isRefund
                        ? 'Editing a refund entry. Enter the refund amount as a positive number.'
                        : 'Editing a payment entry.');
                    $('#edit-payment-modal').modal('show');
                });
            });
        </script>
    @endmodulePerm

    @modulePerm('booking', 'delete')
        <script>
            document.getElementById('delete-booking').addEventListener('click', async function () {
                if (!window.confirm('Permanently delete this booking? This cannot be undone.')) return;

                const button = this;
                const error = document.getElementById('delete-booking-error');
                button.disabled = true;
                error.hidden = true;

                try {
                    const response = await fetch(@json(route('admin.booking.delete', $booking->id)), {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) }
                    });
                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Failed to delete booking.');
                    }
                    window.location.href = @json(route('admin.booking.index'));
                } catch (failure) {
                    error.textContent = failure.message || 'Request failed. Please try again.';
                    error.hidden = false;
                    button.disabled = false;
                }
            });
        </script>
    @endmodulePerm
@endsection
