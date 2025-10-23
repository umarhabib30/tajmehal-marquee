@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <h5 class="card-header bg-primary text-white">
                Attendance Management - {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}
            </h5>
            <div class="card-body">

                <!-- Month/Year Controls -->
                <div class="mb-3 d-flex justify-content-between flex-wrap sticky-controls bg-white p-2 shadow-sm rounded">
                    <div>
                        <label>Select Month:</label>
                        <select id="selectMonth" class="form-control d-inline-block w-auto">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m==$month?'selected':'' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endfor
                        </select>

                        <label>Select Year:</label>
                        <select id="selectYear" class="form-control d-inline-block w-auto">
                            @for($y = date('Y')-5; $y <= date('Y'); $y++)
                                <option value="{{ $y }}" {{ $y==$year?'selected':'' }}>{{ $y }}</option>
                            @endfor
                        </select>

                        <button id="changeMonth" class="btn btn-primary btn-sm">Go</button>
                        <button id="thisMonth" class="btn btn-secondary btn-sm">This Month</button>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="table-responsive">
                    <table id="attendanceTable" class="table table-striped table-bordered text-center table-sm align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="sticky-col bg-white">Staff Name</th>
                                @for($i = 1; $i <= $monthDays; $i++)
                                    <th class="sticky-header bg-white">{{ $i }}</th>
                                @endfor
                                <th class="sticky-header bg-white">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $s)
                                <tr>
                                    <td class="sticky-col bg-white text-truncate" style="max-width:150px;">{{ $s->name }}</td>
                                    @for($i = 1; $i <= $monthDays; $i++)
                                        @php
                                            $date = \Carbon\Carbon::create($year, $month, $i)->toDateString();
                                            $record = $attendances->where('staff_id', $s->id)->where('date', $date)->first();
                                            $status = $record ? $record->status : '';

                                            $isToday = $date === \Carbon\Carbon::today()->toDateString();
                                            $canEditToday = true;
                                            if($isToday && $record){
                                                $markedTime = \Carbon\Carbon::parse($record->created_at);
                                                $canEditToday = $markedTime->addHours(4)->isFuture();
                                            }

                                            // ✅ Joining date check
                                            $joiningDate = $s->joining_date ? \Carbon\Carbon::parse($s->joining_date)->toDateString() : null;
                                            $beforeJoining = $joiningDate && $date < $joiningDate;
                                        @endphp
                                        <td class="{{ $beforeJoining ? 'disabled-cell' : '' }}" title="{{ $beforeJoining ? 'Joined after this date' : '' }}">
                                            <div class="status-checkboxes" 
                                                 data-staff="{{ $s->id }}" 
                                                 data-date="{{ $date }}" 
                                                 data-joining="{{ $joiningDate }}">
                                                <label><input type="checkbox" class="status-box" value="present" {{ $status=='present' ? 'checked' : '' }} {{ (!$canEditToday || $beforeJoining) ? 'disabled' : '' }}> P</label>
                                                <label><input type="checkbox" class="status-box" value="absent"  {{ $status=='absent'  ? 'checked' : '' }} {{ (!$canEditToday || $beforeJoining) ? 'disabled' : '' }}> A</label>
                                                <label><input type="checkbox" class="status-box" value="leave"   {{ $status=='leave'   ? 'checked' : '' }} {{ (!$canEditToday || $beforeJoining) ? 'disabled' : '' }}> L</label>
                                            </div>
                                        </td>
                                    @endfor
                                    <td>
                                        <a href="{{ route('attendence.view', ['id'=>$s->id,'month'=>$month,'year'=>$year]) }}" class="btn btn-info btn-sm">View</a>
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
            checkboxes.forEach(b => { if(b !== box) b.checked = false; });

            // Send update to server
            fetch('{{ route("attendence.updateStatus") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ staff_id, date, status })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'error'){
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
$(document).ready(function(){
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

<style>
.table-responsive { overflow:auto; }
.sticky-col { position: sticky; left: 0; background: #fff; z-index: 2; }
thead .sticky-col { z-index: 3; }
.sticky-header { position: sticky; top: 0; background: #fff; z-index: 3; }
.sticky-controls { position: sticky; top:0; z-index: 4; background:#fff; padding:5px; }

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
</style>

@endsection
