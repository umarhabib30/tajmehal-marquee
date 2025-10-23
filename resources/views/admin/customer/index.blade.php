@extends('layouts.admin')

@section('content')
    
<div class="row">
    <!-- ============================================================== -->
    <!-- Customers Table -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Customers Table</h5>
            <div class="card-body">
                <a href="{{ route('customer.create') }}" class="btn btn-primary mb-3">Add Customer</a>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered first">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>ID Card</th>
                                <th>Address</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($values as $value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ $value->phone }}</td>
                                    <td>{{ $value->idcardnumber }}</td>
                                    <td>{{ $value->address }}</td>
                                    <td>
                                        <a href="{{ route('customer.edit', $value->id) }}"
                                           class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.delete', $value->id) }}" 
                                           class="btn btn-danger btn-sm delete-customer">Delete</a>
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
    <!-- End Customers Table -->
    <!-- ============================================================== -->
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Delete confirmation for GET route
    document.querySelectorAll('.delete-customer').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let url = this.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "This customer will be permanently deleted!",
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
