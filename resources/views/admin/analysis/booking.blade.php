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
</style>

<div class="container-fluid">
    <div class="d-flex  align-items-center mb-4">
        <h3 class="fw-bold">Sales & Booking Analysis</h3>
        <form method="GET" action="{{ route('admin.analysis.booking') }}" style="margin-left: 20px">
            <select name="year" class="form-control" onchange="this.form.submit()">
                @foreach(range(now()->year, now()->year - 10) as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
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

    <div class="card mt-4">
        <h5 class="card-header">Monthly Summary ({{ $year }})</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Bookings</th>
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
