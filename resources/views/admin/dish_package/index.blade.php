@extends('layouts.admin')
@section('style')
    <style>
        .card {
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* Dish mini-cards */
        .dish-chip {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 0.95rem;
            font-weight: 500;
            color: #333;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease-in-out;
        }

        .dish-chip:hover {
            background-color: #f1f5ff;
            border-color: #93c5fd;
            transform: scale(1.05);
        }
    </style>
@endsection
@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">{{ $heading }}</h3>
            <a href="{{ route('admin.dish_package.create') }}" class="btn btn-primary px-4 py-2 shadow-sm">
                + Add Package
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @forelse ($packages as $package)
                <div class="col-md-6 mb-4"> {{-- bottom gap added --}}
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-gradient text-white rounded-top-4 py-3" style="background: #29166f ; ">
                            <h5 class="mb-0 fw-semibold" style="color: white !important">{{ $package->name }}</h5>
                        </div>

                        <div class="card-body bg-light">
                            <h6 class="text-secondary fw-semibold mb-3">Dishes Included</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse ($package->dishes as $dish)
                                    <div class="dish-chip">
                                        {{ $dish->name }}
                                    </div>
                                @empty
                                    <div class="text-muted fst-italic">No dishes added yet.</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2 pb-3">
                            <a href="{{ route('admin.dish_package.edit', $package->id) }}"
                                class="btn btn-warning text-white px-4 shadow-sm">
                                <i class="fa fa-edit me-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-danger px-4 shadow-sm delete-btn"
                                data-id="{{ $package->id }}"
                                data-url="{{ route('admin.dish_package.destroy', $package->id) }}">
                                <i class="fa fa-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center rounded-3 shadow-sm">
                        No packages created yet.
                    </div>
                </div>
            @endforelse
        </div>
    </div>


@endsection
@section('script')
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.dataset.url;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will permanently delete the package!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Deleted!', 'The package has been removed.',
                                    'success');
                                setTimeout(() => location.reload(), 800);
                            } else {
                                Swal.fire('Error', 'Something went wrong.', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'Request failed.', 'error'));
                });
            });
        });
    </script>
@endsection
