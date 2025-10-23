@extends('layouts.admin')
@section('content')
    <div class="container mt-5" id="salary-invoice"
        style="max-width:800px; background:#fff; padding:30px; border:1px solid #ddd; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);">

        <!-- Invoice Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Salary Slip</h2>
                <p class="mb-0"><strong>Taj Mahal Marquee</strong></p>
                <small>{{ \Carbon\Carbon::createFromDate($salary->year, $salary->month, 1)->format('F Y') }}</small>
            </div>
            <button id="printBtn" class="btn btn-primary btn-lg mt-3 mt-md-0" onclick="printInvoice()">Print</button>
        </div>

        <!-- Company & Employee Info -->
        <div class="row mb-4">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <h5>From:</h5>
                <strong>Taj Mahal Marquee</strong><br>
                Shahpur<br>

                Email: info@company.com<br>
                Phone: +123 456 7890
            </div>
            <div class="col-12 col-md-6">
                <h5>To:</h5>
                <strong>{{ $salary->staff->name }}</strong><br>
                Role: {{ $salary->staff->role }}<br>
                Month: {{ \Carbon\Carbon::createFromDate($salary->year, $salary->month, 1)->format('F Y') }}
            </div>
        </div>

        <!-- Salary Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="font-size:1rem;">
                <thead class="thead-dark">
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary</td>
                        <td class="text-right">{{ number_format($salary->basic, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Absent Days ({{ $salary->absent_days }})</td>
                        <td class="text-right text-danger">-
                            {{ number_format($salary->deduction_per_absent * $salary->absent_days, 2) }}</td>
                    </tr>
                    <tr class="table-success">
                        <th>Net Salary</th>
                        <th class="text-right">{{ number_format($salary->net_salary, 2) }}</th>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer & Note -->
        <div class="row mt-5">
            <div class="col-6">
                <p><strong>Prepared by:</strong><br>HR Department</p>
            </div>
            <div class="col-6 text-right">
                <p><strong>Authorized Signature:</strong><br>__________________</p>
            </div>
        </div>

        <div class="text-center mt-4">
            <p style="font-style:italic;">Thank you for your hard work and dedication!</p>
        </div>
    </div>

    <script>
        function printInvoice() {
            // Hide print button before printing
            document.getElementById('printBtn').style.display = 'none';

            const printContents = document.getElementById('salary-invoice').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #salary-invoice,
            #salary-invoice * {
                visibility: visible;
            }

            #salary-invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            #printBtn {
                display: none;
            }
        }

        /* Typography improvements for print */
        #salary-invoice h2 {
            font-size: 2rem;
            font-weight: 700;
        }

        #salary-invoice h5 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        #salary-invoice table th,
        #salary-invoice table td {
            font-size: 1rem;
            padding: 12px;
        }

        #salary-invoice p {
            font-size: 1rem;
        }
    </style>
@endsection
