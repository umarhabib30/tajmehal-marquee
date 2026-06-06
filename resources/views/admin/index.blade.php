@extends('layouts.admin')
@section('style')
    <style>
        /* Keep the calendar responsive and centered */
        .dashboard-content {
            width: 100%;
            overflow-x: hidden;
        }

        .card {
            width: 100%;
            max-width: 95%;
            /* prevent overflow near sidebar */
        }

        /* Calendar width slightly smaller for good fit */
        #calendar {
            width: 100%;
            max-width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        /* Grid & typography */
        .fc-daygrid-day-frame {
            border: 1px solid #dee2e6;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 600;
        }

        .fc-event {
            font-size: 0.85rem;
            border: none;
        }

        /* Booking status legend (dashboard calendar) */
        .booking-status-legend {
            font-size: 0.875rem;
        }

        .booking-status-legend .legend-swatch {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 3px;
            vertical-align: middle;
            margin-right: 4px;
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
    </style>
@endsection
@section('content')
    <div class="dashboard-content px-2"> {{-- Reduced horizontal padding --}}

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 px-3">
            <h2 class="pageheader-title mb-2 mb-md-0">
                <i class="fa fa-calendar-check text-primary me-2"></i> Bookings Calendar
            </h2>
            <div class="d-flex gap-2">
                <select id="monthSelect" class="form-select form-select-sm">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
                <select id="yearSelect" class="form-select form-select-sm">
                    @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
                <button id="goToDateBtn" class="btn btn-primary btn-sm">
                    <i class="fa fa-search"></i> Go
                </button>
                <button id="resetBtn" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-undo"></i> Today
                </button>
            </div>
            <div class="booking-status-legend mt-2 mt-md-0 text-muted px-3">
                <span class="me-3"><span class="legend-swatch legend-active"></span>Active</span>
                <span class="me-3"><span class="legend-swatch legend-pending"></span>Pending</span>
                <span class="me-3"><span class="legend-swatch legend-done"></span>Done</span>
                <span><span class="legend-swatch legend-cancelled"></span>Cancelled</span>
            </div>
        </div>

        <!-- Calendar Card -->
        <div class="card shadow-sm border-0 mx-auto" style="max-width: 95%;">
            <div class="card-body p-3">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white d-flex flex-nowrap align-items-center">
                    <div class="flex-grow-1 me-2" style="min-width: 0;">
                        <h5 class="modal-title mb-0 text-truncate" style="color: white !important;">
                            <span id="bookingModalTitle"></span>
                        </h5>
                    </div>
                    <span id="bookingModalStatusBadge"
                        class="badge rounded-pill px-3 py-2 border border-white flex-shrink-0 me-2 d-none"
                        style="font-size: 0.8rem;"></span>
                    <button type="button" class="btn-close btn-close-white flex-shrink-0" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-2">Customer Details</h6>
                            <p><strong>Name:</strong> <span id="bookingCustomer"></span></p>
                            <p><strong>Phone:</strong> <span id="bookingPhone"></span></p>
                            <p><strong>Address:</strong> <span id="bookingAddress"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-2">Event Details</h6>
                            <p><strong>Type:</strong> <span id="bookingType"></span></p>
                            <p><strong>Date:</strong> <span id="bookingDate"></span></p>
                            <p><strong>Time:</strong> <span id="bookingTime"></span></p>
                            <p><strong>Hall:</strong> <span id="bookingHall"></span></p>
                            <p class="mb-0"><strong>Total Guests:</strong> <span id="bookingGuests"></span></p>
                        </div>
                    </div>

                    <hr>

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

                    // Customer Info
                    $('#bookingModalTitle').text(`${b.event_type || 'Event'} - ${b.time_slot || ''}`);
                    $('#bookingModalStatusBadge').removeClass('d-none').text(statusLabel).css({
                        backgroundColor: statusBg,
                        color: '#ffffff'
                    });
                    $('#bookingCustomer').text(b.customer_name || 'N/A');
                    $('#bookingPhone').text(b.customer_phone || 'N/A');
                    $('#bookingAddress').text(b.customer_address || 'N/A');

                    // Event Info
                    $('#bookingType').text(b.event_type || 'N/A');
                    $('#bookingDate').text(b.event_date || 'N/A');
                    $('#bookingTime').text(`${formatTime(b.start_time)} - ${formatTime(b.end_time)}`);
                    $('#bookingHall').text(b.hall_name || 'N/A');
                    $('#bookingGuests').text(Number(b.guests_count || 0).toLocaleString());

                    // Payment Info
                    const paymentHTML = `
                <p><strong>Total Amount:</strong> ₨ ${Number(b.total_amount || 0).toLocaleString()}</p>
                <p><strong>Total Paid:</strong> ₨ ${Number(b.total_paid || 0).toLocaleString()}</p>
                <p><strong>Pending Amount:</strong>
                    <span class="text-danger fw-bold">₨ ${Number(b.pending_amount || 0).toLocaleString()}</span>
                </p>
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
