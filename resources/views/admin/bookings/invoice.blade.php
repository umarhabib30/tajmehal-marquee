@extends('layouts.admin')

@section('content')
    <div class="row" id="invoice-div">
        <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header p-4">
                    <a class="pt-2 d-inline-block">
                        <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" style="height:60px;">
                        <span class="ml-2 font-weight-bold text-dark" style="font-size: 25px;">The Taj Mahal Marquee</span>
                    </a>
                    <div class="float-right">
                        <h3 class="mb-0">Invoice #{{ $booking->id }}</h3>
                        Date: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}
                    </div>
                </div>

                <div class="card-body">
                    {{-- FROM / TO DETAILS --}}
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h5 class="mb-3">From:</h5>
                            <h3 class="text-dark mb-1">The Taj Mahal Marquee</h3>
                            <div>Link Road Aqil Shah, Shahpur</div>
                            <div>Email: info@tajmahalmarquee.pk</div>
                            <div>Phone: 0300-8700443 | 0324-1111963</div>
                        </div>

                        <div class="col-sm-6">
                            <h5 class="mb-3">To:</h5>
                            <h3 class="text-dark mb-1">{{ $booking->customer->name ?? 'N/A' }}</h3>
                            <div>{{ $booking->customer->address ?? 'N/A' }}</div>
                            <div>Phone: {{ $booking->customer->phone ?? 'N/A' }}</div>
                        </div>
                    </div>

                    {{-- PAYMENT SUMMARY --}}
                    <div class="row mb-4">
                        <div class="col-lg-6 col-sm-8 ml-auto">
                            <h5 class="mb-3">Payment Summary</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Total Guest</strong></td>
                                        <td class="text-right">
                                            {{ $booking->guests_count }}
                                        </td>
                                    </tr>
                                    @if (!empty($booking->extra_guests))
                                        <tr>
                                            <td><strong>Extra Guest</strong></td>
                                            <td class="text-right">
                                                {{ number_format($booking->extra_guests) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Price Per Head</strong></td>
                                        <td class="text-right">
                                            {{ $booking->per_head_price }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Bill</strong></td>
                                        <td class="text-right">₨
                                            {{ number_format($booking->per_head_price * $booking->guests_count) }}
                                        </td>
                                    </tr>

                                    @if (!empty($booking->decore_price))
                                        <tr>
                                            <td><strong>Decoration Charges</strong></td>
                                            <td class="text-right">₨
                                                {{ number_format($booking->decore_price) }}</td>
                                        </tr>
                                    @endif

                                    @if (!empty($booking->tax_amount))
                                        <tr>
                                            <td><strong>Tax</strong></td>
                                            <td class="text-right">₨
                                                {{ number_format($booking->tax_amount) }}</td>
                                        </tr>
                                    @endif

                                    <tr class="table-primary">
                                        <td><strong>Grand Total</strong></td>
                                        <td class="text-right">₨
                                            {{ number_format($booking->total_amount) }}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Total Paid</strong></td>
                                        <td class="text-right">₨ {{ number_format($totalPaid) }}</td>
                                    </tr>

                                    <tr class="table-warning">
                                        <td><strong>Remaining</strong></td>
                                        <td class="text-right font-weight-bold">
                                            ₨ {{ number_format($booking->total_amount - $totalPaid) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- NOTES --}}
                    @if (!empty($booking->notes))
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0">
                                    <div class="card-header bg-light text-dark">
                                        <strong>Notes</strong>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0" style="white-space: pre-line;">
                                            {{ $booking->notes }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    {{-- PAYMENT HISTORY --}}
                    @if ($booking->payments->count() > 0)
                        <div class="table-responsive-sm">
                            <h5 class="mb-3">Payment History</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Amount (₨)</th>
                                        <th>Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($booking->payments as $index => $payment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}
                                            </td>
                                            <td>₨ {{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- SIGNATURES --}}
                    <div class="row mt-5 text-center">
                        <div class="col-sm-6">
                            @if ($booking->customer_signature)
                                <img src="{{ $booking->customer_signature }}" alt="Customer Signature"
                                    style="max-width:120px;">
                            @endif
                            <div class="mt-2 font-weight-semibold">Customer Signature</div>
                        </div>
                        <div class="col-sm-6">
                            @if ($booking->manager_signature)
                                <img src="{{ $booking->manager_signature }}" alt="Manager Signature"
                                    style="max-width:120px;">
                            @endif
                            <div class="mt-2 font-weight-semibold">Manager Signature</div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white text-center">
                    <button type="button" class="btn btn-success btn-sm no-print" onclick="printInvoice()">
                        <i class="fas fa-print"></i> Print Invoice
                    </button>

                    <p class="mb-0 mt-2 small text-muted">Thank you for choosing The Taj Mahal Marquee</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card mt-4 mb-4">
                <div class="card-header p-4">
                    <h4 class="mb-0">Add Extra Guests</h4>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.booking.extraGuest') }}" method="POST" id="extraGuestForm">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="extra_guests"><strong>Extra Guest Count</strong></label>
                                <input type="number" name="extra_guests" id="extra_guest_count" class="form-control"
                                    min="1" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="per_head_price"><strong>Price Per Head (₨)</strong></label>
                                <input type="number" name="per_head_price" id="extra_price_per_head" class="form-control"
                                    value="{{ $booking->per_head_price }}" min="0" required readonly>
                            </div>

                            <div class="form-group col-md-3">
                                <label><strong>Extra Guest Total (₨)</strong></label>
                                <input type="text" id="extra_total" class="form-control" readonly>
                            </div>
                            <div class="col-md-3 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Add Extra Guests
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>


@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const guestInput = document.getElementById('extra_guest_count');
            const priceInput = document.getElementById('extra_price_per_head');
            const totalInput = document.getElementById('extra_total');

            function updateTotal() {
                const guests = parseInt(guestInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                totalInput.value = (guests * price).toLocaleString('en-PK', {
                    minimumFractionDigits: 2
                });
            }

            guestInput.addEventListener('input', updateTotal);
            priceInput.addEventListener('input', updateTotal);
        });

        function printInvoice() {
            const originalHTML = document.body.innerHTML;
            const originalScroll = window.scrollY;
            const invoiceDiv = document.getElementById('invoice-div');

            if (!invoiceDiv) {
                console.error('Invoice div not found!');
                return;
            }

            document.body.innerHTML = invoiceDiv.outerHTML;

            // 🔹 Enhanced print-friendly styles with larger fonts
            const style = document.createElement('style');
            style.innerHTML = `
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            body {
                background: #fff !important;
                margin: 0;
                padding: 0;
                font-family: 'Arial', sans-serif;
                font-size: 14px; /* 🔹 Increased from default */
                line-height: 1.4;
                color: #000;
            }
            h3, h4, h5 {
                font-size: 18px !important;
            }
            table {
                font-size: 15px !important;
            }
            .no-print {
                display: none !important;
            }
        `;
            document.head.appendChild(style);

            const waitForReady = Promise.all([
                ...Array.from(document.images).map(img =>
                    img.complete ? Promise.resolve() : new Promise(res => {
                        img.addEventListener('load', res, {
                            once: true
                        });
                        img.addEventListener('error', res, {
                            once: true
                        });
                    })
                ),
                ('fonts' in document && 'ready' in document.fonts) ?
                document.fonts.ready.catch(() => {}) :
                Promise.resolve()
            ]);

            waitForReady.then(() => {
                const restorePage = () => {
                    if (document.body.innerHTML !== originalHTML) {
                        document.body.innerHTML = originalHTML;
                        window.scrollTo(0, originalScroll);
                    }
                };

                const mediaQuery = window.matchMedia('print');
                const handleChange = e => {
                    if (!e.matches) {

                        window.location.reload();
                        mediaQuery.removeEventListener('change', handleChange);
                    }
                };
                mediaQuery.addEventListener('change', handleChange);

                const restoreTimeout = setTimeout(restorePage, 8000);

                window.print();

                window.onafterprint = () => {
                    clearTimeout(restoreTimeout);

                    window.location.reload();
                    window.onafterprint = null;
                };

                window.addEventListener('focus', () => {
                    setTimeout(restorePage, 500);
                }, {
                    once: true
                });
            });
        }
    </script>
@endsection
