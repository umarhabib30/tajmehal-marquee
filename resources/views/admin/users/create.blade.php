@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-10">
            <div class="card">
                <h5 class="card-header">Create staff user</h5>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <h6 class="mt-4">Module permissions</h6>
                        @include('admin.users._permission_matrix', [
                            'modules' => $modules,
                            'actions' => $actions,
                            'permissions' => old('permissions', []),
                        ])
                        <button type="submit" class="btn btn-primary mt-3">Create user</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light mt-3">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
