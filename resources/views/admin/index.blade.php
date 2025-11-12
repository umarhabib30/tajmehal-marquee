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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookingModalTitle" style="color: white !important;"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
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
            const colors = ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#fd7e14', '#6f42c1', '#e83e8c'];
            let colorIndex = 0;

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
                    info.el.style.backgroundColor = colors[colorIndex % colors.length];
                    colorIndex++;
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

                    // Customer Info
                    $('#bookingModalTitle').text(`${b.event_type || 'Event'} - ${b.time_slot || ''}`);
                    $('#bookingCustomer').text(b.customer_name || 'N/A');
                    $('#bookingPhone').text(b.customer_phone || 'N/A');
                    $('#bookingAddress').text(b.customer_address || 'N/A');

                    // Event Info
                    $('#bookingType').text(b.event_type || 'N/A');
                    $('#bookingDate').text(b.event_date || 'N/A');
                    $('#bookingTime').text(`${formatTime(b.start_time)} - ${formatTime(b.end_time)}`);
                    $('#bookingHall').text(b.hall_name || 'N/A');

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
