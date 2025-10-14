@extends('layouts.admin')

@section('content')
<div class="container-fluid dashboard-content">
    <h2 class="pageheader-title mb-3">Add Attendance</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Staff</label>
                        <select name="staff_id" class="form-select" required>
                            <option value="">Select Staff</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date</label>
                        <input type="date" name="attendance_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Entry Time</label>
                        <input type="time" name="entry_time" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Exit Time</label>
                        <input type="time" name="exit_time" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Half Leave">Half Leave</option>
                            <option value="Leave">Leave</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Save Attendance</button>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
