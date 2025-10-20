@extends('layouts.admin')
@section('content')

<div class="container">
    <h3>{{ isset($dishPackage) ? 'Edit' : 'Create' }} Dish Package</h3>

    <form method="POST" action="{{ isset($dishPackage) ? route('admin.dish_package.update', $dishPackage->id) : route('admin.dish_package.store') }}">
        @csrf
        @if(isset($dishPackage)) @method('PUT') @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>One Dish</label>
                <select name="one_dish[]" class="form-control" multiple>
                    @foreach($dishes as $dish)
                        <option value="{{ $dish->id }}" @if(isset($dishPackage) && in_array($dish->id, $dishPackage->one_dish ?? [])) selected @endif>
                            {{ $dish->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Two Dish</label>
                <select name="two_dish[]" class="form-control" multiple>
                    @foreach($dishes as $dish)
                        <option value="{{ $dish->id }}" @if(isset($dishPackage) && in_array($dish->id, $dishPackage->two_dish ?? [])) selected @endif>
                            {{ $dish->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('admin.dish_package.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

@endsection
