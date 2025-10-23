@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 p-4">
        <h3 class="mb-4 fw-bold text-primary">{{ $heading }}</h3>

        <form action="{{ url('admin/dish_package') }}" method="POST" id="packageForm">
            @csrf

            <!-- Package Name -->
            <div class="mb-4">
                <label for="packageName" class="form-label fw-semibold">Enter Package Name</label>
                <input type="text" name="name" id="packageName" class="form-control form-control-lg"
                       placeholder="e.g., one dish" required>
            </div>

            <!-- Select Dish -->
<div class="mb-4">
    <label for="dishDropdown" class="form-label fw-semibold">Select Dishes</label>
    <div class="position-relative">
        <select id="dishDropdown" class="form-select form-select-lg w-100 px-3 py-2 border border-primary-subtle rounded-3 shadow-sm">
            <option value="">-- Select a dish --</option>
            @foreach ($dishes as $dish)
                <option value="{{ $dish->id }}">{{ $dish->name }}</option>
            @endforeach
        </select>
    </div>
</div>

            <!-- Selected Dishes -->
            <div class="mb-4">
                <div class="p-3 border rounded-3 bg-light">
                    <div id="selectedDishes" class="d-flex flex-wrap gap-3"></div>
                    <div id="hiddenInputs"></div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-success px-5 py-2">Save Package</button>
                <a href="{{ route('admin.dish_package.index') }}" class="btn btn-primary px-5 py-2">Back</a>
            </div>
        </form>
    </div>
</div>

<style>
/* --- Responsive Full Page Look --- */
body {
    background-color: #f8f9fa;
}
.card {
    max-width: 900px;
    margin: 0 auto;
}
.chip-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 10px 16px;
    position: relative;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    min-width: 140px;
    text-align: center;
    transition: 0.2s ease;
}
.chip-card:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
.chip-remove {
    position: absolute;
    top: 4px;
    right: 10px;
    cursor: pointer;
    font-size: 18px;
    color: #dc3545;
    font-weight: bold;
    line-height: 1;
}
.chip-remove:hover {
    color: #a71d2a;
}
@media (max-width: 768px) {
    .chip-card {
        min-width: 120px;
        font-size: 14px;
    }
    .btn {
        width: 48%;
    }
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

}
</style>

<script>
const dishDropdown = document.getElementById('dishDropdown');
const selectedContainer = document.getElementById('selectedDishes');
const hiddenInputs = document.getElementById('hiddenInputs');
let selected = [];

// preload dish map
window.allDishNames = {
    @foreach ($dishes as $dish)
        "{{ $dish->id }}": "{{ addslashes($dish->name) }}",
    @endforeach
};

// select dish
dishDropdown.addEventListener('change', () => {
    const id = dishDropdown.value;
    if (!id || selected.includes(id)) return;

    selected.push(id);
    const opt = dishDropdown.querySelector(`option[value="${id}"]`);
    if (opt) opt.remove();

    renderSelected();
});

// render selected dishes
function renderSelected() {
    selectedContainer.innerHTML = '';
    hiddenInputs.innerHTML = '';

    selected.forEach(id => {
        const name = window.allDishNames[id] || 'Unknown';
        const card = document.createElement('div');
        card.className = 'chip-card';
        card.innerHTML = `
            <span class="chip-remove" onclick="removeDish('${id}')">&times;</span>
            <span class="fw-semibold">${name}</span>
        `;
        selectedContainer.appendChild(card);

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'dishes[]';
        hidden.value = id;
        hiddenInputs.appendChild(hidden);
    });
}

// remove dish and return to dropdown
function removeDish(id) {
    selected = selected.filter(x => x !== id);
    const opt = document.createElement('option');
    opt.value = id;
    opt.text = window.allDishNames[id];
    dishDropdown.appendChild(opt);
    renderSelected();
}
</script>
@endsection
