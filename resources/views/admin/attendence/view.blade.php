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

    @php
        $joiningDate = \Carbon\Carbon::parse($staff->joining_date);
        $selectedMonth = \Carbon\Carbon::create($year, $month, 1);
    @endphp

    {{-- Check if joined after selected month --}}
    @if($joiningDate->greaterThan($selectedMonth->endOfMonth()))
        <div class="alert alert-warning text-center">
            This staff member joined later — no attendance record found for this month.
        </div>
    @else

        {{-- Attendance Table --}}
        <div class="table-responsive">
            <table class="table table-bordered text-center table-striped" id="attendanceTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $a)
                        @php
                            $isToday = $a->date == \Carbon\Carbon::today()->toDateString();
                            $canEditToday = true;
                            if($isToday && $a->created_at){
                                $markedTime = \Carbon\Carbon::parse($a->created_at);
                                $canEditToday = $markedTime->addHours(4)->isFuture();
                            }
                        @endphp
                        <tr data-id="{{ $a->id }}" data-status="{{ $a->status }}">
                            <td>{{ \Carbon\Carbon::parse($a->date)->format('d M') }}</td>
                            <td>
                                <div class="status-checkboxes" data-id="{{ $a->id }}">
                                    <label><input type="checkbox" class="status-box" value="present" {{ $a->status=='present'?'checked':'' }} {{ !$canEditToday ? 'disabled' : '' }}> P</label>
                                    <label><input type="checkbox" class="status-box" value="absent" {{ $a->status=='absent'?'checked':'' }} {{ !$canEditToday ? 'disabled' : '' }}> A</label>
                                    <label><input type="checkbox" class="status-box" value="leave" {{ $a->status=='leave'?'checked':'' }} {{ !$canEditToday ? 'disabled' : '' }}> L</label>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger deleteRow">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No attendance record found for this month.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" id="attendanceSummary">Totals: —</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    @endif {{-- joining date check end --}}
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('changeMonth').addEventListener('click', ()=>{
    const selected = document.getElementById('selectMonth').value.split('-');
    const year = selected[0];
    const month = selected[1];
    window.location.href = `{{ url('admin/attendence/view/'.$staff->id) }}?year=${year}&month=${month}`;
});

function updateSummary(){
    let total = 0, present = 0, absent = 0, leave = 0;
    document.querySelectorAll('#attendanceTable tbody tr').forEach(row=>{
        total++;
        const status = row.dataset.status;
        if(status=='present') present++;
        else if(status=='absent') absent++;
        else if(status=='leave') leave++;
    });
    document.getElementById('attendanceSummary').textContent =
        `Totals: ${total} | Present: ${present} | Absent: ${absent} | Leave: ${leave}`;
}

updateSummary();

document.querySelectorAll('.status-checkboxes').forEach(container => {
    const checkboxes = container.querySelectorAll('.status-box');
    checkboxes.forEach(box => {
        box.addEventListener('change', () => {
            checkboxes.forEach(b => { if(b !== box) b.checked = false; });
            const id = container.dataset.id;
            const status = box.checked ? box.value : '';

            const row = container.closest('tr');
            row.dataset.status = status;

            updateSummary();

            fetch('{{ route("attendence.update") }}/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Updated!' : 'Error!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    });
});

document.querySelectorAll('.deleteRow').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        const row = btn.closest('tr');
        const id = row.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: "This attendance entry will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ url("admin/attendence/delete") }}/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type':'application/json'
                    }
                })
                .then(res=>res.json())
                .then(data=>{
                    Swal.fire({
                        icon: data.status === 'success' ? 'success' : 'error',
                        title: data.status === 'success' ? 'Deleted!' : 'Error!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    if(data.status === 'success'){
                        row.remove();
                        updateSummary();
                    }
                });
            }
        });
    });
});
</script>

<style>
.status-checkboxes {
    display: flex;
    justify-content: center;
    gap: 5px;
}
.status-checkboxes input {
    width:14px; height:14px; margin-right:2px;
}
</style>
@endsection
