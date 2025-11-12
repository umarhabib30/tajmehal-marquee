@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Edit Customer</h5>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.update', $customer->id) }}" method="POST" novalidate>
                        @csrf

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">Name</label>
                                <input id="name" name="name" type="text" class="form-control"
                                       value="{{ old('name', $customer->name) }}" required>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email" class="col-form-label">Email</label>
                                <input id="email" name="email" type="email" class="form-control"
                                       value="{{ old('email', $customer->email) }}" required>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="phone" class="col-form-label">Phone</label>
                                <input id="phone" name="phone" type="text" class="form-control"
                                       maxlength="11" value="{{ old('phone', $customer->phone) }}" required>
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="idcardnumber" class="col-form-label">ID Card Number</label>
                                <input id="idcardnumber" name="idcardnumber" type="text" class="form-control"
                                       maxlength="15" value="{{ old('idcardnumber', $customer->idcardnumber) }}" required>
                                @error('idcardnumber') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="address" class="col-form-label">Address</label>
                                <input id="address" name="address" type="text" class="form-control"
                                       value="{{ old('address', $customer->address) }}" required>
                                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div >
                            <button type="submit" class="btn btn-primary px-5">Update Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const phoneEl = document.getElementById('phone');
    phoneEl.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }
    });

    const idEl = document.getElementById('idcardnumber');
    idEl.addEventListener('input', function() {
        let v = this.value.replace(/[^0-9]/g, '');
        if (v.length > 5 && v.length <= 12) {
            v = v.replace(/(\d{5})(\d+)/, "$1-$2");
        }
        if (v.length > 12) {
            v = v.replace(/(\d{5})(\d{7})(\d+)/, "$1-$2-$3");
        }
        this.value = v;
    });
});
</script>
@endsection
