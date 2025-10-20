@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h4>Salary Slip - {{ $salary->staff->name }}</h4>
    <p><strong>Month:</strong> {{ \Carbon\Carbon::createFromDate($salary->year, $salary->month, 1)->format('F Y') }}</p>

    <table class="table table-bordered">
        <tr><th>Basic Salary</th><td>{{ $salary->basic }}</td></tr>
        <tr><th>Overtime Hours</th><td>{{ $salary->overtime_hours }}</td></tr>
        <tr><th>Absent Days</th><td>{{ $salary->absent_days }}</td></tr>
        <tr><th>Leave Days</th><td>{{ $salary->leave_days }}</td></tr>
        <tr><th>Overtime Rate</th><td>{{ $salary->overtime_rate }}</td></tr>
        <tr><th>Deduction per Absent</th><td>{{ $salary->deduction_per_absent }}</td></tr>
        <tr><th>Deduction per Leave</th><td>{{ $salary->deduction_per_leave }}</td></tr>
        <tr class="table-success"><th>Net Salary</th><td><strong>{{ $salary->net_salary }}</strong></td></tr>
    </table>
</div>
@endsection
