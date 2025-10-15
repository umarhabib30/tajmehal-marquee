@extends('layouts.admin')
@section('content')
    <!-- basic form  -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="section-block" id="basicform">
                <h3 class="section-title">Form</h3>
                <p>Enter Dish Details</p>
            </div>
            <div class="card">
                <h5 class="card-header">Dish Form</h5>
                <div class="card-body">

                    {{-- ✅ Display success or error message --}}
                    @if (session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('dishes.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">Dish Name</label>
                                <input id="name" name="name" type="text" class="form-control"
                                    placeholder="Enter Dish Name"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="price_per_head" class="col-form-label">Price per Head</label>
                                <input id="price_per_head" name="price_per_head" type="number" class="form-control"
                                    placeholder="Enter Price per Head"
                                    value="{{ old('price_per_head') }}" required>
                                @error('price_per_head')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">Save Dish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end basic form  -->
@endsection
