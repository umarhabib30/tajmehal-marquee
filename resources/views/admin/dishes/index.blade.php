@extends('layouts.admin')
@section('content')
<div class="row">
    <!-- ============================================================== -->
    <!-- basic table  -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Dishes Table</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered first">
                        <thead>
                            <tr>
                                <th>Dish Name</th>
                                <th>Price per Head</th>
                                <th>Action</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($values as $value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->price_per_head }}</td>
                                    <td>
                                        <a href="{{ route('dishes.edit', $value->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('dishes.destroy', $value->id) }}" 
                                           class="btn btn-danger btn-sm delete-dish">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end basic table  -->
    <!-- ============================================================== -->
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Delete confirmation for GET route
    document.querySelectorAll('.delete-dish').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let url = this.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "This dish will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to GET URL
                    window.location.href = url;
                }
            })
        });
    });

    // Success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session("success") }}',
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    // Error message
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '{{ session("error") }}',
        });
    @endif
});
</script>
@endsection
