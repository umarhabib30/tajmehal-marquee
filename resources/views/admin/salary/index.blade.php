@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h4 class="mb-3">{{ $heading }}</h4>

    <a href="{{ route('admin.salary.create') }}" class="btn btn-primary mb-3">+ Generate New Salary</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Staff</th>
                <th>Month / Year</th>
                <th>Basic</th>
                <th>Overtime (hrs)</th>
                <th>Absent</th>
                <th>Leave</th>
                <th>Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salaries as $s)
                <tr>
                    <td>{{ $s->staff->name }}</td>
                    <td>{{ Carbon\Carbon::create()->month($s->month)->format('F') }} {{ $s->year }}</td>
                    <td>{{ $s->basic }}</td>
                    <td>{{ $s->overtime_hours }}</td>
                    <td>{{ $s->absent_days }}</td>
                    <td>{{ $s->leave_days }}</td>
                    <td><strong>{{ number_format($s->net_salary, 2) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No salary records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
