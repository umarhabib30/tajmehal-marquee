@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <h5 class="card-header">Add Staff</h5>
            <div class="card-body">
                <a href="{{ route('staff.index') }}" class="btn btn-secondary mb-3">Back</a>

                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" placeholder="Enter Staff Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Role</label>
                            <input name="role" type="text" class="form-control" placeholder="Enter Role" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Enter Email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input name="phone" type="text" class="form-control" placeholder="Enter Phone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Salary</label>
                            <input name="salary" type="number" class="form-control" placeholder="Enter Salary">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Experience (Years)</label>
                            <input name="experience" type="number" class="form-control" min="0" placeholder="Enter Experience">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Joining Date</label>
                            <input name="joining_date" type="date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="On Leave">On Leave</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Save Staff</button>
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
