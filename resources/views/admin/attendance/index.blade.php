@extends('layouts.admin')

@section('content')
<div class="container-fluid dashboard-content">
    <h2 class="pageheader-title mb-3">Attendance List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <a href="{{ route('attendance.create') }}" class="btn btn-primary mb-3">
                <i class="fa fa-plus"></i> Add Attendance
            </a>
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Staff</th>
                        <th>Date</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $attendance->staff->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->attendance_date }}</td>
                        <td>{{ $attendance->entry_time ?? '-' }}</td>
                        <td>{{ $attendance->exit_time ?? '-' }}</td>
                        <td>{{ $attendance->status }}</td>
                        <td>{{ $attendance->remarks ?? '-' }}</td>
                        <td>
                            <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
