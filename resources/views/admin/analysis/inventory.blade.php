@extends('layouts.admin')

@section('content')
<style>
    :root { --main-color: #29166f; }
    .container-fluid { font-family: sans-serif; }
    h3.fw-bold { color: var(--main-color); }

    .card-header {
        background-color: var(--main-color);
        color: #fff;
        font-weight: 600;
    }

    select.form-select {
        border: 1px solid var(--main-color);
        color: var(--main-color);
        font-weight: 500;
    }

    select.form-select:focus {
        border-color: var(--main-color);
        box-shadow: 0 0 0 0.2rem rgba(41, 22, 111, 0.25);
    }

    table th {
        background-color: var(--main-color);
        color: #fff !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(41, 22, 111, 0.05);
    }

    .card {
        border-top: 3px solid var(--main-color);
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(41, 22, 111, 0.1);
    }

    .filter-card {
        border-top: 3px solid var(--main-color);
    }

    .filter-card .card-body {
        background: linear-gradient(180deg, #fbfaff 0%, #ffffff 100%);
        border-radius: 0 0 8px 8px;
    }

    .filter-inline-wrap {
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .filter-form {
        display: flex;
        flex-wrap: nowrap;
        align-items: flex-end;
        gap: 12px;
        min-width: 1120px;
    }

    .filter-field {
        flex: 1 1 0;
        min-width: 170px;
    }

    .filter-field.actions-field {
        flex: 0 0 auto;
        min-width: 470px;
    }

    .filter-form .form-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.35rem;
    }

    .filter-control {
        width: 100%;
        height: 42px;
        border: 1px solid #cfd4df;
        border-radius: 8px;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .filter-control:focus {
        border-color: var(--main-color);
        box-shadow: 0 0 0 0.2rem rgba(41, 22, 111, 0.16);
    }

    .filter-actions {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
    }

    .filter-btn {
        min-width: 108px;
        height: 42px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .filter-hint {
        font-size: 0.82rem;
        color: #6b7280;
        margin-top: 8px;
    }

    .print-header {
        display: none;
    }

    .print-report-line {
        font-size: 13px;
        font-weight: 600;
        color: #111;
    }

    .table-responsive {
        width: 100%;
    }

    .table-responsive table {
        width: 100%;
    }

    .report-metric {
        border: 1px solid #d9d9d9;
        border-left: 4px solid var(--main-color);
        border-radius: 6px;
        background: #fff;
        padding: 10px 14px;
        margin-bottom: 12px;
    }

    .report-metric .label {
        font-weight: 600;
        color: #444;
    }

    .report-metric .value {
        font-weight: 700;
        color: var(--main-color);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        text-align: left;
    }

    .summary-item {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 10px 12px;
        background: #fafbff;
        display: flex;
        flex-direction: column;
        min-height: 88px;
    }

    .summary-item .label {
        display: block;
        color: #5b6475;
        font-size: 0.86rem;
        margin-bottom: 0;
        line-height: 1.2;
        min-height: 2.1em;
    }

    .summary-item .value {
        color: var(--main-color);
        font-weight: 700;
        font-size: 1rem;
        line-height: 1.2;
        margin-top: 0;
    }

    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }

        /* Hide global admin layout chrome while printing */
        .dashboard-header,
        .nav-left-sidebar,
        .navbar,
        .sidebar-dark {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
        }

        .dashboard-main-wrapper,
        .dashboard-wrapper,
        .dashboard-ecommerce,
        .dashboard-content,
        .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        body {
            background: #fff !important;
            color: #000 !important;
        }

        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .no-print {
            display: none !important;
        }

        .print-header {
            display: block !important;
            margin-bottom: 10px;
        }

        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .card-header {
            background: #f1f1f1 !important;
            color: #000 !important;
            border-bottom: 1px solid #000 !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        table {
            width: 100% !important;
            margin: 0 !important;
        }

        table th {
            background: #f1f1f1 !important;
            color: #000 !important;
            border: 1px solid #000 !important;
        }

        table td {
            border: 1px solid #000 !important;
        }
    }
</style>

<div class="container-fluid">
    <div class="print-header">
        <div class="print-report-line">
            Inventory Analysis | Category: {{ $category }} | Date Range: {{ $startDate }} to {{ $endDate }} | Generated:
            {{ now()->format('Y-m-d h:i A') }}
        </div>
        <div class="print-report-line">
            Total Purchase Amount: Rs {{ number_format($totalPriceIn, 2) }}
        </div>
    </div>

    <div class="d-flex align-items-center mb-3">
        <h3 class="fw-bold mb-0">Inventory Item-wise Analysis</h3>
    </div>

    {{-- <div class="report-metric">
        <span class="label">Total Amount Used for Purchasing:</span>
        <span class="value">Rs {{ number_format($totalPriceIn, 2) }}</span>
    </div> --}}

    <div class="card filter-card mb-4 no-print">
        <div class="card-body">
            <div class="filter-inline-wrap">
            <form method="GET" action="{{ route('admin.analysis.inventory') }}" class="filter-form filter-form-inline">
                <div class="filter-field">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select filter-control">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $cat == $category ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label class="form-label fw-semibold">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control filter-control">
                </div>
                <div class="filter-field">
                    <label class="form-label fw-semibold">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control filter-control">
                </div>
                <div class="filter-field actions-field">
                    <label class="form-label fw-semibold">Actions</label>
                    <div class="filter-actions">
                    <button type="submit" class="btn btn-primary filter-btn">
                        <i class="fa fa-filter"></i> Apply
                    </button>
                    <a href="{{ route('admin.analysis.inventory', ['category' => $category]) }}"
                        class="btn btn-outline-secondary filter-btn">
                        <i class="fa fa-undo"></i> Reset
                    </a>
                    <button type="button" class="btn btn-outline-dark filter-btn" onclick="window.print()">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <button type="button" class="btn btn-outline-success filter-btn" onclick="exportInventoryPdf()">
                        <i class="fa fa-file-pdf"></i> PDF
                    </button>
                    </div>
                </div>
            </form>
            </div>
           
        </div>
    </div>

    <div class="row g-3 no-print">
        <div class="col-md-8 no-print">
            <div class="card">
                <h5 class="card-header">
                    Item Quantity In vs Out ({{ $startDate }} to {{ $endDate }}) - Top 10
                </h5>
                <div class="card-body">
                    <canvas id="inventoryBarChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <h5 class="card-header">Category Summary</h5>
                <div class="card-body">
                    <h4 class="fw-bold" style="color: var(--main-color)">
                        {{ strtoupper($category) }}
                    </h4>
                    <div class="summary-grid mt-3">
                        <div class="summary-item">
                            <span class="label">Purchased Quantity (Stock In)</span>
                            <span class="value">{{ number_format($totalQtyIn, 2) }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Consumed/Issued Quantity (Stock Out)</span>
                            <span class="value">{{ number_format($totalQtyOut, 2) }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Total Purchase Value</span>
                            <span class="value">Rs {{ number_format($totalPriceIn, 2) }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Used Stock Value</span>
                            <span class="value">Rs {{ number_format($totalPriceOut, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <h5 class="card-header">Item-wise Summary (All Items) - {{ $startDate }} to {{ $endDate }}</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0" id="inventorySummaryTable">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Total In</th>
                        <th>Total Out</th>
                        <th>Total Price (₨)</th>
                        <th>Current Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chartMonths as $index => $itemName)
                        <tr>
                            <td>{{ $itemName }}</td>
                            <td>{{ $monthlyBookings[$index] ?? 0 }} {{ $monthlyUnits[$index] ?? '' }}</td>
                            <td>{{ $monthlyPaid[$index] ?? 0 }} {{ $monthlyUnits[$index] ?? '' }}</td>
                            <td>{{ number_format($monthlySales[$index] ?? 0) }}</td>
                            <td>{{ $monthlyPending[$index] ?? 0 }}</td>
                        </tr>
                    @endforeach

                    @if(empty($chartMonths))
                        <tr><td colspan="5" class="text-center text-muted">No data available.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
<script>
const mainColor = '#29166f';
const ctxBar = document.getElementById('inventoryBarChart').getContext('2d');

new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: @json($chartMonthsTop10),
        datasets: [
            {
                label: 'Quantity In',
                data: @json($monthlyBookingsTop10),
                backgroundColor: mainColor,
                borderRadius: 5
            },
            {
                label: 'Quantity Out',
                data: @json($monthlyPaidTop10),
                backgroundColor: 'rgba(41, 22, 111, 0.3)',
                borderColor: mainColor,
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: mainColor }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: mainColor },
                grid: { color: 'rgba(41,22,111,0.1)' }
            },
            x: {
                ticks: { color: mainColor },
                grid: { color: 'rgba(41,22,111,0.05)' }
            }
        }
    }
});

function exportInventoryPdf() {
    if (!window.jspdf || !window.jspdf.jsPDF || typeof window.jspdf.jsPDF !== 'function') {
        alert('PDF library failed to load. Please refresh and try again.');
        return;
    }

    const jsPDF = window.jspdf.jsPDF;
    const doc = new jsPDF('p', 'mm', 'a4');
    const pageWidth = doc.internal.pageSize.getWidth();
    const generatedAt = new Date().toLocaleString();
    const heading = 'Inventory Analysis';
    const subtitle = `Category: {{ $category }} | Date: {{ $startDate }} to {{ $endDate }} | Generated: ${generatedAt}`;
    const totalPurchaseText = `Total Purchase Amount: Rs ${Number({{ (float) $totalPriceIn }}).toLocaleString()}`;

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(12);
    doc.text(heading, 14, 12);

    doc.setFont('helvetica', 'normal');
    doc.setFontSize(9);
    doc.text(doc.splitTextToSize(subtitle, pageWidth - 28), 14, 18);
    doc.setFont('helvetica', 'bold');
    doc.text(totalPurchaseText, 14, 23);

    doc.autoTable({
        html: '#inventorySummaryTable',
        startY: 28,
        theme: 'grid',
        styles: {
            fontSize: 9,
            cellPadding: 2
        },
        headStyles: {
            fillColor: [41, 22, 111],
            textColor: [255, 255, 255]
        },
        margin: {
            left: 8,
            right: 8
        },
        tableWidth: 'auto'
    });

    doc.save(`inventory-analysis-{{ strtolower($category) }}-{{ $startDate }}-to-{{ $endDate }}.pdf`);
}
</script>
@endsection
