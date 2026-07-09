@extends('layouts.admin')
@section('style')
    <style>
        :root {
            --att-primary: #352a86;
            --att-primary-soft: #f2f0ff;
            --att-border: #dfe4f2;
            --att-text: #2f3547;
            --att-muted: #6b7280;
        }

        .attendance-page .card {
            border-top: 3px solid var(--att-primary);
            border-radius: 10px;
            box-shadow: 0 8px 22px rgba(26, 20, 77, 0.08);
            overflow: hidden;
        }

        .attendance-titlebar {
            background: linear-gradient(120deg, #332881 0%, #4b3eb6 100%);
            color: #fff;
            padding: 14px 16px;
        }

        .attendance-titlebar h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
            color: #ffffff !important;
        }

        .attendance-filter-bar {
            border: 1px solid var(--att-border);
            border-top: 3px solid var(--att-primary);
            border-radius: 10px;
            background: linear-gradient(180deg, #fcfbff 0%, #ffffff 100%);
            padding: 12px;
            margin-bottom: 14px;
        }

        .attendance-filter-wrap {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .attendance-filter-controls {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .attendance-filter-field {
            min-width: 170px;
        }

        .attendance-filter-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #56617a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.35rem;
        }

        .attendance-filter-control {
            width: 100%;
            height: 40px;
            border: 1px solid #d7dced;
            border-radius: 8px;
            font-weight: 500;
            color: var(--att-text);
            background: #fff;
        }

        .attendance-filter-control:focus {
            border-color: var(--att-primary);
            box-shadow: 0 0 0 0.2rem rgba(53, 42, 134, 0.14);
        }

        .attendance-filter-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .attendance-filter-btn {
            min-width: 98px;
            height: 40px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .attendance-legend {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .attendance-legend > span {
            border: 1px solid #e2e7f3;
            background: #fff;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #4b5563;
        }

        .attendance-legend .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
            padding: 0 !important;
            border: 0 !important;
            flex: 0 0 9px;
        }

        .dot-p { background: #28a745; }
        .dot-a { background: #dc3545; }
        .dot-l { background: #007bff; }

        .table-responsive {
            overflow: auto;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 2;
        }

        thead .sticky-col {
            z-index: 3;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 3;
        }

        .sticky-controls {
            position: sticky;
            top: 0;
            z-index: 4;
            background: #fff;
            padding: 5px;
        }

        .status-checkboxes {
            display: flex;
            justify-content: center;
            gap: 4px;
        }

        .status-checkboxes label {
            font-size: 12px;
            cursor: pointer;
            margin-bottom: 0;
        }

        .status-checkboxes input {
            margin-right: 2px;
            width: 14px;
            height: 14px;
        }

        .text-truncate {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .disabled-cell {
            background-color: #f2f2f2;
            opacity: 0.6;
            pointer-events: none;
        }

        #attendanceTable thead th {
            background: #f3f5fd !important;
            color: #3f4a62 !important;
            border-color: #e1e6f3 !important;
            font-weight: 700;
        }

        #attendanceTable td {
            border-color: #e7ebf6 !important;
            color: #374151;
        }

        #attendanceTable tbody tr:hover {
            background: #f8f9ff;
        }

        /* --- NEW COLORS --- */

        /* Present = Green */
        .status-box[value="present"]:checked {
            accent-color: #28a745;
        }

        /* Absent = Red */
        .status-box[value="absent"]:checked {
            accent-color: #dc3545;
        }

        /* Leave = Blue */
        .status-box[value="leave"]:checked {
            accent-color: #007bff;
        }
    </style>
@endsection
@section('content')
    <div class="row attendance-page">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="attendance-titlebar">
                    <h5>
                        Attendance Management - {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}
                    </h5>
                </div>
                <div class="card-body">

                    <!-- Month/Year Controls -->
                    <div class="attendance-filter-bar no-print sticky-controls">
                        <div class="attendance-filter-wrap">
                            <div class="attendance-filter-controls">
                                <div class="attendance-filter-field">
                                    <label class="attendance-filter-label">Month</label>
                                    <select id="selectMonth" class="form-control attendance-filter-control">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="attendance-filter-field">
                                    <label class="attendance-filter-label">Year</label>
                                    <select id="selectYear" class="form-control attendance-filter-control">
                                        @for ($y = date('Y') - 5; $y <= date('Y'); $y++)
                                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label class="attendance-filter-label">Actions</label>
                                    <div class="attendance-filter-actions">
                                        <button id="changeMonth" class="btn btn-primary attendance-filter-btn">
                                            <i class="fa fa-search"></i> Go
                                        </button>
                                        <button id="thisMonth" class="btn btn-outline-secondary attendance-filter-btn">
                                            <i class="fa fa-undo"></i> This Month
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="attendance-legend">
                                <span><span class="dot dot-p"></span>P = Present</span>
                                <span><span class="dot dot-a"></span>A = Absent</span>
                                <span><span class="dot dot-l"></span>L = Leave</span>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Table -->
                    <div class="table-responsive">
                        <table id="attendanceTable"
                            class="table table-striped table-bordered text-center table-sm align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="sticky-col bg-white">Staff Name</th>
                                    @for ($i = 1; $i <= $monthDays; $i++)
                                        <th class="sticky-header bg-white">{{ $i }}</th>
                                    @endfor
                                    <th class="sticky-header bg-white">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staff as $s)
                                    <tr>
                                        <td class="sticky-col bg-white text-truncate" style="max-width:150px;">
                                            {{ $s->name }}</td>
                                        @for ($i = 1; $i <= $monthDays; $i++)
                                            @php
                                                $date = \Carbon\Carbon::create($year, $month, $i)->toDateString();
                                                $record = $attendances
                                                    ->where('staff_id', $s->id)
                                                    ->where('date', $date)
                                                    ->first();
                                                $status = $record ? $record->status : '';

                                                $isToday = $date === \Carbon\Carbon::today()->toDateString();
                                                $canEditToday = true;
                                                if ($isToday && $record) {
                                                    $markedTime = \Carbon\Carbon::parse($record->created_at);
                                                    $canEditToday = $markedTime->addHours(4)->isFuture();
                                                }

                                                // ✅ Joining date check
                                                $joiningDate = $s->joining_date
                                                    ? \Carbon\Carbon::parse($s->joining_date)->toDateString()
                                                    : null;
                                                $beforeJoining = $joiningDate && $date < $joiningDate;
                                            @endphp
                                            <td class="{{ $beforeJoining ? 'disabled-cell' : '' }}"
                                                title="{{ $beforeJoining ? 'Joined after this date' : '' }}">
                                                <div class="status-checkboxes" data-staff="{{ $s->id }}"
                                                    data-date="{{ $date }}" data-joining="{{ $joiningDate }}">
                                                    <label><input type="checkbox" class="status-box" value="present"
                                                            {{ $status == 'present' ? 'checked' : '' }}
                                                            {{ !$canEditToday || $beforeJoining ? 'disabled' : '' }}>
                                                        P</label>
                                                    <label><input type="checkbox" class="status-box" value="absent"
                                                            {{ $status == 'absent' ? 'checked' : '' }}
                                                            {{ !$canEditToday || $beforeJoining ? 'disabled' : '' }}>
                                                        A</label>
                                                    <label><input type="checkbox" class="status-box" value="leave"
                                                            {{ $status == 'leave' ? 'checked' : '' }}
                                                            {{ !$canEditToday || $beforeJoining ? 'disabled' : '' }}>
                                                        L</label>
                                                </div>
                                            </td>
                                        @endfor
                                        <td>
                                            <a href="{{ route('attendence.view', ['id' => $s->id, 'month' => $month, 'year' => $year]) }}"
                                                class="btn btn-info btn-sm">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- Toastr CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Scripts -->
    <script>
        document.getElementById('changeMonth').addEventListener('click', () => {
            const month = document.getElementById('selectMonth').value;
            const year = document.getElementById('selectYear').value;
            window.location.href = `?month=${month}&year=${year}`;
        });
        document.getElementById('thisMonth').addEventListener('click', () => {
            const now = new Date();
            window.location.href = `?month=${now.getMonth()+1}&year=${now.getFullYear()}`;
        });

        // ✅ Attendance marking with joining date restriction + Toastr alerts
        document.querySelectorAll('.status-checkboxes').forEach(container => {
            const checkboxes = container.querySelectorAll('.status-box');
            const joiningDate = container.dataset.joining;

            checkboxes.forEach(box => {
                box.addEventListener('change', () => {
                    const staff_id = container.dataset.staff;
                    const date = container.dataset.date;
                    const status = box.checked ? box.value : '';

                    // Check joining date restriction
                    if (joiningDate && date < joiningDate) {
                        toastr.warning('Cannot mark attendance — staff joined later.', 'Warning');
                        box.checked = false;
                        return;
                    }

                    // Allow only one checked box
                    checkboxes.forEach(b => {
                        if (b !== box) b.checked = false;
                    });

                    // Send update to server
                    fetch('{{ route('attendence.updateStatus') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                staff_id,
                                date,
                                status
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'error') {
                                toastr.error(data.message, 'Error');
                                box.checked = false;
                            } else {
                                toastr.success('Attendance saved successfully!', 'Success');
                            }
                        })
                        .catch(() => toastr.error('Something went wrong', 'Error'));
                });
            });
        });

        // Initialize DataTable with scroll and sticky header
        $(document).ready(function() {
            $('#attendanceTable').DataTable({
                scrollX: true,
                scrollY: '500px',
                scrollCollapse: true,
                paging: false,
                searching: false,
                ordering: false,
                info: false,
                fixedHeader: true
            });
        });
    </script>
@endsection
