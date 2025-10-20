@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h4 class="mb-3">{{ $heading }}</h4>

    <form action="{{ route('admin.salary.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="staff_id">Select Staff</label>
            <select name="staff_id" class="form-control" required>
                <option value="">-- Choose Staff --</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->role }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="month">Month</label>
            <select name="month" class="form-control" required>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}">{{ Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="year">Year</label>
            <select name="year" class="form-control" required>
                @foreach(range(date('Y')-2, date('Y')+2) as $y)
                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Overtime Rate (Per Hour)</label>
            <input type="number" name="overtime_rate" class="form-control" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Deduction Per Absent</label>
            <input type="number" name="deduction_per_absent" class="form-control" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Deduction Per Leave</label>
            <input type="number" name="deduction_per_leave" class="form-control" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">Generate Salary</button>
        <a href="{{ route('admin.salary.index') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
@endsection
