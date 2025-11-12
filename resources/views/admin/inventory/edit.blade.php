@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Edit Inventory Item</h5>
                <div class="card-body">

                    {{-- Inventory Edit Form --}}
                    <form action="{{ route('inventory.update', $inventory->id) }}" method="POST" id="inventoryForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Category -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="category">Category <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="Food" {{ old('category', $inventory->category) == 'Food' ? 'selected' : '' }}>Food</option>
                                    <option value="Electronics" {{ old('category', $inventory->category) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                    <option value="Furniture" {{ old('category', $inventory->category) == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="Decoration" {{ old('category', $inventory->category) == 'Decoration' ? 'selected' : '' }}>Decoration</option>
                                    <option value="Crockery" {{ old('category', $inventory->category) == 'Crockery' ? 'selected' : '' }}>Crockery</option>
                                </select>
                            </div>

                            <!-- Item Name -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="name">Item Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                       value="{{ old('name', $inventory->name) }}"
                                       class="form-control"
                                       placeholder="Enter item name"
                                       required>
                            </div>

                            <!-- Quantity Type -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="quantity_type">Quantity Type <span class="text-danger">*</span></label>
                                <select name="quantity_type" id="quantity_type" class="form-control" required>
                                    <option value="">-- Select Quantity Type --</option>
                                    <option value="Pieces" {{ old('quantity_type', $inventory->quantity_type) == 'Pieces' ? 'selected' : '' }}>Pieces</option>
                                    <option value="Kg" {{ old('quantity_type', $inventory->quantity_type) == 'Kg' ? 'selected' : '' }}>Kilograms</option>
                                </select>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity"
                                       value="{{ old('quantity', $inventory->quantity) }}" readonly
                                       class="form-control"
                                       placeholder="Enter quantity"
                                       min="0">
                            </div>

                            <!-- Warranty Period (Shown for Electronics only) -->
                            <div class="form-group col-md-6 mb-3 d-none" id="warranty_group">
                                <label for="warranty_period">Warranty Period (in months)</label>
                                <input type="number" name="warranty_period"
                                       value="{{ old('warranty_period', $inventory->warranty_period) }}"
                                       class="form-control"
                                       placeholder="Enter warranty period if applicable">
                            </div>

                            <!-- Supplier Name (Shown for all except Food) -->
                            <div class="form-group col-md-6 mb-4 d-none" id="supplier_group">
                                <label for="supplier_name">Supplier Name</label>
                                <input type="text" name="supplier_name"
                                       value="{{ old('supplier_name', $inventory->supplier_name) }}"
                                       class="form-control"
                                       placeholder="Enter supplier name">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="form-group col-md-6 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Item
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

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category');
            const warrantyGroup = document.getElementById('warranty_group');
            const supplierGroup = document.getElementById('supplier_group');

            function toggleFields() {
                const category = categorySelect.value;

                // Hide by default
                warrantyGroup.classList.add('d-none');
                supplierGroup.classList.add('d-none');

                // Show warranty only for Electronics
                if (category === 'Electronics') {
                    warrantyGroup.classList.remove('d-none');
                    supplierGroup.classList.remove('d-none');
                }
                // Show supplier for all except Food
                else if (category && category !== 'Food') {
                    supplierGroup.classList.remove('d-none');
                }
            }

            // Trigger on page load and change
            toggleFields();
            categorySelect.addEventListener('change', toggleFields);

            // Optional: Basic client-side check
            document.getElementById('inventoryForm').addEventListener('submit', function(e) {
                const name = document.querySelector('[name="name"]');
                const category = document.querySelector('[name="category"]');
                const quantityType = document.querySelector('[name="quantity_type"]');

                if (!name.value.trim() || !category.value.trim() || !quantityType.value.trim()) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        });
    </script>
@endsection
