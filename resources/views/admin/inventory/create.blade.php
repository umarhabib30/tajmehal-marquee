@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Add New Inventory Item</h5>
            <div class="card-body">

                {{-- Show Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Inventory Create Form --}}
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf

                    <!-- Item Name -->
                    <div class="form-group mb-3">
                        <label for="name">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter item name" required>
                    </div>

                    <!-- Category -->
                    <div class="form-group mb-3">
                        <label for="category">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            <option value="Food" {{ old('category') == 'Food' ? 'selected' : '' }}>Food</option>
                            <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                            <option value="Decoration" {{ old('category') == 'Decoration' ? 'selected' : '' }}>Decoration</option>
                            <option value="Crockery" {{ old('category') == 'Crockery' ? 'selected' : '' }}>Crockery</option>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group mb-3">
                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" class="form-control" placeholder="Enter quantity" required>
                    </div>

                    <!-- Price -->
                    <div class="form-group mb-3">
                        <label for="price">Price (per item)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control" placeholder="Enter price">
                    </div>

                    <!-- Purchase Date -->
                    <div class="form-group mb-3">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" class="form-control">
                    </div>

                    <!-- Status -->
                    <div class="form-group mb-4">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Out of Stock" {{ old('status') == 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Save Item</button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
