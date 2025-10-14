@extends('layouts.admin')
@section('content')
    <div class="row">
        <!-- ============================================================== -->
        <!-- Staff Table -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Staff Table</h5>
                <div class="card-body">
                    <a href="{{ route('staff.create') }}" class="btn btn-success mb-3">Add New Staff</a>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered first">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Salary</th>
                                    <th>Experience</th>
                                    <th>Status</th>
                                    <th>Joining Date</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staff as $s)
                                    <tr>
                                        <td>{{ $s->name }}</td>
                                        <td>{{ $s->role }}</td>
                                        <td>{{ $s->email }}</td>
                                        <td>{{ $s->phone }}</td>
                                        <td>{{ $s->salary }}</td>
                                        <td>{{ $s->experience }} yrs</td>
                                        <td>{{ $s->status }}</td>
                                        <td>{{ $s->joining_date }}</td>
                                        <td>
                                            <a href="{{ route('staff.edit', $s->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                        <td>
                                            <form action="{{ route('staff.destroy', $s->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this staff?')">
                                                    Delete
                                                </button>
                                            </form>
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
        <!-- End Staff Table -->
        <!-- ============================================================== -->
    </div>
@endsection
