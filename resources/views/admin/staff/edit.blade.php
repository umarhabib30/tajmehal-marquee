@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <h5 class="card-header">Edit Staff</h5>
            <div class="card-body">
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
                             <select name="role" id="" class="form-control">
                                <option value="Manager" @if ($staff->role == 'Manager') selected @endif>Manager</option>
                                <option value="Waiter" @if ($staff->role == 'Waiter') selected @endif>Waiter</option>
                                <option value="Chef" @if ($staff->role == 'Chef') selected @endif>Chef</option>
                                <option value="Cleaner" @if ($staff->role == 'Cleaner') selected @endif>Cleaner</option>
                                <option value="Security" @if ($staff->role == 'Security') selected @endif>Security</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $staff->email }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" maxlength="11" value="{{ $staff->phone }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>ID Card Number</label>
                            <input id="idcardnumber" name="idcardnumber" type="text" class="form-control"
                                   placeholder="xxxxx-xxxxxxx-x" maxlength="15" value="{{ $staff->idcardnumber }}" required>
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
