@extends('layouts.admin')
@section('content')
<div class="container mt-4">

    {{-- Heading --}}
    <h4>{{ $staff->name }} - {{ $heading }} ({{ \Carbon\Carbon::create($year, $month)->format('F Y') }})</h4>

    {{-- Month/Year Selection --}}
    <div class="d-flex mb-3 align-items-center flex-wrap">
        <label for="selectMonth" class="mr-2 mb-2">Select Month:</label>
        <input type="month" id="selectMonth" class="form-control w-auto mb-2" value="{{ sprintf('%04d-%02d', $year, $month) }}">
        <button id="changeMonth" class="btn btn-secondary ml-2 mb-2">View</button>
    </div>

    {{-- Working Hours --}}
    <div class="d-flex mb-3 align-items-center flex-wrap">
        <label for="working_hours" class="mr-2 mb-2">Working Hours per Day:</label>
        <input type="number" id="working_hours" value="8" step="0.5" class="form-control w-25 mb-2">
        <button id="apply_hours" class="btn btn-primary ml-2 mb-2">Apply</button>
    </div>

    {{-- Attendance Table --}}
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Entry</th>
                    <th>Exit</th>
                    <th>Worked (hrs)</th>
                    <th>Overtime (hrs)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $a)
                    @php
                        $worked = $a->entry_time && $a->exit_time
                            ? \Carbon\Carbon::parse($a->entry_time)->diffInMinutes(\Carbon\Carbon::parse($a->exit_time)) / 60
                            : 0;
                    @endphp
                    <tr class="day-row" data-id="{{ $a->id }}" data-worked="{{ $worked }}" data-status="{{ $a->status }}">
                        <td>{{ \Carbon\Carbon::parse($a->date)->format('d M') }}</td>
                        <td><input type="time" value="{{ $a->entry_time }}" class="form-control form-control-sm" disabled></td>
                        <td><input type="time" value="{{ $a->exit_time }}" class="form-control form-control-sm" disabled></td>
                        <td class="worked">{{ number_format($worked, 2) }}</td>
                        <td class="overtime">—</td>
                        <td>
                            <select class="form-control form-control-sm" disabled>
                                <option value="present" {{ $a->status=='present'?'selected':'' }}>Present</option>
                                <option value="leave" {{ $a->status=='leave'?'selected':'' }}>Leave</option>
                                <option value="absent" {{ $a->status=='absent'?'selected':'' }}>Absent</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info editRow">Edit</button>
                            <button class="btn btn-sm btn-success saveRow d-none">Save</button>
                            <button class="btn btn-sm btn-danger deleteRow">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Totals</th>
                    <th id="totalWorked">—</th>
                    <th id="totalOvertime">—</th>
                    <th colspan="2" id="totalSummary">—</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
// Function to calculate totals
function calculateTotals(){
    const normal = parseFloat(document.getElementById('working_hours').value);
    let totalWorked = 0, totalOver = 0, present=0, absent=0, leave=0, totalDays=0;

    document.querySelectorAll('.day-row').forEach(row=>{
        const worked = parseFloat(row.dataset.worked);
        const overtime = Math.max(0, worked - normal);
        row.querySelector('.overtime').textContent = overtime.toFixed(2);

        totalWorked += worked;
        totalOver += overtime;
        totalDays++;

        const status = row.dataset.status;
        if(status=='present') present++;
        else if(status=='absent') absent++;
        else if(status=='leave') leave++;
    });

    document.getElementById('totalWorked').textContent = totalWorked.toFixed(2);
    document.getElementById('totalOvertime').textContent = totalOver.toFixed(2);
    document.getElementById('totalSummary').innerHTML = `
        Total Days: ${totalDays} | Present: ${present} | Absent: ${absent} | Leave: ${leave}
    `;
}

// Initial calculation
calculateTotals();

// Apply working hours button
document.getElementById('apply_hours').addEventListener('click', calculateTotals);

// Change Month button
document.getElementById('changeMonth').addEventListener('click', ()=>{
    const selected = document.getElementById('selectMonth').value.split('-');
    const year = selected[0];
    const month = selected[1];
    window.location.href = `{{ url('admin/attendence/view/'.$staff->id) }}?year=${year}&month=${month}`;
});

// Delegate row actions
document.querySelector('tbody').addEventListener('click', async function(e){
    const row = e.target.closest('tr');
    if(!row) return;
    const id = row.dataset.id;
    const tds = row.querySelectorAll('td');

    // Edit
    if(e.target.classList.contains('editRow')){
        row.querySelectorAll('input, select').forEach(inp => inp.disabled=false);
        row.querySelector('.editRow').classList.add('d-none');
        row.querySelector('.saveRow').classList.remove('d-none');
    }

    // Save
    if(e.target.classList.contains('saveRow')){
        const entry = tds[1].querySelector('input').value;
        const exit  = tds[2].querySelector('input').value;
        const status = tds[5].querySelector('select').value;

        try{
            const res = await fetch(`{{ url('admin/attendence/update') }}/${id}`,{
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Content-Type':'application/json',
                    'Accept':'application/json'
                },
                body: JSON.stringify({entry_time: entry, exit_time: exit, status})
            });
            const data = await res.json();
            alert(data.message);

            const worked = entry && exit ? (new Date(`1970-01-01T${exit}Z`) - new Date(`1970-01-01T${entry}Z`))/3600000 : 0;
            row.dataset.worked = worked;
            row.dataset.status = status;
            tds[3].textContent = worked.toFixed(2);

            row.querySelectorAll('input, select').forEach(inp => inp.disabled=true);
            row.querySelector('.editRow').classList.remove('d-none');
            row.querySelector('.saveRow').classList.add('d-none');

            calculateTotals();
        }catch(err){
            alert('Failed to update attendance');
            console.error(err);
        }
    }

    // Delete
    if(e.target.classList.contains('deleteRow')){
        if(!confirm('Are you sure you want to delete this attendance entry?')) return;
        try{
            const res = await fetch(`{{ url('admin/attendence/delete') }}/${id}`,{
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Content-Type':'application/json',
                    'Accept':'application/json'
                }
            });
            const data = await res.json();
            alert(data.message);
            row.remove();
            calculateTotals();
        }catch(err){
            alert('Failed to delete attendance');
            console.error(err);
        }
    }
});
</script>
@endsection
