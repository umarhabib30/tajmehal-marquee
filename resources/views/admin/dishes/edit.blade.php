@extends('layouts.admin')
@section('content')
    <!-- basic form  -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="section-block" id="basicform">
                <h3 class="section-title">Form</h3>
                <p>Edit Dish Details</p>
            </div>
            <div class="card">
                <h5 class="card-header">Edit Dish</h5>
                <div class="card-body">
                    <form action="{{ route('dishes.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $dish->id }}">

                        <div class="form-group">
                            <label for="name" class="col-form-label">Dish Name</label>
                            <input id="name" name="name" type="text" class="form-control"
                                   value="{{ $dish->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="price_per_head" class="col-form-label">Price per Head</label>
                            <input id="price_per_head" name="price_per_head" type="number" class="form-control"
                                   value="{{ $dish->price_per_head }}" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">Update Dish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end basic form  -->
@endsection
