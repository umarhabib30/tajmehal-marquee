@extends('layouts.admin')
@section('content')
<div class="container mt-4">

    {{-- Page Header --}}
    <div class="section-block" id="attendanceReport">
        <h3 class="section-title">{{ $staff->name }} - Attendance Report ({{ now()->format('F Y') }})</h3>
        <p>Edit Attendance Details</p>
    </div>

    {{-- Card --}}
    <div class="card">
        <h5 class="card-header">Attendance List</h5>
        <div class="card-body">

            {{-- Success / Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            {{-- Attendance Table --}}
            <div class="table-responsive">
                <table class="table table-bordered text-center table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Entry</th>
                            <th>Exit</th>
                            <th>Worked (hrs)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $a)
                            @php
                                $worked = $a->entry_time && $a->exit_time
                                    ? \Carbon\Carbon::parse($a->entry_time)
                                        ->diffInMinutes(\Carbon\Carbon::parse($a->exit_time)) / 60
                                    : 0;
                            @endphp
                            <tr data-id="{{ $a->id }}">
                                <td>{{ \Carbon\Carbon::parse($a->date)->format('d M') }}</td>
                                <td>{{ $a->entry_time ?? '-' }}</td>
                                <td>{{ $a->exit_time ?? '-' }}</td>
                                <td class="worked">{{ number_format($worked,2) }}</td>
                                <td>{{ ucfirst($a->status) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info editRow">Edit</button>
                                    <button class="btn btn-sm btn-danger deleteRow">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

{{-- Scripts --}}
<script>
// Inline Edit via Prompt
document.querySelectorAll('.editRow').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
        const row = btn.closest('tr');
        const id = row.dataset.id;

        const entry = row.children[1].textContent==='-'?'':row.children[1].textContent;
        const exit  = row.children[2].textContent==='-'?'':row.children[2].textContent;
        const status= row.children[4].textContent.toLowerCase();

        const newEntry  = prompt('Edit Entry Time (HH:MM:SS)', entry);
        const newExit   = prompt('Edit Exit Time (HH:MM:SS)', exit);
        const newStatus = prompt('Edit Status (present/leave/absent)', status);

        if(newEntry!==null && newExit!==null && newStatus!==null){
            try{
                const res = await fetch(`{{ url('admin/attendence/update') }}/${id}`,{
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN':'{{ csrf_token() }}',
                        'Content-Type':'application/json',
                        'Accept':'application/json'
                    },
                    body: JSON.stringify({
                        entry_time: newEntry || null,
                        exit_time: newExit || null,
                        status: newStatus
                    })
                });
                const data = await res.json();
                alert(data.message);

                // Update table
                row.children[1].textContent = newEntry || '-';
                row.children[2].textContent = newExit || '-';
                row.children[4].textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                // Recalculate worked hours
                const worked = newEntry && newExit
                    ? (new Date(`1970-01-01T${newExit}Z`) - new Date(`1970-01-01T${newEntry}Z`))/3600000
                    : 0;
                row.children[3].textContent = worked.toFixed(2);

            }catch(err){
                console.error(err);
                alert('Failed to update attendance');
            }
        }
    });
});

// Delete attendance row
document.querySelectorAll('.deleteRow').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
        if(!confirm('Are you sure you want to delete this attendance entry?')) return;
        const row = btn.closest('tr');
        const id = row.dataset.id;

        try{
            const res = await fetch(`{{ url('admin/attendence/delete') }}/${id}`,{
                method:'DELETE',
                headers:{ 
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Accept':'application/json'
                }
            });
            const data = await res.json();
            alert(data.message);
            row.remove();
        }catch(err){
            console.error(err);
            alert('Failed to delete attendance');
        }
    });
});
</script>
@endsection
