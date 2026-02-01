@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h3>Settings</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Account</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.update', Auth::user()->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ Auth::user()->email }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" class="form-control" name="password">
                                    <small class="form-text fst-italic text-muted">If you want to change your
                                        password</small>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Address Shipper</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('shipper.update') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Brand Name</label>
                                    <input type="text" class="form-control" name="brand_name"
                                        value="{{ $shipper->brand_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Shipper Name</label>
                                    <input type="text" class="form-control" name="shipper_name"
                                        value="{{ $shipper->shipper_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Shipper Phone</label>
                                    <input type="text" class="form-control" name="shipper_phone"
                                        value="{{ $shipper->shipper_phone }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Shipper Email</label>
                                    <input type="email" class="form-control" name="shipper_email"
                                        value="{{ $shipper->shipper_email }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Province</label>
                                            <select name="province" id="province" class="form-control">
                                                <option value="">Select Province</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province['id'] }}"
                                                        {{ $shipper->province == $province['id'] ? 'selected' : '' }}>
                                                        {{ $province['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <select name="city" id="city" class="form-control">
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District</label>
                                            <select name="district" id="district" class="form-control">
                                                <option value="">Select District</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Subdistrict</label>
                                            <select name="subdistrict" id="subdistrict" class="form-control">
                                                <option value="">Select Subdistrict</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Shipper Address</label>
                                    <input type="text" class="form-control" name="shipper_address"
                                        value="{{ $shipper->shipper_address }}" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Address</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const shipper = {
            province: "{{ $shipper->province }}",
            city: "{{ $shipper->city }}",
            district: "{{ $shipper->district }}",
            subdistrict: "{{ $shipper->subdistrict }}"
        };

        function loadCities(provinceId, selectedCity = null) {
            if (!provinceId) return;
            fetch(`/rajaongkir/cities/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    let city = document.getElementById('city');
                    city.innerHTML = '<option value="">Select City</option>';
                    data.forEach(item => {
                        city.innerHTML += `
                    <option value="${item.id}" ${selectedCity == item.id ? 'selected' : ''}>
                        ${item.name}
                    </option>`;
                    });
                });
        }

        function loadDistricts(cityId, selectedDistrict = null) {
            if (!cityId) return;
            fetch(`/rajaongkir/districts/${cityId}`)
                .then(res => res.json())
                .then(data => {
                    let district = document.getElementById('district');
                    district.innerHTML = '<option value="">Select District</option>';
                    data.forEach(item => {
                        district.innerHTML += `
                    <option value="${item.id}" ${selectedDistrict == item.id ? 'selected' : ''}>
                        ${item.name}
                    </option>`;
                    });
                });
        }

        function loadSubdistricts(districtId, selectedSub = null) {
            if (!districtId) return;
            fetch(`/rajaongkir/subdistricts/${districtId}`)
                .then(res => res.json())
                .then(data => {
                    let sub = document.getElementById('subdistrict');
                    sub.innerHTML = '<option value="">Select Subdistrict</option>';
                    data.forEach(item => {
                        sub.innerHTML += `
                    <option value="${item.id}" ${selectedSub == item.id ? 'selected' : ''}>
                        ${item.name}
                    </option>`;
                    });
                });
        }

        // CHANGE EVENTS
        document.getElementById('province').addEventListener('change', function() {
            loadCities(this.value);
        });

        document.getElementById('city').addEventListener('change', function() {
            loadDistricts(this.value);
        });

        document.getElementById('district').addEventListener('change', function() {
            loadSubdistricts(this.value);
        });

        // AUTO LOAD SAAT EDIT
        document.addEventListener('DOMContentLoaded', function() {
            if (shipper.province) {
                loadCities(shipper.province, shipper.city);
                setTimeout(() => {
                    loadDistricts(shipper.city, shipper.district);
                    setTimeout(() => {
                        loadSubdistricts(shipper.district, shipper.subdistrict);
                    }, 500);
                }, 500);
            }
        });
    </script>
@endpush
