@extends('layouts.admin')

@section('content')
<div class="container-fluid dashboard-content">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
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
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="bookingModalTitle"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-2">
            <div class="col-md-6">
                <p><strong>Customer:</strong> <span id="bookingCustomer"></span></p>
                <p><strong>Event Type:</strong> <span id="bookingType"></span></p>
                <p><strong>Hall:</strong> <span id="bookingHall"></span></p>
            </div>
            <div class="col-md-6">
                <p><strong>Date:</strong> <span id="bookingDate"></span></p>
                <p><strong>Time:</strong> <span id="bookingTime"></span></p>
                <p><strong>Status:</strong> <span id="bookingStatus" class="badge"></span></p>
            </div>
        </div>
        <hr>
        <p><strong>Special Request:</strong></p>
        <div class="border rounded p-2 bg-light" id="bookingRequest"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    // Color palette for events
    const colors = ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#fd7e14', '#6f42c1', '#e83e8c'];
    let colorIndex = 0;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        themeSystem: 'bootstrap5',
        events: '{{ route("calendar.events") }}',
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: true },
        headerToolbar: {
            left: '',
            center: 'title',
            right: ''
        },
        eventDisplay: 'block',
        eventDidMount: function(info) {
            info.el.style.borderRadius = '8px';
            info.el.style.padding = '2px 4px';
            info.el.style.cursor = 'pointer'; // ✅ Pointer on hover

            // ✅ Different color per booking
            info.el.style.backgroundColor = colors[colorIndex % colors.length];
            colorIndex++;
        },
        eventClick: function(info) {
            const booking = info.event.extendedProps;

            $('#bookingModalTitle').text(info.event.title);
            $('#bookingCustomer').text(booking.customer_name || 'N/A');
            $('#bookingType').text(booking.event_type || 'N/A');
            $('#bookingDate').text(booking.booking_date || 'N/A');
            $('#bookingTime').text(booking.booking_time || 'N/A');
            $('#bookingHall').text(booking.hall_name || 'N/A');
            $('#bookingRequest').text(booking.special_request || 'No request');

            const badge = $('#bookingStatus');
            badge.text(booking.status);
            badge.removeClass().addClass('badge ' + 
                (booking.status === 'Completed' ? 'bg-success' :
                 booking.status === 'Cancelled' ? 'bg-danger' : 'bg-primary'));

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

<style>
/* Minor visual polish */
#calendar {
    max-width: 100%;
    margin: 0 auto;
    border: 1px solid #dee2e6; /* ✅ Calendar border */
    border-radius: 8px;
}
.fc-daygrid-day-frame {
    border: 1px solid #dee2e6; /* ✅ Day box borders */
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
