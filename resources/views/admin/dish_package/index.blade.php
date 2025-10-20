@extends('layouts.admin')
@section('content')

<div class="container">
    <h3>Dish Packages</h3>
    <a href="{{ route('admin.dish_package.create') }}" class="btn btn-primary mb-3">Add New Package</a>

    <table class="table table-bordered" id="packageTable">
        <thead>
            <tr>
                <th>One Dish</th>
                <th>Two Dish</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packages as $package)
                <tr>
                    <td>
                        @if($package->one_dish)
                            @foreach($package->one_dish as $dishId)
                                <span class="badge badge-info">{{ \App\Models\Dish::find($dishId)->name ?? 'N/A' }}</span>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if($package->two_dish)
                            @foreach($package->two_dish as $dishId)
                                <span class="badge badge-success">{{ \App\Models\Dish::find($dishId)->name ?? 'N/A' }}</span>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.dish_package.edit', $package->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.dish_package.destroy', $package->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this package?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- DataTable --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function(){
        $('#packageTable').DataTable();
    });
</script>
@endsection
