@extends('layouts.admin')

@section('style')
<style>
@media print {

    /* Reset default spacing */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        background: #fff !important;
    }

    /* Hide everything except printArea */
    body * { visibility: hidden !important; }
    #printArea, #printArea * { visibility: visible !important; }

    /* ✅ IMPORTANT: remove sidebar/content left offset */
    .wrapper,
    .content-wrapper,
    .main-content,
    .page-wrapper,
    .app-content,
    .app-wrapper,
    .container,
    .container-fluid,
    .row,
    [class*="col-"] {
        margin: 0 !important;
        padding: 0 !important;
        left: 0 !important;
        right: 0 !important;
        max-width: 100% !important;
    }

    /* ✅ FIX: stick printArea to the actual page (not inside shifted wrapper) */
    #printArea{
        position: fixed !important;   /* <-- this is the main fix */
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Hide unwanted elements */
    .no-print,
    .dataTables_filter,
    .dataTables_length,
    .dataTables_info,
    .dataTables_paginate,
    .btn,
    .card-header {
        display: none !important;
    }

    /* Table full width */
    .table-responsive { overflow: visible !important; }
    #salaryTable { width: 100% !important; }

    .print-table th,
    .print-table td {
        padding: 8px 10px !important;
        font-size: 15px !important;
    }

    @page {
        size: A4;
        margin: 8mm !important;
    }
}


</style>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-3">{{ $heading }}</h4>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.salary.create') }}" class="btn btn-primary mb-3 no-print">
                        + Generate New Salary
                    </a>

                    <a href="javascript:void(0)" onclick="printSalaryOnly()" class="btn btn-secondary mb-3 no-print">
                        🖨 Print Salary Table
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- ✅ Month / Year Filter Controls --}}
                <div class="mb-3 d-flex justify-content-between flex-wrap bg-white p-2 shadow-sm rounded no-print">
                    <div>
                        <label>Select Month:</label>
                        <select id="selectMonth" class="form-control d-inline-block w-auto">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ (int)$m == (int)$month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endfor
                        </select>

                        <label>Select Year:</label>
                        <select id="selectYear" class="form-control d-inline-block w-auto">
                            @for ($y = date('Y') - 5; $y <= date('Y'); $y++)
                                <option value="{{ $y }}" {{ (int)$y == (int)$year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>

                        <button id="changeSalaryMonth" class="btn btn-primary btn-sm">Go</button>
                        <button id="salaryThisMonth" class="btn btn-secondary btn-sm">This Month</button>
                    </div>
                </div>

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
                            <h6 class="fw-semibold mt-1 text-dark">
                                Salary Report - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
                            </h6>
                        </div>
                    </header>

                    <!-- SALARY TABLE -->
                    <div class="table-responsive">
                        <table id="salaryTable" class="table table-bordered print-table" style="width: 100% !important;">
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
                                        <td>{{ number_format($s->basic, 2) }}</td>
                                        <td>{{ $s->absent_days }}</td>

                                        {{-- ✅ Deduction is already TOTAL in DB --}}
                                        <td>{{ number_format($s->deduction_per_absent, 2) }}</td>

                                        <td><strong>{{ number_format($s->net_salary, 2) }}</strong></td>

                                        <td class="no-print">
                                            <a href="{{ route('admin.salary.show', $s->id) }}" class="btn btn-info btn-sm">
                                                Show
                                            </a>
                                        </td>

                                        <td class="no-print">
                                            <form action="{{ route('admin.salary.delete', $s->id) }}" method="POST"
                                                  class="d-inline delete-salary-form">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            No salary records found for this month.
                                        </td>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    // DataTable init
    const table = $('#salaryTable').DataTable({
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

    // Month/year filter buttons
    $('#changeSalaryMonth').on('click', function() {
        const month = $('#selectMonth').val();
        const year  = $('#selectYear').val();
        window.location.href = `?month=${month}&year=${year}`;
    });

    $('#salaryThisMonth').on('click', function() {
        const now = new Date();
        window.location.href = `?month=${now.getMonth()+1}&year=${now.getFullYear()}`;
    });

});

// ✅ PRINT FIXED FUNCTION (NO body replace, CSS will work)
function printSalaryOnly() {
    let table = $('#salaryTable').DataTable();

    // show all rows before print
    table.page.len(-1).draw();

    setTimeout(() => {
        window.print();

        // restore page size
        table.page.len(10).draw();
    }, 300);
}
</script>

@endsection
