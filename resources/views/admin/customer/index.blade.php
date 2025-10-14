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
                                               onclick="return confirm('Are you sure you want to delete this customer?')"
                                               class="btn btn-danger btn-sm">Delete</a>
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

@endsection
