@extends('layouts.admin')
@section('content')
    <!-- basic form  -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Edit Dish</h5>
                <div class="card-body">

                    <form action="{{ route('dishes.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $dish->id }}">

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="name" class="col-form-label">Dish Name</label>
                                <input id="name" name="name" type="text" class="form-control"
                                    value="{{ old('name', $dish->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary px-5">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end basic form  -->
@endsection
