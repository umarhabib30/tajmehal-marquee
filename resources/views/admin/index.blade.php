@extends('layouts.admin')
@section('style')
    <style>
        :root {
            --ui-primary: #352a86;
            --ui-primary-soft: #f2f0ff;
            --ui-surface: #ffffff;
            --ui-border: #dfe4f2;
            --ui-text: #2f3547;
            --ui-muted: #6b7280;
        }

        /* Keep the calendar responsive and centered */
        .dashboard-content {
            width: 100%;
            overflow-x: hidden;
            padding-bottom: 8px;
            background: linear-gradient(180deg, #f4f6fb 0%, #eef2f9 100%);
            border-radius: 10px;
        }

        .calendar-card {
            width: 100%;
            max-width: 100%;
            border: 1px solid var(--ui-border);
            border-radius: 10px;
            box-shadow: 0 10px 24px rgba(26, 20, 77, 0.08);
            background: var(--ui-surface);
        }

        /* Calendar width slightly smaller for good fit */
        #calendar {
            width: 100%;
            max-width: 100%;
            border: 1px solid #e6eaf5;
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
        }

        /* Grid & typography */
        .fc-daygrid-day-frame {
            border: 1px solid #ebeff8;
            min-height: 92px;
            transition: background-color 0.15s ease-in-out;
            padding: 4px;
            overflow: hidden;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
        }

        .fc-daygrid-day-frame:hover {
            background-color: #f6f8ff;
        }

        .fc-col-header-cell {
            background: #f7f9ff;
        }

        .fc-col-header-cell-cushion {
            color: #525c73;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 8px 0;
        }

        .fc-daygrid-day-number {
            font-weight: 600;
            color: #4e566b;
            font-size: 0.82rem;
        }

        .fc .fc-day-today {
            background: rgba(53, 42, 134, 0.11) !important;
        }

        .fc-theme-standard td,
        .fc-theme-standard th,
        .fc-theme-standard .fc-scrollgrid {
            border-color: #e6eaf5;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 600;
            color: var(--ui-primary);
            margin: 0 !important;
            line-height: 1.2;
            letter-spacing: 0.2px;
        }

        .fc .fc-toolbar.fc-header-toolbar {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            margin-bottom: 0.8rem;
        }

        .fc .fc-toolbar-chunk {
            display: flex;
            align-items: center;
        }

        .fc-event {
            font-size: 0.79rem;
            border: none;
            border-radius: 6px;
            padding: 2px 5px;
            font-weight: 600;
            width: calc(100% - 6px);
            max-width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.22);
            transform: translateY(0);
        }

        .fc .fc-daygrid-day-events {
            margin: 2px 2px 0;
        }

        .fc .fc-daygrid-event-harness {
            margin-top: 2px;
        }

        .fc .fc-daygrid-day-top {
            padding: 0 2px 2px;
        }

        /* Booking status legend (dashboard calendar) */
        .booking-status-legend {
            font-size: 0.875rem;
            color: var(--ui-muted);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .booking-status-legend .legend-swatch {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 4px;
        }

        .booking-status-legend > span {
            border: 1px solid #e2e7f3;
            background: #fff;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .legend-active {
            background: #6c757d;
        }

        .legend-pending {
            background: #ffc107;
        }

        .legend-done {
            background: #28a745;
        }

        .legend-cancelled {
            background: #dc3545;
        }

        .modal-content {
            border-radius: 12px;
        }

        .dashboard-filter-bar {
            border: 1px solid var(--ui-border);
            border-top: 3px solid var(--ui-primary);
            border-radius: 10px;
            background: linear-gradient(180deg, #fcfbff 0%, #ffffff 100%);
            padding: 14px 14px 12px;
            box-shadow: 0 6px 18px rgba(25, 20, 74, 0.06);
        }

        .dashboard-filter-wrap {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .dashboard-filter-controls {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-field {
            min-width: 160px;
        }

        .filter-label {
            display: block;
            font-size: 0.74rem;
            font-weight: 700;
            color: #56617a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.35rem;
        }

        .dashboard-filter-select {
            height: 40px;
            min-width: 150px;
            border: 1px solid #d7dced;
            border-radius: 8px;
            font-weight: 500;
            color: var(--ui-text);
            background: #fff;
            box-shadow: inset 0 1px 1px rgba(17, 24, 39, 0.02);
        }

        .dashboard-filter-select:focus {
            border-color: var(--ui-primary);
            box-shadow: 0 0 0 0.2rem rgba(53, 42, 134, 0.14);
        }

        .dashboard-filter-btn {
            height: 40px;
            min-width: 98px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 0;
            transition: all 0.18s ease;
        }

        .dashboard-filter-btn:hover {
            transform: translateY(-1px);
        }

        #goToDateBtn {
            background: linear-gradient(180deg, #4536b1 0%, #352a86 100%);
            border-color: #352a86;
            box-shadow: 0 6px 14px rgba(53, 42, 134, 0.22);
        }

        #goToDateBtn:hover {
            background: linear-gradient(180deg, #3e30a4 0%, #2f2479 100%);
            border-color: #2f2479;
        }

        #resetBtn {
            background: var(--ui-primary-soft);
            border-color: #d8d2ff;
            color: var(--ui-primary);
        }

        #resetBtn:hover {
            background: #ebe7ff;
            border-color: #cac0ff;
            color: #2f2479;
        }

        .dashboard-filter-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .booking-modal .modal-content {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(25, 20, 74, 0.24);
        }

        .booking-modal-header {
            background: linear-gradient(120deg, #332881 0%, #4b3eb6 100%) !important;
            border-bottom: 0;
            padding: 14px 16px;
        }

        .booking-modal-body {
            background: #f8f9ff;
            padding: 16px;
        }

        .booking-info-card {
            background: #fff;
            border: 1px solid #e4e8f6;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(25, 20, 74, 0.05);
            padding: 12px;
            height: 100%;
        }

        .booking-info-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: #332881;
            margin-bottom: 10px;
        }

        .booking-info-card p {
            margin-bottom: 0.4rem;
            color: #374151;
            font-size: 0.9rem;
        }

        .booking-info-card p:last-child {
            margin-bottom: 0;
        }

        .booking-payment-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .booking-payment-item {
            background: #fff;
            border: 1px solid #e4e8f6;
            border-radius: 10px;
            padding: 10px 12px;
            box-shadow: 0 2px 8px rgba(25, 20, 74, 0.05);
        }

        .booking-payment-label {
            display: block;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .booking-payment-value {
            display: block;
            color: #1f2937;
            font-weight: 700;
            font-size: 1rem;
        }

        .booking-payment-value.pending {
            color: #d12f2f;
        }

        .booking-modal .modal-footer {
            border-top: 1px solid #e6eaf5;
            padding: 12px 16px;
        }

        .booking-modal .modal-footer .btn {
            border-radius: 8px;
            font-weight: 600;
        }

        .booking-modal-close {
            border: 0;
            background: transparent;
            color: #ffffff;
            font-size: 1rem;
            line-height: 1;
            padding: 0.2rem 0.35rem;
            border-radius: 6px;
            opacity: 0.95;
        }

        .booking-modal-close:hover {
            background: rgba(255, 255, 255, 0.16);
            opacity: 1;
        }

        @media (max-width: 768px) {
            .booking-payment-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
@section('content')
    <div class="dashboard-content px-2"> {{-- Reduced horizontal padding --}}

        <!-- Page Header -->
        <div class="mb-3 px-3">
            <h2 class="pageheader-title mb-3">
                <i class="fa fa-calendar-check text-primary me-2"></i> Bookings Calendar
            </h2>

            <div class="dashboard-filter-bar">
                <div class="dashboard-filter-wrap">
                    <div class="dashboard-filter-controls">
                        <div class="filter-field">
                            <label class="filter-label">Month</label>
                            <select id="monthSelect" class="form-select dashboard-filter-select">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="filter-field">
                            <label class="filter-label">Year</label>
                            <select id="yearSelect" class="form-select dashboard-filter-select">
                                @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="filter-field" style="min-width: auto;">
                            <label class="filter-label">Actions</label>
                            <div class="dashboard-filter-actions">
                                <button id="goToDateBtn" class="btn btn-primary dashboard-filter-btn">
                                    <i class="fa fa-search"></i> Go
                                </button>
                                <button id="resetBtn" class="btn btn-outline-secondary dashboard-filter-btn">
                                    <i class="fa fa-undo"></i> Today
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="booking-status-legend text-muted">
                        <span class="me-3"><span class="legend-swatch legend-active"></span>Active</span>
                        <span class="me-3"><span class="legend-swatch legend-pending"></span>Pending</span>
                        <span class="me-3"><span class="legend-swatch legend-done"></span>Done</span>
                        <span><span class="legend-swatch legend-cancelled"></span>Cancelled</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Card -->
        <div class="px-3">
            <div class="card calendar-card mt-3">
                <div class="card-body p-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div> 
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade booking-modal" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header booking-modal-header text-white d-flex flex-nowrap align-items-center">
                    <div class="flex-grow-1 me-2" style="min-width: 0;">
                        <h5 class="modal-title mb-0 text-truncate" style="color: white !important;">
                            <span id="bookingModalTitle"></span>
                        </h5>
                    </div>
                    <span id="bookingModalStatusBadge"
                        class="badge rounded-pill px-3 py-2 border border-white flex-shrink-0 me-2 d-none"
                        style="font-size: 0.8rem;"></span>
                    <button type="button" class="booking-modal-close flex-shrink-0" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body booking-modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="booking-info-card">
                                <h6 class="booking-info-title">Customer Details</h6>
                                <p><strong>Name:</strong> <span id="bookingCustomer"></span></p>
                                <p><strong>Phone:</strong> <span id="bookingPhone"></span></p>
                                <p><strong>Address:</strong> <span id="bookingAddress"></span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="booking-info-card">
                                <h6 class="booking-info-title">Event Details</h6>
                                <p><strong>Booking ID:</strong> <span id="bookingId"></span></p>
                                <p><strong>Type:</strong> <span id="bookingType"></span></p>
                                <p><strong>Date:</strong> <span id="bookingDate"></span></p>
                                <p><strong>Time:</strong> <span id="bookingTime"></span></p>
                                <p><strong>Hall:</strong> <span id="bookingHall"></span></p>
                                <p class="mb-0"><strong>Total Guests:</strong> <span id="bookingGuests"></span></p>
                            </div>
                        </div>
                    </div>

                    <div id="bookingPaymentInfo" class="mt-2"></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Close</button>
                    <a id="viewBookingBtn" href="#" class="btn btn-primary btn-sm">
                        <i class="fa fa-eye"></i> View Booking Details
                    </a>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="" value="{{ url('admin/booking/show') }}" id="bookingShowUrl">
@endsection
@section('script')
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: '{{ route('calendar.events') }}',
                displayEventTime: false,

                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: ''
                },

                eventContent: function(info) {
                    const b = info.event.extendedProps;
                    return {
                        html: `
                    <div style="line-height:1.2; text-align:center; font-family: Arial, sans-serif; font-size: 0.85rem;">
                        ${b.event_type || 'Event'} - ${b.time_slot || ''}<br>
                        ${b.customer_name || ''}
                    </div>
                `
                    };
                },

                eventDisplay: 'block',

                eventDidMount: function(info) {
                    info.el.style.borderRadius = '8px';
                    info.el.style.padding = '2px 4px';
                    info.el.style.cursor = 'pointer';
                },

                eventClick: function(info) {
                    const b = info.event.extendedProps;

                    // Helper: format time in 12-hour AM/PM format
                    function formatTime(timeStr) {
                        if (!timeStr || timeStr === 'N/A') return 'N/A';
                        const [hour, minute] = timeStr.split(':');
                        let h = parseInt(hour);
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        h = h % 12 || 12;
                        return `${h}:${minute} ${ampm}`;
                    }

                    const statusLabel = (b.status != null && String(b.status).trim() !== '')
                        ? String(b.status).trim()
                        : 'Active';
                    const statusBg = info.event.backgroundColor || info.event.borderColor ||
                        b.status_color || '#6c757d';
                    const statusTextColor = b.status_text_color || '#ffffff';

                    // Customer Info
                    $('#bookingModalTitle').text(
                        `Booking #${info.event.id || 'N/A'} ${b.event_type || 'Event'} ${b.time_slot || ''}`
                    );
                    $('#bookingModalStatusBadge').removeClass('d-none').text(statusLabel).css({
                        backgroundColor: statusBg,
                        color: statusTextColor
                    });
                    $('#bookingCustomer').text(b.customer_name || 'N/A');
                    $('#bookingPhone').text(b.customer_phone || 'N/A');
                    $('#bookingAddress').text(b.customer_address || 'N/A');

                    // Event Info
                    $('#bookingId').text(info.event.id || 'N/A');
                    $('#bookingType').text(b.event_type || 'N/A');
                    $('#bookingDate').text(b.event_date || 'N/A');
                    $('#bookingTime').text(`${formatTime(b.start_time)} - ${formatTime(b.end_time)}`);
                    $('#bookingHall').text(b.hall_name || 'N/A');
                    $('#bookingGuests').text(Number(b.guests_count || 0).toLocaleString());

                    // Payment Info
                    const paymentHTML = `
                <div class="booking-payment-grid">
                    <div class="booking-payment-item">
                        <span class="booking-payment-label">Total Amount</span>
                        <span class="booking-payment-value">₨ ${Number(b.total_amount || 0).toLocaleString()}</span>
                    </div>
                    <div class="booking-payment-item">
                        <span class="booking-payment-label">Total Paid</span>
                        <span class="booking-payment-value">₨ ${Number(b.total_paid || 0).toLocaleString()}</span>
                    </div>
                    <div class="booking-payment-item">
                        <span class="booking-payment-label">Pending Amount</span>
                        <span class="booking-payment-value pending">₨ ${Number(b.pending_amount || 0).toLocaleString()}</span>
                    </div>
                </div>
            `;
                    $('#bookingPaymentInfo').html(paymentHTML);

                    // Booking Details Button
                    const bookingShowUrl = $('#bookingShowUrl').val();
                    $('#viewBookingBtn').attr('href', bookingShowUrl + '/' + info.event.id);

                    // Show Modal
                    $('#bookingModal').modal('show');
                }
            });

            calendar.render();

            // Month & Year filter
            $('#goToDateBtn').on('click', function() {
                var month = $('#monthSelect').val();
                var year = $('#yearSelect').val();
                calendar.gotoDate(new Date(year, month - 1, 1));
            });

            // Reset to current month
            $('#resetBtn').on('click', function() {
                calendar.today();
                $('#monthSelect').val(new Date().getMonth() + 1);
                $('#yearSelect').val(new Date().getFullYear());
            });
        });
    </script>
@endsection
