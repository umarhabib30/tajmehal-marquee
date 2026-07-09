@extends('layouts.admin')

@section('content')
<style>
    :root {
        --main-color: #29166f;
    }

    h3.fw-bold {
        color: var(--main-color);
    }

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
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(41, 22, 111, 0.15);
    }

    .table th, .table thead th {
        color: white !important;
    }

    .card-header {
        border-bottom: none;
    }

    .analysis-filter-card {
        border-top: 3px solid var(--main-color);
    }

    .analysis-filter-card .card-body {
        background: linear-gradient(180deg, #fbfaff 0%, #ffffff 100%);
        border-radius: 0 0 8px 8px;
        padding: 14px 16px;
    }

    .analysis-filter-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .analysis-filter-field {
        min-width: 210px;
    }

    .analysis-filter-label {
        display: block;
        font-size: 0.76rem;
        font-weight: 700;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.35rem;
    }

    .analysis-filter-control {
        width: 100%;
        height: 42px;
        border: 1px solid #cfd4df;
        border-radius: 8px;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .analysis-filter-control:focus {
        border-color: var(--main-color);
        box-shadow: 0 0 0 0.2rem rgba(41, 22, 111, 0.15);
    }

    .analysis-filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .analysis-filter-btn {
        min-width: 98px;
        height: 42px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .analysis-filter-hint {
        margin-top: 8px;
        font-size: 0.82rem;
        color: #6b7280;
    }

    .summary-card,
    .comparison-card {
        margin-top: 16px;
    }

    .summary-card .table-responsive,
    .comparison-card .table-responsive {
        max-height: 330px;
        overflow-y: auto;
    }

    .summary-card .table,
    .comparison-card .table {
        font-size: 12px;
    }

    .summary-card .table th,
    .summary-card .table td,
    .comparison-card .table th,
    .comparison-card .table td {
        padding: 7px;
        vertical-align: middle;
    }

    .summary-card .card-header,
    .comparison-card .card-header {
        padding: 12px 16px;
    }
</style>

<div class="container-fluid">
    <div class="d-flex align-items-center mb-3">
        <h3 class="fw-bold mb-0">Sales & Booking Analysis</h3>
    </div>

    <div class="card analysis-filter-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.analysis.booking') }}" class="analysis-filter-form">
                <div class="analysis-filter-field">
                    <label class="analysis-filter-label">Year</label>
                    <select name="year" class="form-control analysis-filter-control">
                        @foreach(range(now()->year + 5, now()->year - 10) as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="analysis-filter-label">Actions</label>
                    <div class="analysis-filter-actions">
                        <button type="submit" class="btn btn-primary analysis-filter-btn">
                            <i class="fa fa-filter"></i> Apply
                        </button>
                        <a href="{{ route('admin.analysis.booking') }}" class="btn btn-outline-secondary analysis-filter-btn">
                            <i class="fa fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
            {{-- <div class="analysis-filter-hint">
                Tip: select year and click Apply to refresh all charts and summary tables.
            </div> --}}
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card">
                <h5 class="card-header">Monthly Bookings & Sales</h5>
                <div class="card-body">
                    <canvas id="bookingsBarChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <h5 class="card-header">Paid vs Pending</h5>
                <div class="card-body text-center">
                    <canvas id="donutChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 align-items-start">
        <div class="col-md-8">
            <div class="card summary-card">
                <h5 class="card-header">Monthly Summary ({{ $year }})</h5>
                <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Bookings</th>
                        <th>Guests</th>

                        <th>Total Sales (₨)</th>
                        <th>Total Paid (₨)</th>
                        <th>Pending (₨)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chartMonths as $index => $month)
                        <tr>
                            <td>{{ $month }}</td>
                            <td>{{ $monthlyBookings[$index] }}</td>
                            <td>{{ $monthlyGuests[$index] }}</td>

                            <td>{{ number_format($monthlySales[$index]) }}</td>
                            <td>{{ number_format($monthlyPaid[$index]) }}</td>
                            <td>{{ number_format($monthlyPending[$index]) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        </div>

        <div class="col-md-4">
            <div class="card comparison-card">
                <h5 class="card-header">{{ $previousYear }} vs {{ $year }} Bookings</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>{{ $previousYear }}</th>
                                <th>{{ $year }}</th>
                                <th>+/-</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($yearComparisonRows as $row)
                                <tr>
                                    <td>{{ $row['month'] }}</td>
                                    <td>{{ $row['previous_bookings'] }}</td>
                                    <td>{{ $row['current_bookings'] }}</td>
                                    <td class="{{ $row['difference'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $row['difference'] > 0 ? '+' : '' }}{{ $row['difference'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const mainColor = '#29166f';

const ctxBar = document.getElementById('bookingsBarChart').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: @json($chartMonths),
        datasets: [
            {
                label: 'Total Sales (₨)',
                data: @json($monthlySales),
                backgroundColor: mainColor,
                borderRadius: 5,
                yAxisID: 'y1'
            },
            {
                label: 'Bookings',
                data: @json($monthlyBookings),
                backgroundColor: 'rgba(41, 22, 111, 0.3)',
                borderColor: mainColor,
                borderWidth: 2,
                yAxisID: 'y2'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: mainColor }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        let value = context.parsed.y;
                        return label + ': ' + new Intl.NumberFormat().format(value);
                    }
                }
            }
        },
        scales: {
            y1: {
                type: 'linear',
                position: 'left',
                title: { display: true, text: 'Total Sales (₨)', color: mainColor },
                ticks: { color: mainColor },
                grid: { color: 'rgba(41,22,111,0.1)' }
            },
            y2: {
                type: 'linear',
                position: 'right',
                title: { display: true, text: 'Bookings', color: mainColor },
                ticks: { color: mainColor },
                grid: { drawOnChartArea: false }
            },
            x: {
                ticks: { color: mainColor },
                grid: { color: 'rgba(41,22,111,0.05)' }
            }
        }
    }
});

const ctxDonut = document.getElementById('donutChart').getContext('2d');
new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending'],
        datasets: [{
            data: [{{ $totalPaid }}, {{ $totalPending }}],
            backgroundColor: [mainColor, '#ffc107'],
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: mainColor }
            }
        }
    }
});
</script>
@endsection
