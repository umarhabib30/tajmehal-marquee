@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">
                Attendance Management - {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}
            </h5>
            <div class="card-body">

                <!-- Controls -->
                <div class="mb-3 d-flex justify-content-between flex-wrap sticky-controls bg-white" style="top:0; z-index:4;">
                    <div>
                        <label>Select Month:</label>
                        <select id="selectMonth" class="form-control d-inline-block w-auto">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m==$month?'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
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

                    <div>
                        <button id="applyLeave" class="btn btn-warning">Mark Leave</button>
                        <button id="saveAttendance" class="btn btn-success">Save Attendance</button>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="table-responsive" style="max-height:600px; overflow:auto;">
                    <table class="table table-striped table-bordered text-center table-sm mb-0">
                        <thead>
                            <tr>
                                <th class="sticky-col bg-white" style="left:0; z-index:3;">Staff Name</th>
                                @for($i = 1; $i <= $monthDays; $i++)
                                    <th class="sticky-header bg-white">{{ $i }}</th>
                                @endfor
                                <th class="sticky-header bg-white">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $s)
                                <tr>
                                    <td class="sticky-col bg-white" style="left:0; z-index:2;">{{ $s->name }}</td>
                                    @for($i = 1; $i <= $monthDays; $i++)
                                        @php
                                            $date = \Carbon\Carbon::create($year, $month, $i)->toDateString();
                                            $record = $attendances->where('staff_id', $s->id)->where('date', $date)->first();
                                            $entryColor = $record && $record->entry_time ? 'lightblue' : 'white';
                                            $exitColor  = $record && $record->exit_time  ? 'red' : 'white';
                                            $leaveColor = $record && $record->status=='leave' ? 'yellow' : null;
                                        @endphp
                                        <td>
                                            <div class="day-box-wrapper">
                                                <div class="day-box entry" data-staff="{{ $s->id }}" data-date="{{ $date }}" style="background: {{ $leaveColor ?? $entryColor }}"></div>
                                                <div class="day-box exit" data-staff="{{ $s->id }}" data-date="{{ $date }}" style="background: {{ $leaveColor ?? $exitColor }}"></div>
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

<!-- Leave Modal -->
<div class="modal" tabindex="-1" role="dialog" id="leaveModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Leave</h5>
                <button type="button" class="close" onclick="document.getElementById('leaveModal').style.display='none'">&times;</button>
            </div>
            <div class="modal-body">
                <label>Staff:</label>
                <select id="leaveStaff" class="form-control">
                    @foreach($staff as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                <label>Leave From:</label>
                <input type="date" id="leaveStart" class="form-control" value="{{ $date }}">
                <label>Leave To:</label>
                <input type="date" id="leaveEnd" class="form-control" value="{{ $date }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmLeave">Mark Leave</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('leaveModal').style.display='none'">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
let attendanceData = {}; // only track clicked boxes

// Month/year navigation
document.getElementById('changeMonth').addEventListener('click', () => {
    const month = document.getElementById('selectMonth').value;
    const year = document.getElementById('selectYear').value;
    window.location.href = `?month=${month}&year=${year}`;
});
document.getElementById('thisMonth').addEventListener('click', () => {
    const now = new Date();
    window.location.href = `?month=${now.getMonth()+1}&year=${now.getFullYear()}`;
});

// Entry/Exit click
document.querySelectorAll('.day-box-wrapper .day-box').forEach(box => {
    box.addEventListener('click', () => {
        let staff = box.dataset.staff;
        let date  = box.dataset.date;
        let type  = box.classList.contains('entry') ? 'entry' : 'exit';

        // Toggle color
        if(type==='entry') box.style.background = 'lightblue';
        else box.style.background = 'red';

        // Track only user actions
        attendanceData[staff] = attendanceData[staff] || {};
        attendanceData[staff][date] = attendanceData[staff][date] || {};
        attendanceData[staff][date][type] = true;
    });
});

// Save Attendance
document.getElementById('saveAttendance').addEventListener('click', () => {
    for(let staff in attendanceData){
        for(let date in attendanceData[staff]){
            let data = attendanceData[staff][date];
            if(data.entry) sendMark('entry', staff, date);
            if(data.exit) sendMark('exit', staff, date);
        }
    }
    alert('Attendance saved!');
});

// Open Leave Modal
document.getElementById('applyLeave').addEventListener('click', () => {
    document.getElementById('leaveModal').style.display='block';
});

// Mark Leave
document.getElementById('confirmLeave').addEventListener('click', () => {
    let staff = document.getElementById('leaveStaff').value;
    let start = new Date(document.getElementById('leaveStart').value);
    let end   = new Date(document.getElementById('leaveEnd').value);

    for(let d = start; d <= end; d.setDate(d.getDate() + 1)){
        let dateStr = d.toISOString().slice(0,10);
        sendMark('leave', staff, dateStr); // only mark leave
    }

    document.getElementById('leaveModal').style.display='none';
    location.reload();
});

// AJAX send
function sendMark(type, staff_id, date){
    let url = type==='entry' ? '{{ route("attendence.markEntry") }}' :
              type==='exit' ? '{{ route("attendence.markExit") }}' :
              '{{ route("attendence.markLeave") }}';
    
    fetch(url,{
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},
        body: JSON.stringify({ staff_id, date })
    }).then(res=>res.json())
      .then(data=>console.log(data.message))
      .catch(err=>console.error(err));
}
</script>

<style>
.table-responsive { overflow:auto; max-height:600px; }
.sticky-col { position: sticky; left: 0; background: #fff; z-index: 2; }
thead .sticky-col { z-index: 3; }
.sticky-header { position: sticky; top: 0; background: #fff; z-index: 3; }
.sticky-controls { position: sticky; top:0; }
.day-box-wrapper { display:flex; justify-content:center; }
.day-box { width:20px; height:20px; margin:1px; border:1px solid #ccc; cursor:pointer; }
</style>

@endsection
