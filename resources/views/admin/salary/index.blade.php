@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-3">{{ $heading }}</h4>

                <div>
                    <a href="{{ route('admin.salary.create') }}" class="btn btn-primary mb-3">+ Generate New Salary</a>

                    <a href="javascript:void(0)" onclick="printSalaryOnly()" class="btn btn-secondary mb-3">
                        🖨 Print Salary Table
                    </a>
                </div>
            </div>

            <div class="card-body">

                <!-- ====================== PRINT AREA ============================== -->
                <div id="printArea">

                    <!-- BRANDING HEADER -->
                    <header class="pb-2 border-bottom mb-3 d-flex justify-content-between align-items-center print-header">
                        <div class="logo-box">
                            <img src="{{ asset('assets/images/logo.jpg') }}"
                                 alt="Taj Mahal Marquee Logo"
                                 style="height: 90px; width: auto;">
                        </div>

                        <div class="text-end">
                            <h2 class="mb-0 text-primary fw-bold">The Taj Mahal Marquee</h2>
                            <div class="small text-secondary">Link Road Aqil Shah, Shahpur</div>
                            <div class="small">Contact: 0300-8700443 | 0324-1111963</div>
                            <h6 class="fw-semibold mt-1 text-dark">Salary Report</h6>
                        </div>
                    </header>

                    <!-- SALARY TABLE -->
                    <div class="table-responsive">
                        <table id="salaryTable"
                               class="table table-bordered print-table"
                               style="width: 100% !important;">
                            <thead class="thead-light">
                                <tr>
                                    <th>Staff</th>
                                    <th>Role</th>
                                    <th>Month / Year</th>
                                    <th>Basic</th>
                                    <th>Absent</th>
                                    <th>Deduction</th>
                                    <th>Net Salary</th>
                                    <th class="no-print">Action</th>
                                    <th class="no-print">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($salaries as $s)
                                    <tr>
                                        <td>{{ $s->staff->name }}</td>
                                        <td>{{ $s->staff->role }}</td>
                                        <td>{{ \Carbon\Carbon::createFromDate($s->year, $s->month, 1)->format('F Y') }}</td>
                                        <td>{{ number_format($s->basic,2) }}</td>
                                        <td>{{ $s->absent_days }}</td>
                                        <td>{{ number_format($s->deduction_per_absent * $s->absent_days,2) }}</td>
                                        <td><strong>{{ number_format($s->net_salary,2) }}</strong></td>

                                        <td class="no-print">
                                            <a href="{{ route('admin.salary.show', $s->id) }}" class="btn btn-info btn-sm">Show</a>
                                        </td>

                                        <td class="no-print">
                                            <form action="{{ route('admin.salary.delete', $s->id) }}" method="POST" class="d-inline delete-salary-form">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No salary records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
                <!-- ====================== END PRINT AREA ============================== -->

            </div>

        </div>
    </div>
</div>




<!-- ====================== SCRIPTS ============================== -->

<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    // DataTable init
    $('#salaryTable').DataTable({
        responsive: true,
        paging: true,
        ordering: true,
        info: true
    });

    // Delete SweetAlert
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
});


// ==================== NEW PRINT FUNCTION ====================
function printSalaryOnly() {

    // Show ALL rows temporarily (DataTable API)
    let table = $('#salaryTable').DataTable();
    table.page.len(-1).draw();

    setTimeout(() => {

        let printContents = document.getElementById("printArea").innerHTML;
        let originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;

        location.reload();

    }, 500);
}

</script>



<!-- ====================== PRINT CSS ============================== -->
<style>
@media print {

    /* Hide everything except printArea */
    body * {
        visibility: hidden !important;
    }

    #printArea, #printArea * {
        visibility: visible !important;
    }

    /* Expand print area full width */
    #printArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100% !important;
        padding: 0 20px !important;
    }

    /* HIDE unwanted elements */
    .no-print,
    .dataTables_filter,
    .dataTables_length,
    .dataTables_info,
    .dataTables_paginate,
    .btn,
    .card-header {
        display: none !important;
    }

    /* INCREASE FONT SIZE */
    .print-table {
        font-size: 17px !important;
    }

    .print-table th,
    .print-table td {
        padding: 10px !important;
        font-size: 17px !important;
    }

    @page {
        size: A4;
        margin: 10mm !important;
    }
}

</style>

@endsection
