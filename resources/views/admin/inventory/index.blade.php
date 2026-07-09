@extends('layouts.admin')
@section('style')
    <style>
        .inventory-page .card {
            border-top: 3px solid #352a86;
            border-radius: 10px;
            box-shadow: 0 8px 22px rgba(26, 20, 77, 0.08);
            overflow: hidden;
        }

        .inventory-page .card-header {
            background: linear-gradient(120deg, #332881 0%, #4b3eb6 100%);
            color: #fff;
            border-radius: 10px 10px 0 0 !important;
            padding: 14px 16px;
            border-bottom: 0;
        }

        .inventory-title {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
        }

        .inventory-header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .inventory-header-btn {
            min-width: 170px;
            height: 40px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .inventory-header-btn.btn-success {
            background: #ffffff;
            color: #352a86;
            border-color: #ffffff;
        }

        .inventory-header-btn.btn-danger {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }
    </style>
@endsection
@section('content')
    <div class="row inventory-page">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="inventory-title mb-2">Inventory Table</h5>
                    <div class="inventory-header-actions mb-2">
                        <a href="{{ route('inventory.create') }}" class="btn btn-success inventory-header-btn">
                            <i class="fa fa-plus"></i> Add New Item
                        </a>

                        <a href="{{ route('inventory.stock.history') }}" class="btn btn-info inventory-header-btn">
                            <i class="fa fa-history"></i> Backup History / Restore
                        </a>

                        <form id="reset-stock-form" action="{{ route('inventory.stock.reset_all') }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            <button type="button" class="btn btn-danger inventory-header-btn" id="reset-stock-btn">
                                <i class="fa fa-trash"></i> Reset All In/Out Records
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">

                    {{-- Success Message --}}
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

                    {{-- Category Filter --}}
                    <form method="GET" action="{{ route('inventory.index') }}" class="mb-3">
                        <div class="form-group w-25">
                            <select name="category" onchange="this.form.submit()" class="form-control">
                                <option value="">All Categories</option>
                                <option value="Food" {{ request('category') == 'Food' ? 'selected' : '' }}>Food</option>
                                <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="Furniture" {{ request('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="Decoration" {{ request('category') == 'Decoration' ? 'selected' : '' }}>Decoration</option>
                                <option value="Crockery" {{ request('category') == 'Crockery' ? 'selected' : '' }}>Crockery</option>
                            </select>
                        </div>
                    </form>

                    {{-- Inventory Table --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered first">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Quantity Type</th>
                                    <th>Stock</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventories as $inv)
                                    <tr>
                                        <td>{{ $inv->id }}</td>
                                        <td>{{ $inv->name }}</td>
                                        <td>{{ $inv->category }}</td>
                                        <td>{{ $inv->quantity }}</td>
                                        <td>{{ $inv->quantity_type }}</td>
                                        <td>
                                            <a href="{{ route('inventory.stock', $inv->id) }}" class="btn btn-info btn-sm">Stock</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('inventory.edit', $inv->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                        <td>
                                            <form id="delete-form-{{ $inv->id }}"
                                                  action="{{ route('inventory.destroy', $inv->id) }}"
                                                  method="POST"
                                                  style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="btn btn-danger btn-sm delete-btn"
                                                        data-id="{{ $inv->id }}">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($inventories->isEmpty())
                                    <tr>
                                        <td colspan="10" class="text-center">No inventory items found.</td>
                                    </tr>
                                @endif
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
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + itemId).submit();
                        }
                    });
                });
            });

            const resetButton = document.getElementById('reset-stock-btn');
            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Reset all stock records?',
                        html: `
                            <div style="text-align:left;">
                                <p>This will delete <b>all inventory stock in/out records</b> for all items.</p>
                                <p>Inventory items will remain, and deleted records will be saved in backup history.</p>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, reset all records',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('reset-stock-form').submit();
                        }
                    });
                });
            }

        });
    </script>
@endsection
