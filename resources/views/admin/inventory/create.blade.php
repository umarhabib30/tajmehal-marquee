@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Add New Inventory Item</h5>
                <div class="card-body">

                    {{-- Inventory Create Form --}}
                    <form action="{{ route('inventory.store') }}" method="POST" id="inventoryForm">
                        @csrf
                        <div class="row">
                            <!-- Category -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="category">Category <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="Food" {{ old('category') == 'Food' ? 'selected' : '' }} selected>Food</option>
                                    <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>
                                        Electronics</option>
                                    <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>
                                        Furniture</option>
                                    <option value="Decoration" {{ old('category') == 'Decoration' ? 'selected' : '' }}>
                                        Decoration</option>
                                    <option value="Crockery" {{ old('category') == 'Crockery' ? 'selected' : '' }}>Crockery
                                    </option>
                                </select>
                            </div>
                            <!-- Item Name -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="name">Item Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    placeholder="Enter item name" required>
                            </div>



                            <!-- Quantity Type -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="quantity_type">Quantity Type <span class="text-danger">*</span></label>
                                <select name="quantity_type" id="quantity_type" class="form-control" required>
                                    <option value="">-- Select Quantity Type --</option>
                                    <option value="Pieces" {{ old('quantity_type') == 'Pieces' ? 'selected' : '' }}>Pieces
                                    </option>
                                    <option value="Kg" {{ old('quantity_type') == 'Kg' ? 'selected' : '' }}>Kilograms
                                    </option>
                                </select>
                            </div>

                            <!-- Quantity -->
                            {{-- <div class="form-group col-md-6 mb-3">
                            <label for="quantity">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" value="{{ old('quantity') }}" class="form-control" placeholder="Enter quantity" min="0" required>
                        </div> --}}


                            <!-- Warranty Period (Shown for Electronics only) -->
                            <div class="form-group col-md-6 mb-3 d-none" id="warranty_group">
                                <label for="warranty_period">Warranty Period (in months)</label>
                                <input type="number" name="warranty_period" value="{{ old('warranty_period') }}"
                                    class="form-control" placeholder="Enter warranty period if applicable">
                            </div>

                            <!-- Supplier Name (Shown for all except Food) -->
                            <div class="form-group col-md-6 mb-4 d-none" id="supplier_group">
                                <label for="supplier_name">Supplier Name</label>
                                <input type="text" name="supplier_name" value="{{ old('supplier_name') }}"
                                    class="form-control" placeholder="Enter supplier name">
                            </div>

                        </div>
                        <!-- Buttons -->
                        <div class="form-group col-md-6 text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Save Item
                            </button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
