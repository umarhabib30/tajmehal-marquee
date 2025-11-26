@extends('layouts.admin')
@section('content')
<div class="row">
    <!-- ============================================================== -->
    <!-- Staff Table -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Staff</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered first">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                {{-- <th>Email</th> --}}
                                <th>Phone</th>
                                <th>ID Card Number</th>
                                <th>Salary</th>
                                <th>Experience</th>
                                <th>Status</th>
                                <th>Joining Date</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($staff as $s)
                                <tr>
                                    <td>{{ $s->name }}</td>
                                    <td>{{ $s->role }}</td>
                                    {{-- <td>{{ $s->email }}</td> --}}
                                    <td>{{ $s->phone }}</td>
                                    <td>{{ $s->idcardnumber }}</td>
                                    <td>{{ $s->salary }}</td>
                                    <td>{{ $s->experience }} yrs</td>
                                    <td>{{ $s->status }}</td>
                                    <td>{{ $s->joining_date }}</td>
                                    <td>
                                        <a href="{{ route('staff.edit', $s->id) }}"
                                           class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('staff.destroy', $s->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm delete-staff">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No staff found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Staff Table -->
    <!-- ============================================================== -->
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Delete confirmation
    document.querySelectorAll('.delete-staff').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This staff will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
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
