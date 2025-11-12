@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h4>{{ $heading }}</h4>

    <form action="{{ route('admin.salary.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="staff_id">Select Staff</label>
            <select name="staff_id" class="form-control" required>
                <option value="">-- Choose Staff --</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->role }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Month / Year</label>
            <div class="d-flex flex-column flex-sm-row gap-2"> {{-- stacks on mobile --}}
                <select name="month" class="form-control mb-2 mb-sm-0" required>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
                <select name="year" class="form-control" required>
                    @foreach(range(date('Y')-2, date('Y')+2) as $y)
                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Total Absent Days</label>
            <input type="number" id="total_absent" class="form-control" value="0" readonly>
        </div>

        <div class="form-group">
            <label>Total Deduction Amount</label>
            <input type="number" id="total_deduction" name="total_deduction" class="form-control" step="0.01" value="0" required>
            <small class="text-muted">You can manually input the total deduction amount for salary.</small>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
            <button type="submit" class="btn btn-success ">Generate Salary</button>
            <a href="{{ route('admin.salary.index') }}" class="btn btn-primary  ">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    // Fetch total absent via AJAX
    function fetchAbsentDays() {
        let staffId = $('select[name="staff_id"]').val();
        let month = $('select[name="month"]').val();
        let year = $('select[name="year"]').val();
        if(!staffId || !month || !year) return;

        $.ajax({
            url: '{{ route("admin.salary.getAbsentDays") }}',
            data: { staff_id: staffId, month: month, year: year },
            success: function(res) {
                $('#total_absent').val(res.absent_days);
            }
        });
    }

    $(document).ready(function(){
        $('select[name="staff_id"], select[name="month"], select[name="year"]').change(fetchAbsentDays);
        fetchAbsentDays(); // initial load
    });
</script>
@endsection
