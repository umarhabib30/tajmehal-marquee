@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-10">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card">
                <h5 class="card-header">Edit user: {{ $editUser->name }}</h5>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $editUser) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $editUser->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $editUser->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <h6 class="mt-4">Module permissions</h6>
                        @include('admin.users._permission_matrix', [
                            'modules' => $modules,
                            'actions' => $actions,
                            'permissions' => old('permissions', $editUser->permissions ?? []),
                        ])
                        <button type="submit" class="btn btn-primary mt-3">Save changes</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light mt-3">Back</a>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <h5 class="card-header">Reset password</h5>
                <div class="card-body">
                    <form action="{{ route('admin.users.reset-password', $editUser) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Confirm new password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Reset password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
