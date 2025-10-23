@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h4 class="mb-3">{{ $heading }}</h4>

    <a href="{{ route('admin.salary.create') }}" class="btn btn-primary mb-3">+ Generate New Salary</a>

    <div class="table-responsive">
        <table id="salaryTable" class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>Staff</th>
                    <th>Month / Year</th>
                    <th>Basic</th>
                    <th>Absent</th>
                    <th>Deduction</th>
                    <th>Net Salary</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $s)
                    <tr>
                        <td>{{ $s->staff->name }}</td>
                        <td>{{ \Carbon\Carbon::createFromDate($s->year, $s->month, 1)->format('F Y') }}</td>
                        <td>{{ number_format($s->basic,2) }}</td>
                        <td>{{ $s->absent_days }}</td>
                        <td>{{ number_format($s->deduction_per_absent * $s->absent_days,2) }}</td>
                        <td><strong>{{ number_format($s->net_salary,2) }}</strong></td>
                        <td>
                            <a href="{{ route('admin.salary.show', $s->id) }}" class="btn btn-info btn-sm mb-1">Show</a>
                            <form action="{{ route('admin.salary.delete', $s->id) }}" method="POST" class="d-inline delete-salary-form">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No salary records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Include jQuery and DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#salaryTable').DataTable({
        responsive: true,
        order: [[1, 'desc']] // sort by month/year descending
    });

    // SweetAlert2 delete confirmation
    $('.delete-salary-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Are you sure?',
            text: "This salary record will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session("success") }}',
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    // Error message
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '{{ session("error") }}',
        });
    @endif
});
</script>
@endsection
