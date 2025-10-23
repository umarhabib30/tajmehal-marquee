@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>{{ $heading }}</h3>
    <form action="{{ route('admin.dish_package.update', $dishPackage->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Package Name</label>
            <input type="text" name="name" id="name" value="{{ $dishPackage->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="dishes" class="form-label">Select Dishes</label>
            <select name="dish_ids[]" id="dishes" class="form-control" multiple required>
                @foreach($dishes as $dish)
                    <option value="{{ $dish->id }}" {{ in_array($dish->id, $dishPackage->dish_ids) ? 'selected' : '' }}>
                        {{ $dish->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="dish_names[]" id="dish_names">

        <button type="submit" class="btn btn-primary">Update Package</button>
    </form>
</div>

<script>
const dishSelect = document.getElementById('dishes');
const dishNamesInput = document.getElementById('dish_names');

function updateDishNames(){
    const names = Array.from(dishSelect.selectedOptions).map(opt => opt.text);
    dishNamesInput.value = JSON.stringify(names);
}

dishSelect.addEventListener('change', updateDishNames);
updateDishNames(); // Initialize
</script>
@endsection
