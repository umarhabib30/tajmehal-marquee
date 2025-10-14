@extends('layouts.admin')
@section('content')
    <div class="row">
        <!-- ============================================================== -->
        <!-- Inventory Table -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Inventory Table</h5>
                <div class="card-body">

                    {{-- Add New Item --}}
                    <a href="{{ route('inventory.create') }}" class="btn btn-success mb-3">Add New Item</a>

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
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Purchase Date</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventories as $inv)
                                    <tr>
                                        <td>{{ $inv->name }}</td>
                                        <td>{{ $inv->category }}</td>
                                        <td>{{ $inv->quantity }}</td>
                                        <td>{{ $inv->price }}</td>
                                        <td>{{ $inv->purchase_date }}</td>
                                        <td>{{ $inv->status }}</td>
                                        <td>
                                            <a href="{{ route('inventory.edit', $inv->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                        <td>
                                            <form action="{{ route('inventory.destroy', $inv->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this item?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($inventories->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">No inventory items found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Inventory Table -->
        <!-- ============================================================== -->
    </div>
@endsection
