@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <h5 class="card-header">Add Staff</h5>
            <div class="card-body">
                <a href="{{ route('staff.index') }}" class="btn btn-secondary mb-3">Back</a>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                               <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" type="text" class="form-control" placeholder="Enter Staff Name" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <input name="role" type="text" class="form-control" placeholder="Enter Role" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input name="phone" type="text" class="form-control" placeholder="Enter Phone" required>
                    </div>
                    <div class="form-group">
                        <label>Salary</label>
                        <input name="salary" type="number" class="form-control" placeholder="Enter Salary">
                    </div>
                    <div class="form-group">
                        <label>Experience (Years)</label>
                        <input name="experience" type="number" class="form-control" min="0" placeholder="Enter Experience">
                    </div>
                    <div class="form-group">
                        <label>Joining Date</label>
                        <input name="joining_date" type="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="On Leave">On Leave</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Save Staff</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
