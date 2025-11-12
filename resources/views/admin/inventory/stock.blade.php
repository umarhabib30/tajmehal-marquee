@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <h5 class="card-header">Stock for: {{ $inventory->name }}</h5>
                <div class="card-body">

                    {{-- SweetAlert success --}}
                    @if (session('success'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: '{{ session('success') }}',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            });
                        </script>
                    @endif

                    {{-- Error Message --}}
                    @if ($errors->has('error'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: '{{ $errors->first('error') }}',
                                });
                            });
                        </script>
                    @endif

                    {{-- Current Stock Summary --}}
                    <div class="mb-4">
                        <h6>Category: <strong>{{ $inventory->category }}</strong></h6>
                        <p>
                            Total In: <strong>{{ $total_in }}</strong> |
                            Total Out: <strong>{{ $total_out }}</strong> |
                            Current Stock: <strong>{{ $current_stock }} {{ $inventory->quantity_type }}</strong>
                        </p>
                    </div>

                    {{-- Add / Take Stock Form --}}
                    <form action="{{ route('inventory.stock.update', $inventory->id) }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="id" value="{{ $inventory->id }}">

                        <div class="row align-items-end gy-3">
                            <div class="col-md-3">
                                <label>Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="add">Add Stock</option>
                                    <option value="take">Take Stock</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control" min="1" required>
                            </div>

                            <div class="col-md-3" id="price_col">
                                <label>Price Per Unit</label>
                                <input type="number" name="price_per_unit" class="form-control">
                            </div>

                            <div class="col-md-3" id="supplier_group">
                                <label>Supplier Name</label>
                                <input type="text" name="supplier_name" class="form-control"
                                    value="{{ old('supplier_name', $inventory->supplier_name ?? '') }}"
                                    placeholder="Enter supplier name">
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-auto ms-auto">
                                <button type="submit" class="btn btn-primary w-100">Update Stock</button>
                            </div>
                        </div>
                    </form>

                    {{-- Stock In Table --}}
                    <h6 class="mt-4 text-success">Stock Added</h6>
                    <div class="table-responsive mb-5">
                        <table class="table table-bordered table-striped">
                            <thead class="table-success">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Quantity Added</th>
                                    <th>Price/Unit</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $stockIn = $stocks->where('quantity_in', '>', 0); @endphp
                                @forelse ($stockIn as $key => $stock)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $stock->date }}</td>
                                        <td>{{ $stock->supplier_name ?? 'N/A' }}</td>
                                        <td>{{ $stock->quantity_in }} {{ $inventory->quantity_type }}</td>
                                        <td>Rs. {{ $stock->price_per_unit }}</td>
                                        <td>Rs. {{ $stock->quantity_in * $stock->price_per_unit }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No stock added yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Stock Out Table --}}
                    <h6 class="mt-4 text-danger">Stock Taken</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-danger">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Quantity Taken</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $stockOut = $stocks->where('quantity_out', '>', 0); @endphp
                                @forelse ($stockOut as $key=> $stock)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $stock->date }}</td>
                                        <td>{{ $stock->quantity_out }} {{ $inventory->quantity_type }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No stock taken yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.querySelector('select[name="type"]');
            const priceInput = document.querySelector('input[name="price_per_unit"]');
            const priceCol = priceInput.closest('.col-md-3');
            const supplierGroup = document.getElementById('supplier_group');
            const category = @json($inventory->category);

            function toggleFields() {
                const type = typeSelect.value;

                // Hide all optional fields first
                priceCol.style.display = 'none';
                supplierGroup.style.display = 'none';

                // For "add" type only
                if (type === 'add') {
                    priceCol.style.display = 'block';
                    priceInput.required = true;

                    // Show supplier for all categories except "take"
                    supplierGroup.style.display = 'block';
                } else {
                    priceInput.required = false;
                    priceInput.value = '';
                }
            }

            toggleFields();
            typeSelect.addEventListener('change', toggleFields);
        });
    </script>
@endsection
