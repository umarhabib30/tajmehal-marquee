@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">{{ $heading }}</h3>

    <form action="{{ route('admin.dish_package.update', $package->id) }}" method="POST" id="editForm">
        @csrf
        {{-- Using POST (no PUT) per your request --}}

        <!-- Package Name -->
        <div class="mb-3">
            <label for="packageName" class="form-label fw-semibold">Enter Package Name</label>
            <input type="text" name="name" id="packageName" value="{{ $package->name }}" class="form-control form-control-lg" placeholder="Enter package name" required>
        </div>

        <!-- Select Dish -->
        <div class="mb-4">
            <label for="dishDropdown" class="form-label fw-semibold">Select Dishes</label>
            <select id="dishDropdown" class="form-select form-select-lg border border-primary-subtle rounded-3 shadow-sm">
                <option value="">-- Select a dish --</option>
                @foreach ($dishes as $dish)
                    <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Selected Dishes (table-like box) -->
        <div class="border rounded-3 p-3 mb-4 shadow-sm" style="background-color:#fafafa;">
            <h6 class="fw-semibold mb-3">Selected Dishes:</h6>
            <div id="selectedDishes" class="d-flex flex-wrap gap-3"></div>
            <div id="hiddenInputs"></div>
        </div>

        <!-- Buttons -->
        <div class="mt-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-success px-4 py-2">Update Package</button>
            <a href="{{ route('admin.dish_package.index') }}" class="btn btn-secondary px-4 py-2">Back</a>
        </div>
    </form>
</div>

<style>
/* Form input styling */
#dishDropdown {
    width: 100%;
    font-size: 1.05rem;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    border: 1px solid #ced4da;
    background-color: #fff;
    transition: all 0.2s ease-in-out;
}
#dishDropdown:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
}

/* Selected Dish Chips styled like table cells */
.chip-card {
    display: inline-block;
    min-width: 160px;
    border-radius: 8px;
    padding: 10px 16px;
    position: relative;
    background-color: #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    text-align: center;
    font-weight: 500;
    border: 1px solid #dee2e6;
}
.chip-remove {
    position: absolute;
    top: 6px;
    right: 8px;
    cursor: pointer;
    font-weight: 800;
    color: #b00020; /* darker red */
    font-size: 22px; /* slightly larger cross */
    transition: color 0.2s ease-in-out;
}
.chip-remove:hover {
    color: #7a0015; /* even darker on hover */
}

/* Responsive layout */
@media (max-width: 576px) {
    .chip-card {
        min-width: 100%;
    }
}
</style>

<script>
const dishDropdown = document.getElementById('dishDropdown');
const selectedContainer = document.getElementById('selectedDishes');
const hiddenInputs = document.getElementById('hiddenInputs');

// Preloaded selected dish IDs from backend
let selected = @json($selectedDishes->pluck('id')->map(fn($i)=> (string)$i));

// Build name map for all dishes
window.allDishNames = {};
@foreach($selectedDishes as $d)
    window.allDishNames["{{ $d->id }}"] = "{{ addslashes($d->name) }}";
@endforeach
@foreach($dishes as $d)
    window.allDishNames["{{ $d->id }}"] = "{{ addslashes($d->name) }}";
@endforeach

// Remove already-selected from dropdown
document.addEventListener('DOMContentLoaded', () => {
    selected.forEach(id => {
        const optIndex = Array.from(dishDropdown.options).findIndex(o => o.value === id);
        if (optIndex > -1) dishDropdown.remove(optIndex);
    });
    renderSelected();
});

dishDropdown.addEventListener('change', () => {
    const id = dishDropdown.value;
    if (!id || selected.includes(id)) return;

    selected.push(id);

    // Remove selected option
    const optIndex = Array.from(dishDropdown.options).findIndex(o => o.value === id);
    if (optIndex > -1) dishDropdown.remove(optIndex);

    renderSelected();
    dishDropdown.value = ""; // reset dropdown
});

function renderSelected() {
    selectedContainer.innerHTML = '';
    hiddenInputs.innerHTML = '';

    selected.forEach(id => {
        const name = window.allDishNames[id] || 'Unknown';
        const card = document.createElement('div');
        card.className = 'chip-card';
        card.innerHTML = `
            <span class="chip-remove" onclick="removeDish('${id}')">&times;</span>
            <div>${name}</div>
        `;
        selectedContainer.appendChild(card);

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'dishes[]';
        hidden.value = id;
        hiddenInputs.appendChild(hidden);
    });
}

function removeDish(id) {
    selected = selected.filter(x => x !== id);
    const opt = document.createElement('option');
    opt.value = id;
    opt.text = window.allDishNames[id] || 'Unknown';
    dishDropdown.appendChild(opt);
    renderSelected();
}
</script>
@endsection
