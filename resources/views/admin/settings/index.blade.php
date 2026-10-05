@extends('layouts.admin')

@section('style')
    <style>
        .settings-page .card {
            border-top: 3px solid #352a86;
            border-radius: 10px;
            box-shadow: 0 8px 22px rgba(26, 20, 77, 0.08);
            overflow: hidden;
        }

        .settings-page .card-header {
            background: linear-gradient(120deg, #332881 0%, #4b3eb6 100%);
            color: #fff;
            border-radius: 10px 10px 0 0 !important;
            padding: 14px 16px;
            border-bottom: 0;
        }

        .settings-page .card-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.05rem;
            color: #fff;
        }

        .settings-page .account-meta {
            background: #f8f7fc;
            border: 1px solid #e8e6f3;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 18px;
        }

        .settings-page .account-meta strong {
            color: #352a86;
        }

        .settings-page .form-group label {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        .settings-page .btn-primary {
            background: #352a86;
            border-color: #352a86;
            font-weight: 600;
            min-width: 160px;
        }

        .settings-page .btn-primary:hover {
            background: #2a216c;
            border-color: #2a216c;
        }
    </style>
@endsection

@section('content')
    <div class="row settings-page">
        <div class="col-xl-6 col-lg-8 col-md-10">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-key mr-2"></i> Change Password</h5>
                </div>
                <div class="card-body">
                    <div class="account-meta">
                        <div><strong>Name:</strong> {{ $user->name }}</div>
                        <div class="mt-1"><strong>Email:</strong> {{ $user->email }}</div>
                    </div>

                    <form action="{{ route('admin.settings.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="current_password">Current password</label>
                            <input type="password" name="current_password" id="current_password"
                                class="form-control @error('current_password') is-invalid @enderror" required
                                autocomplete="current-password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">New password</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Minimum 8 characters.</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm new password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
