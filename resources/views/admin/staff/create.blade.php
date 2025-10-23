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
                            <input name="name" type="text" class="form-control" placeholder="Enter Staff Name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Role</label>
                            <input name="role" type="text" class="form-control" placeholder="Enter Role" value="{{ old('role') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Enter Email" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input name="phone" type="text" class="form-control" placeholder="03XXXXXXXXX" maxlength="11" value="{{ old('phone') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>ID Card Number</label>
                            <input id="idcardnumber" name="idcardnumber" type="text" class="form-control"
                                   placeholder="xxxxx-xxxxxxx-x" maxlength="15" value="{{ old('idcardnumber') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Salary</label>
                            <input name="salary" type="number" step="0.01" class="form-control" placeholder="Enter Salary" value="{{ old('salary') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Experience (Years)</label>
                            <input name="experience" type="number" class="form-control" min="0" placeholder="Enter Experience" value="{{ old('experience') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Joining Date</label>
                            <input name="joining_date" type="date" class="form-control" value="{{ old('joining_date') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="Active" {{ old('status')=='Active'?'selected':'' }}>Active</option>
                                <option value="Inactive" {{ old('status')=='Inactive'?'selected':'' }}>Inactive</option>
                                <option value="On Leave" {{ old('status')=='On Leave'?'selected':'' }}>On Leave</option>
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

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const phoneEl = document.querySelector('input[name="phone"]');
    phoneEl.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g,'').slice(0,11);
    });

    const idEl = document.getElementById('idcardnumber');
    idEl.addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '');
        if(v.length > 5 && v.length <= 12) {
            v = v.slice(0,5) + '-' + v.slice(5);
        } else if(v.length > 12) {
            v = v.slice(0,5) + '-' + v.slice(5,12) + '-' + v.slice(12,13);
        }
        this.value = v;
    });
});
</script>
@endsection
