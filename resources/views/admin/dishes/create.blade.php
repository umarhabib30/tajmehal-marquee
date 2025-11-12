@extends('layouts.admin')
@section('content')
    <!-- basic form  -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Add Dish</h5>
                <div class="card-body">

                    {{-- ✅ Display success or error message --}}
                    <form action="{{ route('dishes.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="name" class="col-form-label">Dish Name</label>
                                <input id="name" name="name" type="text" class="form-control"
                                    placeholder="Enter Dish Name"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div>
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
