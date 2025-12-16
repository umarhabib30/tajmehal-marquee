@extends('layouts.admin')
@section('style')
    <style>
        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        body {
            background: #f5f7fb;
            font-family: "Poppins", Arial, sans-serif;
            font-size: 14px;
            /* increased from 12px */
            color: #333;
        }

        .page {
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            background: #fff;
            margin: 0 auto;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 10mm 8mm;
        }

        @media print {
            body {
                background: #fff !important;
                font-size: 14px !important;
                /* ensure larger font also in print */
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .page {
                margin: 0;
                padding: 8mm 10mm;
                box-shadow: none;
                border-radius: 0;
                width: 100%;
                max-width: none;
                transform: none;
            }

            .no-print {
                display: none !important;
            }
        }

        header {
            /* text-align: center; */
            margin-bottom: 14px;
        }

        header h2 {
            margin: 0;
            color: #29166f;
            font-weight: 700;
        }

        .section-header {
            background: #29166f;
            color: #fff;
            font-size: 14px;
            /* increased from 12px */
            font-weight: 600;
            padding: 5px 9px;
            border-radius: 3px;
        }

        .table {
            margin-bottom: 0;
        }

        .table-sm td,
        .table-sm th {
            padding: 4px 7px !important;
            /* slightly increased padding for spacing */
            vertical-align: top;
        }

        .small-text {
            font-size: 15px;
            /* increased from 11px */
        }

        .terms-rtl {
            direction: rtl;
            text-align: right;
            font-family: "Jameel Noori Nastaleeq", "Noto Nastaliq Urdu", serif;
            line-height: 1.7;
            font-size: 14px;
            /* increased from 12px */
        }

        .signature-box {
            min-height: 80px;
            /* slightly taller for better alignment */
        }

        ul.list-unstyled {
            margin: 0;
            padding: 0;
        }

        .list-unstyled li {
            margin-bottom: 4px;
            /* slightly increased for readability */
        }

        .border-rounded {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }


        p {
            margin-bottom: 4px !important;
        }
    </style>
@endsection


@section('content')
    <div class="page">
        <header class="pb-2 border-bottom mb-3 d-flex justify-content-between align-items-center">
            <div class="logo-box">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="Taj Mahal Marquee Logo"
                    style="height: 80px; width: auto;">
            </div>

            <div class="text-end">
                <h2 class="mb-0 text-primary fw-bold">The Taj Mahal Marquee</h2>
                <div class="small text-secondary">Link Road Aqil Shah, Shahpur</div>
                <div class="small">Contact: 0300-8700443 | 0324-1111963</div>
                <h6 class="fw-semibold mt-1 text-dark">Function Booking Invoice</h6>
            </div>
        </header>


        <div class="row g-2">
            <div class="col-6">
                <div class="border-rounded bg-light-subtle p-2 small-text">
                    <div class="section-header mb-1">Customer Details</div>
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $booking->customer->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $booking->customer->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Booking Date</th>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d-M-Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-6">
                <div class="border-rounded bg-light-subtle p-2 small-text">
                    <div class="section-header mb-1">Event Details</div>
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th>Event</th>
                                <td>{{ $booking->event_type }}</td>
                            </tr>
                            <tr>
                                <th>Hall</th>
                                <td>{{ $booking->hall_name }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{\Carbon\Carbon::parse( $booking->event_date )->format('d-M-Y')}}</td>
                            </tr>
                            <tr>
                                <th>Slot</th>
                                <td>{{ $booking->time_slot }}
                                    ({{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }})
                                </td>
                            </tr>
                            <tr>
                                <th>Guests</th>
                                <td>{{ $booking->guests_count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="border-rounded bg-white mt-2 p-2 small-text">
            <div class="section-header mb-1">Menu Items</div>
            <ul class="list-unstyled row row-cols-2 row-cols-md-4 g-1">
                @if (!empty($booking->dishPackage?->dishes))
                    @foreach ($booking->dishPackage->dishes as $dish)
                        <li class="col"><i class="bi bi-dot text-primary"></i> {{ $dish->name }}</li>
                    @endforeach
                @elseif(!empty($dishes))
                    @foreach ($dishes as $dish)
                        <li class="col"><i class="bi bi-dot text-primary"></i> {{ $dish->name }}</li>
                    @endforeach
                @else
                    <li class="text-muted">No dishes selected</li>
                @endif
            </ul>
        </div>

        <div class="row g-2 mt-2">
            <div class="col-6">
                <div class="border-rounded bg-light-subtle p-2 small-text">
                    <div class="section-header mb-1">Decorations</div>
                    <p class="mb-1"><strong>Selected Items:</strong>
                        @if (!empty($decorations))
                            {{ implode(', ', $decorations) }}
                        @else
                            <span class="text-muted">None</span>
                        @endif
                    </p>
                    <p class="mb-0"><strong>Decoration Charges:</strong> ₨ {{ number_format($booking->decore_price, 2) }}
                    </p>
                </div>
            </div>
            <div class="col-6">
                <div class="border-rounded bg-light-subtle p-2 small-text">
                    <div class="section-header mb-1">Payment Summary</div>
                    <table class="table table-bordered table-sm text-end mb-0">
                        <tbody>
                            <tr>
                                <th class="text-start">Price Per Head</th>
                                <td>₨ {{ number_format($booking->per_head_price, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Decoration Charges</th>
                                <td>₨ {{ number_format($booking->decore_price, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Tax</th>
                                <td>₨ {{ number_format($booking->tax_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Total Amount</th>
                                <td>₨ {{ number_format($booking->total_amount, 2) }}</td>
                            </tr>



                            <tr>
                                <th class="text-start">Total Paid</th>
                                <td>₨ {{ number_format($totalPaid, 2) }}</td>
                            </tr>

                            <tr class="table-warning fw-bold">
                                <th class="text-start">Remaining</th>
                                <td>₨ {{ number_format($booking->remaining_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        @if ($booking->payments->count() > 0)
            <div class="border-rounded mt-2 bg-white p-2 small-text">
                <div class="section-header mb-1">Payment History</div>
                <table class="table table-striped table-bordered table-sm">
                    <thead class="table-light">
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
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                                <td>₨ {{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="border-rounded mt-2 bg-light-subtle p-2 mb-2">
            <div class="section-header mb-1" style="text-align: end;">شرائط و ضوابط</div>
            <div class="terms-rtl border-end border-4 border-primary bg-white p-2">
                <p>★کھانے سے پہلے مہمانوں کی تعداد چیک کر لیں اضافی تعداد کے چارجز وصول کئے جائیں گے۔</p>
                <p>★ 75٪ رقم تقریب سے سات دن پہلے ادا کرنا ہوگی۔</p>
                <p>★اپنی قیمتی اشیاء اور کار ، موٹر سائیکل کی حفاظت خود کریں انتظامیہ زمہ دار نہ ہو گی۔</p>
                <p>★تقریب میں تاخیر کی صورت میں اضافی چارجز لاگو ہوں گے۔</p>
                {{-- <p>★آتش بازی اور فائرنگ قانوناً ممنوع ہے</p> --}}
                <p>★ 5 آتش بازی اور فائرنگ قانوناً ممنوع ہے۔</p>
            </div>
        </div>

        <div class="row g-2 mt-3" style="margin: 0px !important;">
            <div class="col-6 text-center border-rounded p-2 signature-box">
                @if ($booking->customer_signature)
                    <img src="{{ $booking->customer_signature }}" alt="Customer Signature" style="max-width:120px;">
                @endif
                <div class="fw-semibold">Customer Signature</div>
            </div>
            <div class="col-6 text-center border-rounded p-2 signature-box">
                @if ($booking->manager_signature)
                    <img src="{{ $booking->manager_signature }}" alt="Manager Signature" style="max-width:120px;">
                @endif
                <div class="fw-semibold">Manager Signature</div>
            </div>
        </div>

        <div class="text-end mt-3 no-print">
            <button type="button" class="btn btn-success btn-sm" onclick="printPageOnly()">
                <i class="bi bi-printer me-1"></i> Print Invoice
            </button>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function printPageOnly() {
            const page = document.querySelector('.page');
            if (!page) return;

            const originalHTML = document.body.innerHTML;
            const originalScroll = window.scrollY;
            const pageHTML = page.outerHTML;

            // Replace body with only the invoice section
            document.body.innerHTML = pageHTML;

            // Wait for fonts and images
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
                // Define restore logic
                const restore = () => {
                    document.body.innerHTML = originalHTML;
                    window.scrollTo(0, originalScroll);
                };

                // Always restore — whether print is canceled or completed
                const mediaQuery = window.matchMedia('print');
                const handleChange = e => {
                    if (!e.matches) {
                        restore();
                        mediaQuery.removeEventListener('change', handleChange);
                    }
                };

                mediaQuery.addEventListener('change', handleChange);

                // Backup fallback in case browser doesn't trigger events
                const restoreFallback = setTimeout(() => restore(), 2000);

                // Print
                window.print();

                // For browsers that support afterprint
                window.onafterprint = () => {
                    clearTimeout(restoreFallback);
                    restore();
                    window.onafterprint = null;
                };
            });
        }
    </script>
@endsection
