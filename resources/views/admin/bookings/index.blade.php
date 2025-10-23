@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">{{ $heading }}</h3>
        <a href="{{ route('admin.booking.create') }}" class="btn btn-primary">+ Add Booking</a>
    </div>

    <div class="mb-3">
        <label>Filter by Payment Status:</label>
        <select id="paymentFilter" class="form-control w-auto d-inline-block">
            <option value="">All</option>
            <option value="Paid">Paid</option>
            <option value="Pending Amount">Pending Amount</option>
        </select>
    </div>

    <table id="bookingTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Event Type</th>
                <th>Booking Date</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->customer->name }}</td>
                <td>{{ $booking->customer->phone }}</td>
                <td>{{ $booking->event_type }}</td>
                <td>{{ $booking->booking_date->format('d-M-Y') }}</td>
                <td>{{ $booking->status }}</td>
                <td>
                    <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn btn-sm btn-info">Show</a>
                    <button class="btn btn-sm btn-danger delete-btn" data-url="{{ route('admin.booking.destroy', $booking->id) }}">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    var table = $('#bookingTable').DataTable();

    $('#paymentFilter').on('change', function(){
        var val = $(this).val();
        table.column(4).search(val).draw();
    });

    $('.delete-btn').on('click', function(){
        var url = $(this).data('url');
        if(confirm('Are you sure you want to delete this booking?')){
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(res=>res.json()).then(data=>{
                if(data.success) {
                    location.reload();
                } else {
                    alert('Delete failed');
                }
            });
        }
    });
});
</script>
@endsection
