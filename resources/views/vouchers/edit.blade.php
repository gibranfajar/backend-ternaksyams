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
                                <h3>Edit Voucher</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Voucher Code</label>
                                        <input type="text" class="form-control" name="code"
                                            placeholder="Enter voucher code" value="{{ $voucher->code }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Quota</label>
                                        <input type="text" class="form-control" name="quota" placeholder="Enter quota"
                                            value="{{ $voucher->quota }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- Voucher Type -->
                                    <div class="col-md-6">
                                        <label class="form-label d-block">Voucher Type</label>

                                        <div class="card p-2 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="type"
                                                    id="transaction" value="transaction"
                                                    {{ $voucher->type == 'transaction' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="transaction">
                                                    <strong>Transaction</strong><br>
                                                    <small>Discount on total transaction</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card p-2 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="type"
                                                    id="shipping" value="shipping"
                                                    {{ $voucher->type == 'shipping' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shipping">
                                                    <strong>Shipping</strong><br>
                                                    <small>Discount on shipping cost</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card p-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="type"
                                                    id="product" value="product"
                                                    {{ $voucher->type == 'product' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="product">
                                                    <strong>Product</strong><br>
                                                    <small>Discount on specific products</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Target Audience -->
                                    <div class="col-md-6">
                                        <label class="form-label d-block">Target Audience</label>

                                        <div class="card p-2 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="target"
                                                    id="users" value="users"
                                                    {{ $voucher->target == 'users' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="users">
                                                    <strong>Specific Users</strong><br>
                                                    <small>Target specific users</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card p-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="target"
                                                    id="all" value="all"
                                                    {{ $voucher->target == 'all' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="all">
                                                    <strong>All Users</strong><br>
                                                    <small>Available for everyone</small>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Button muncul kalau pilih Product -->
                                        <button id="btnSelectProduct" type="button"
                                            class="btn btn-primary btn-sm mt-2 d-none" data-bs-toggle="modal"
                                            data-bs-target="#modalProduct">
                                            Select Product
                                        </button>

                                        <!-- Button muncul kalau pilih Specific Users -->
                                        <button id="btnSelectUsers" class="btn btn-primary btn-sm mt-2 d-none"
                                            data-bs-toggle="modal" type="button" data-bs-target="#modalUsers">
                                            Select Users
                                        </button>
                                    </div>
                                </div>

                                {{-- modal product --}}
                                @include('vouchers.modalproductsedit')

                                {{-- modal users --}}
                                @include('vouchers.modalusersedit')

                                <div id="resultSection"
                                    class="mb-3 {{ $voucher->users->count() || $firstProduct ? '' : 'd-none' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 id="titleProducts" class="{{ $firstProduct ? '' : 'd-none' }}">
                                                Selected Products:
                                            </h6>
                                            <ul id="listProducts" class="list-group mb-3">
                                                @foreach ($productVouchers as $vp)
                                                    <li class="list-group-item">
                                                        {{ $vp->name }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div id="hiddenProducts">
                                                @foreach ($productVouchers as $vp)
                                                    <input type="hidden" name="selected_products[]"
                                                        value="{{ $vp->id }}">
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 id="titleUsers" class="{{ $voucher->users->count() ? '' : 'd-none' }}">
                                                Selected Users:
                                            </h6>
                                            <ul id="listUsers" class="list-group">
                                                @foreach ($voucher->users as $vu)
                                                    <li class="list-group-item">
                                                        {{ $vu->name }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div id="hiddenUsers">
                                                @foreach ($voucher->users as $vu)
                                                    <input type="hidden" name="selected_users[]"
                                                        value="{{ $vu->id }}">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="card" style="background-color: #0bb0f109">
                                    <div class="card-body">
                                        <h5 class="card-title">Discount Configuration</h5>
                                        <div class="row mb-3">
                                            <!-- Amount Type -->
                                            <div class="col-md-6">
                                                <label class="form-label d-block">Amount Type</label>

                                                <div class="card p-2 mb-2">
                                                    <div class="form-check d-flex align-items-center">
                                                        <input class="form-check-input me-2" type="radio"
                                                            name="amount_type" id="percent" value="percent"
                                                            {{ $voucher->amount_type == 'percent' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="percent">
                                                            <strong>Percentage</strong><br>
                                                            <small>Discount by percentage</small>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="card p-2">
                                                    <div class="form-check d-flex align-items-center">
                                                        <input class="form-check-input me-2" type="radio"
                                                            name="amount_type" id="value" value="value"
                                                            {{ $voucher->amount_type == 'value' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="value">
                                                            <strong>Fixed Value</strong><br>
                                                            <small>Fixed amount discount</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- amount --}}
                                            <div class="col-md-6">
                                                <label for="" class="form-label">Amount</label>
                                                <input type="number" class="form-control" name="amount"
                                                    value="{{ $voucher->amount }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="" class="form-label">Max Value</label>
                                                <input type="number" class="form-control" name="max_value"
                                                    id="maxValue" disabled value="{{ $voucher->max_value }}">
                                                <small class="text-danger"><em>Only for percentage type and voucher
                                                        transaction</em></small>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="" class="form-label">Min Transaction</label>
                                                <input type="number" class="form-control" name="min_transaction"
                                                    value="{{ $voucher->min_transaction_value }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="" class="form-label">Usage Limit</label>
                                                <input type="number" class="form-control" name="limit"
                                                    value="{{ $voucher->limit }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Start Date</label>
                                        <input type="datetime-local" class="form-control" name="start_date"
                                            value="{{ \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">End Date</label>
                                        <input type="datetime-local" class="form-control" name="end_date"
                                            value="{{ \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d\TH:i') }}">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Voucher</button>
                        </div>

                    </form>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // --- Toggle tombol select product ---
            function toggleBtnProducts() {
                if ($('input[name="type"]:checked').val() === 'product') {
                    $('#btnSelectProduct').removeClass('d-none');
                    toggleResultSection();
                } else {
                    $('#btnSelectProduct').addClass('d-none');
                }
            }

            // --- Toggle tombol select users ---
            function toggleBtnUsers() {
                if ($('input[name="target"]:checked').val() === 'users') {
                    $('#btnSelectUsers').removeClass('d-none');
                    toggleResultSection();
                } else {
                    $('#btnSelectUsers').addClass('d-none');
                }
            }

            // --- Kontrol tampil / sembunyi section hasil ---
            function toggleResultSection() {
                if ($('#listProducts li').length > 0 || $('#listUsers li').length > 0) {
                    $('#resultSection').removeClass('d-none');
                } else {
                    $('#resultSection').addClass('d-none');
                }
            }

            // --- Simpan produk dari modal ---
            $('#saveProduct').on('click', function() {
                let $list = $('#listProducts').empty();
                let $hidden = $('#hiddenProducts').empty();

                let selected = $('#modalProduct input:checked').map(function() {
                    return {
                        id: $(this).val(),
                        name: $(this).siblings('label').text()
                    };
                }).get();

                if (selected.length > 0) {
                    $('#titleProducts').removeClass('d-none');
                    $.each(selected, function(_, item) {
                        $list.append(`<li class="list-group-item">${item.name}</li>`);
                        $hidden.append(
                            `<input type="hidden" name="selected_products[]" value="${item.id}">`
                        );
                    });
                } else {
                    $('#titleProducts').addClass('d-none');
                }

                $('#modalProduct').modal('hide');
                setTimeout(toggleResultSection, 100);
            });

            // --- Simpan users dari modal ---
            $('#saveUsers').on('click', function() {
                let selected = $('#modalUsers input:checked').map(function() {
                    return {
                        id: $(this).val(),
                        name: $(this).data('name')
                    };
                }).get();

                let $list = $('#listUsers').empty();
                let $hidden = $('#hiddenUsers').empty();

                if (selected.length > 0) {
                    $('#titleUsers').removeClass('d-none');
                    $.each(selected, function(_, val) {
                        $list.append(`<li class="list-group-item">${val.name}</li>`);
                        $hidden.append(
                            `<input type="hidden" name="selected_users[]" value="${val.id}">`
                        );
                    });
                } else {
                    $('#titleUsers').addClass('d-none');
                }

                $('#modalUsers').modal('hide');
                setTimeout(toggleResultSection, 100);
            });

            // --- Max Value toggle ---
            function toggleMaxValue() {
                let voucherType = $('input[name="type"]:checked').val();
                let amountType = $('input[name="amount_type"]:checked').val();

                if (voucherType === 'transaction' && amountType === 'percent') {
                    $('#maxValue').prop('disabled', false);
                } else {
                    $('#maxValue').prop('disabled', true).val('');
                }
            }

            // --- Inisialisasi awal ---
            toggleBtnProducts();
            toggleBtnUsers();
            toggleMaxValue();
            toggleResultSection(); // <--- ini penting biar data dari DB langsung muncul

            // --- Event listeners ---
            $('input[name="type"]').on('change', function() {
                toggleBtnProducts();
                toggleMaxValue();
            });

            $('input[name="target"]').on('change', toggleBtnUsers);
            $('input[name="amount_type"]').on('change', toggleMaxValue);

        });
    </script>
@endpush
