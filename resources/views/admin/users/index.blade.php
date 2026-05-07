@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    <span>Admin users</span>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Create user</a>
                </h5>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->has('delete'))
                        <div class="alert alert-danger">{{ $errors->first('delete') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered first">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $u)
                                    <tr>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>
                                            @if ($u->isSuperAdmin())
                                                Super admin
                                            @else
                                                Staff
                                            @endif
                                        </td>
                                        <td>
                                            @if ($u->isSuperAdmin())
                                                <span class="text-muted">—</span>
                                            @else
                                                <a href="{{ route('admin.users.edit', $u) }}"
                                                    class="btn btn-primary btn-sm">Edit / permissions</a>
                                                @if ($u->id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST"
                                                        class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-user">Delete</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-user').forEach((button) => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this user?',
                        text: 'This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Done',
                    text: @json(session('success')),
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection
