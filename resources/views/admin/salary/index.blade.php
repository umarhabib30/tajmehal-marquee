@extends('layouts.admin')

@section('style')
<style>
    :root {
        --salary-primary: #352a86;
        --salary-primary-soft: #f2f0ff;
        --salary-border: #dfe4f2;
        --salary-text: #2f3547;
        --salary-muted: #6b7280;
    }

    .salary-page .card {
        border-top: 3px solid var(--salary-primary);
        border-radius: 10px;
        box-shadow: 0 8px 22px rgba(26, 20, 77, 0.08);
    }

    .salary-page .card-header {
        background: linear-gradient(120deg, #332881 0%, #4b3eb6 100%);
        color: #fff;
        border-radius: 10px 10px 0 0 !important;
        padding: 14px 16px;
    }

    .salary-title {
        font-weight: 700;
        margin: 0;
        font-size: 1.2rem;
        color: #ffffff !important;
    }

    .salary-header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .salary-action-btn {
        min-width: 168px;
        height: 40px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .salary-action-btn.btn-primary {
        background: #ffffff;
        color: var(--salary-primary);
        border-color: #ffffff;
    }

    .salary-action-btn.btn-secondary {
        background: rgba(255, 255, 255, 0.14);
        color: #fff;
    }

    .salary-filter-bar {
        border: 1px solid var(--salary-border);
        border-top: 3px solid var(--salary-primary);
        border-radius: 10px;
        background: linear-gradient(180deg, #fcfbff 0%, #ffffff 100%);
        padding: 12px;
        margin-bottom: 14px;
    }

    .salary-filter-wrap {
        display: flex;
        align-items: stretch;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .salary-filter-left {
        flex: 1 1 58%;
        min-width: 360px;
    }

    .salary-filter-left .salary-filter-controls {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .salary-filter-right {
        flex: 1 1 38%;
        min-width: 320px;
        border: 1px solid #e3e8f6;
        border-radius: 8px;
        background: #f8f9ff;
        padding: 10px 12px;
    }

    .salary-filter-field {
        min-width: 170px;
    }

    .salary-filter-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #56617a;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.35rem;
    }

    .salary-filter-control {
        width: 100%;
        height: 40px;
        border: 1px solid #d7dced;
        border-radius: 8px;
        font-weight: 500;
        color: var(--salary-text);
        background: #fff;
    }

    .salary-filter-control:focus {
        border-color: var(--salary-primary);
        box-shadow: 0 0 0 0.2rem rgba(53, 42, 134, 0.14);
    }

    .salary-filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .salary-filter-btn {
        min-width: 102px;
        height: 40px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .salary-filter-hint {
        margin-top: 8px;
        font-size: 0.82rem;
        color: var(--salary-muted);
    }

    .salary-filter-meta {
        margin-top: 0;
        font-size: 0.82rem;
        color: #465066;
        font-weight: 600;
        line-height: 1.5;
    }

    .salary-filter-meta .title {
        font-size: 0.88rem;
        color: var(--salary-primary);
        font-weight: 700;
    }

    .print-only-report-meta {
        display: none;
    }

    .salary-page .table thead th {
        background: #f3f5fd;
        color: #3f4a62 !important;
        font-weight: 700;
        border-color: #e1e6f3;
        white-space: normal;
        line-height: 1.25;
    }

    .salary-page .table td {
        border-color: #e7ebf6;
        color: #374151;
        vertical-align: middle;
        line-height: 1.25;
    }

    .salary-page .table-responsive {
        overflow-x: hidden;
    }

    .salary-page .dataTables_wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    .salary-page #salaryTable {
        table-layout: fixed;
        width: 100% !important;
    }

    .salary-page #salaryTable th,
    .salary-page #salaryTable td {
        padding: 10px 8px;
        font-size: 0.9rem;
        overflow-wrap: anywhere;
    }

    .salary-page .table tbody tr:hover {
        background: #f8f9ff;
    }

    .salary-row-actions .btn {
        border-radius: 7px;
        font-weight: 600;
        padding: 7px 9px;
        white-space: normal;
    }

    .salary-row-actions .btn-adjustment {
        background: var(--salary-primary);
        border-color: var(--salary-primary);
        color: #fff;
        width: 38px;
        height: 38px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .salary-row-actions .btn-adjustment:hover,
    .salary-row-actions .btn-adjustment:focus {
        background: #241b66;
        border-color: #241b66;
        color: #fff;
    }

    .salary-row-actions .btn-adjustment i {
        font-size: 0.95rem;
        line-height: 1;
    }

    .salary-total-row th {
        background: #edf1ff !important;
        color: #2f2479 !important;
    }

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

    .print-only-report-meta {
        display: block !important;
        margin-bottom: 8px !important;
        border-bottom: 1px solid #cfd3df !important;
        padding-bottom: 6px !important;
        color: #111 !important;
        font-size: 13px !important;
        line-height: 1.4 !important;
    }

    @page {
        size: A4;
        margin: 8mm !important;
    }
}


</style>
@endsection

@section('content')

<div class="row salary-page">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="salary-title mb-2">{{ $heading }}</h4>

                <div class="salary-header-actions no-print mb-2">
                    <a href="{{ route('admin.salary.create') }}" class="btn btn-primary salary-action-btn">
                        <i class="fa fa-plus"></i> Generate New Salary
                    </a>

                    <a href="javascript:void(0)" onclick="printSalaryOnly()" class="btn btn-secondary salary-action-btn">
                        <i class="fa fa-print"></i> Print Salary Table
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- ✅ Month / Year Filter Controls --}}
                <div class="salary-filter-bar no-print">
                    <div class="salary-filter-wrap">
                        <div class="salary-filter-left">
                            <div class="salary-filter-controls">
                                <div class="salary-filter-field">
                                    <label class="salary-filter-label">Month</label>
                                    <select id="selectMonth" class="form-control salary-filter-control">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ (int)$m == (int)$month ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="salary-filter-field">
                                    <label class="salary-filter-label">Year</label>
                                    <select id="selectYear" class="form-control salary-filter-control">
                                        @for ($y = date('Y') - 5; $y <= date('Y'); $y++)
                                            <option value="{{ $y }}" {{ (int)$y == (int)$year ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label class="salary-filter-label">Actions</label>
                                    <div class="salary-filter-actions">
                                        <button id="changeSalaryMonth" class="btn btn-primary salary-filter-btn">
                                            <i class="fa fa-search"></i> Go
                                        </button>
                                        <button id="salaryThisMonth" class="btn btn-outline-secondary salary-filter-btn">
                                            <i class="fa fa-undo"></i> This Month
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="salary-filter-hint">
                                Tip: select month and year, then click Go to load salary report.
                            </div>
                        </div>

                        <div class="salary-filter-right">
                            <div class="salary-filter-meta">
                                <div class="title">The Taj Mahal Marquee</div>
                                <div>Link Road Aqil Shah, Shahpur</div>
                                <div>Contact: 0300-8700443 | 0324-1111963</div>
                                <div>Salary Report - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ====================== PRINT AREA ============================== -->
                <div id="printArea">
                    <div class="print-only-report-meta">
                        <div><strong>The Taj Mahal Marquee</strong></div>
                        <div>Link Road Aqil Shah, Shahpur</div>
                        <div>Contact: 0300-8700443 | 0324-1111963</div>
                        <div><strong>Salary Report - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</strong></div>
                    </div>

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
                                    <th>Bonus</th>
                                    <th>Net Salary</th>
                                    <th class="no-print">Action</th>
                                    <th class="no-print">Adjustment</th>
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

                                        <td>{{ ($s->adjustment_type ?? '') === 'deduction' ? number_format($s->adjustment_amount, 2) : '0.00' }}</td>
                                        <td>{{ ($s->adjustment_type ?? '') === 'bonus' ? number_format($s->adjustment_amount, 2) : '0.00' }}</td>
                                        
                                        <td><strong>{{ number_format($s->net_salary, 2) }}</strong></td>

                                        <td class="no-print salary-row-actions">
                                            <a href="{{ route('admin.salary.show', $s->id) }}" class="btn btn-info btn-sm">
                                                Show
                                            </a>
                                        </td>

                                        <td class="no-print salary-row-actions">
                                            <button type="button"
                                                    class="btn btn-adjustment btn-sm salary-adjustment-btn"
                                                    data-url="{{ route('admin.salary.adjustment', $s->id) }}"
                                                    data-type="{{ $s->adjustment_type ?? 'deduction' }}"
                                                    data-amount="{{ $s->adjustment_amount ?? 0 }}"
                                                    data-note="{{ e($s->adjustment_note ?? '') }}"
                                                    title="Salary adjustment"
                                                    aria-label="Salary adjustment">
                                                <i class="fa fa-sliders-h"></i>
                                            </button>
                                        </td>

                                        <td class="no-print salary-row-actions">
                                            <form action="{{ route('admin.salary.delete', $s->id) }}" method="POST"
                                                  class="d-inline delete-salary-form">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">
                                            No salary records found for this month.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
                                <tr class="table-primary salary-total-row">
                                    <th colspan="7" class="text-end">Total Salary Amount</th>
                                    <th>
                                        {{ number_format($salaries->sum('net_salary'), 2) }}
                                    </th>
                                    <th class="no-print"></th>
                                    <th class="no-print"></th>
                                    <th class="no-print"></th>
                                </tr>
                            </tfoot>
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
        autoWidth: false,
        paging: true,
        ordering: true,
        info: true,
        dom: "<'row align-items-center mb-2'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'f><'col-sm-12 col-md-4'p>>" +
            "rt" +
            "<'row'<'col-sm-12 col-md-5'i>>"
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

    $('.salary-adjustment-btn').on('click', function() {
        const url = $(this).data('url');
        const currentType = $(this).attr('data-type') || 'deduction';
        const currentAmount = $(this).attr('data-amount') || 0;
        const currentNote = $(this).attr('data-note') || '';

        Swal.fire({
            title: 'Salary Adjustment',
            html: `
                <div class="text-left">
                    <label class="d-block mb-1 font-weight-bold">Adjustment Type</label>
                    <select id="salaryAdjustmentType" class="form-control mb-3">
                        <option value="deduction">Deduction</option>
                        <option value="bonus">Bonus</option>
                    </select>

                    <label class="d-block mb-1 font-weight-bold">Amount</label>
                    <input id="salaryAdjustmentAmount" type="number" min="0" step="0.01" class="form-control mb-3" placeholder="Enter amount">

                    <label class="d-block mb-1 font-weight-bold">Note</label>
                    <textarea id="salaryAdjustmentNote" class="form-control" rows="3" placeholder="Optional note"></textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Adjustment',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#352a86',
            didOpen: () => {
                $('#salaryAdjustmentType').val(currentType);
                $('#salaryAdjustmentAmount').val(currentAmount);
                $('#salaryAdjustmentNote').val(currentNote);
            },
            preConfirm: () => {
                const amount = $('#salaryAdjustmentAmount').val();

                if (amount === '' || Number(amount) < 0) {
                    Swal.showValidationMessage('Please enter a valid amount.');
                    return false;
                }

                return {
                    type: $('#salaryAdjustmentType').val(),
                    amount,
                    note: $('#salaryAdjustmentNote').val()
                };
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="adjustment_type" value="${result.value.type}">
                <input type="hidden" name="adjustment_amount" value="${result.value.amount}">
                <input type="hidden" name="adjustment_note" value="">
            `;
            form.querySelector('input[name="adjustment_note"]').value = result.value.note;
            document.body.appendChild(form);
            form.submit();
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
