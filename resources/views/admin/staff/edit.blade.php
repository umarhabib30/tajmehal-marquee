@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <h5 class="card-header">{{ $heading }} - {{ $title }}</h5>
            <div class="card-body">
                <a href="{{ route('staff.index') }}" class="btn btn-secondary mb-3">Back</a>

                <form action="{{ route('staff.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $staff->id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $staff->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Role</label>
                            <input type="text" name="role" class="form-control" value="{{ $staff->role }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $staff->email }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $staff->phone }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Salary</label>
                            <input type="number" step="0.01" name="salary" class="form-control" value="{{ $staff->salary }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Experience (Years)</label>
                            <input type="number" name="experience" class="form-control" min="0" value="{{ $staff->experience }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Joining Date</label>
                            <input type="date" name="joining_date" class="form-control" value="{{ $staff->joining_date }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option {{ $staff->status=='Active'?'selected':'' }}>Active</option>
                                <option {{ $staff->status=='Inactive'?'selected':'' }}>Inactive</option>
                                <option {{ $staff->status=='On Leave'?'selected':'' }}>On Leave</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Update Staff</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
<script>toastr.success("{{ session('success') }}");</script>
@endif

@if (session('error'))
<script>toastr.error("{{ session('error') }}");</script>
@endif

@if ($errors->any())
<script>toastr.error("{{ $errors->first() }}");</script>
@endif

@endsection
