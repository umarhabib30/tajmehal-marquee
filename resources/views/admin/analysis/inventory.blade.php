@extends('layouts.admin')

@section('content')
<style>
    :root {
        --main-color: #29166f;
    }

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
</style>

<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <h3 class="fw-bold">Inventory Item-wise Analysis</h3>
        <form method="GET" action="{{ route('admin.analysis.inventory') }}" style="margin-left: 20px">
            <select name="category" class="form-control" onchange="this.form.submit()">
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $cat == $category ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card">
                <h5 class="card-header">Item Quantity In vs Out ({{ $startDate }} to {{ $endDate }})</h5>
                <div class="card-body">
                    <canvas id="inventoryBarChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <h5 class="card-header">Category Summary</h5>
                <div class="card-body text-center">
                    <h4 class="fw-bold" style="color: var(--main-color)">
                        {{ strtoupper($category) }}
                    </h4>
                    <p class="text-muted mb-2">Total Quantity In: <b>{{ $totalPaid }}</b></p>
                    <p class="text-muted mb-2">Total Quantity Out: <b>{{ $totalPending }}</b></p>
                    <p class="text-muted">Total Purchase: <b>₨{{ number_format($totalPurchaseAmt) }}</b></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <h5 class="card-header">Item-wise Summary</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
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
                            <td>{{ $monthlyBookings[$index] }}</td>
                            <td>{{ $monthlyPaid[$index] }}</td>
                            <td>{{ number_format($monthlySales[$index]) }}</td>
                            <td>{{ $monthlyPending[$index] }}</td>
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
<script>
const mainColor = '#29166f';
const ctxBar = document.getElementById('inventoryBarChart').getContext('2d');

new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: @json($chartMonths),
        datasets: [
            {
                label: 'Quantity In',
                data: @json($monthlyBookings),
                backgroundColor: mainColor,
                borderRadius: 5
            },
            {
                label: 'Quantity Out',
                data: @json($monthlyPaid),
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
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y;
                    }
                }
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
</script>
@endsection
