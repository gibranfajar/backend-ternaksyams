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
                                <h3>Create Voucher</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('vouchers.store') }}" method="POST" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Voucher Code</label>
                                        <input type="text" class="form-control" name="code"
                                            placeholder="Enter voucher code">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Quota</label>
                                        <input type="text" class="form-control" name="quota" placeholder="Enter quota">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- Voucher Type -->
                                    <div class="col-md-6">
                                        <label class="form-label d-block">Voucher Type</label>

                                        <div class="card p-2 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="type"
                                                    id="transaction" value="transaction">
                                                <label class="form-check-label" for="transaction">
                                                    <strong>Transaction</strong><br>
                                                    <small>Discount on total transaction</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card p-2 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="type"
                                                    id="shipping" value="shipping">
                                                <label class="form-check-label" for="shipping">
                                                    <strong>Shipping</strong><br>
                                                    <small>Discount on shipping cost</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card p-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="type"
                                                    id="product" value="product">
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
                                                    id="users" value="users">
                                                <label class="form-check-label" for="users">
                                                    <strong>Specific Users</strong><br>
                                                    <small>Target specific users</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card p-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="target"
                                                    id="all" value="all">
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
                                @include('vouchers.modalproducts')

                                {{-- modal users --}}
                                @include('vouchers.modalusers')

                                <!-- List hasil pilihan -->
                                <div id="resultSection" class="mb-3 d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 id="titleProducts" class="d-none">Selected Products:</h6>
                                            <ul id="listProducts" class="list-group mb-3"></ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 id="titleUsers" class="d-none">Selected Users:</h6>
                                            <ul id="listUsers" class="list-group"></ul>
                                        </div>
                                    </div>
                                    <!-- Hidden input container -->
                                    <div id="hiddenProducts"></div>
                                    <div id="hiddenUsers"></div>

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
                                                            name="amount_type" id="percent" value="percent">
                                                        <label class="form-check-label" for="percent">
                                                            <strong>Percentage</strong><br>
                                                            <small>Discount by percentage</small>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="card p-2">
                                                    <div class="form-check d-flex align-items-center">
                                                        <input class="form-check-input me-2" type="radio"
                                                            name="amount_type" id="value" value="value">
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
                                                <input type="number" class="form-control" name="amount">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="" class="form-label">Max Value</label>
                                                <input type="number" class="form-control" name="max_value"
                                                    id="maxValue" disabled>
                                                <small class="text-danger"><em>Only for percentage type and voucher
                                                        transaction</em></small>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="" class="form-label">Min Transaction</label>
                                                <input type="number" class="form-control" name="min_transaction">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="" class="form-label">Usage Limit</label>
                                                <input type="number" class="form-control" name="limit">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Start Date</label>
                                        <input type="datetime-local" class="form-control" name="start_date">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">End Date</label>
                                        <input type="datetime-local" class="form-control" name="end_date">
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
            $('input[name="code"]').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Toggle tombol select product
            $('input[name="type"]').on('change', function() {
                if ($(this).val() === 'product') {
                    $('#btnSelectProduct').removeClass('d-none');
                } else {
                    $('#btnSelectProduct').addClass('d-none');
                }
            });

            // Toggle tombol select users
            $('input[name="target"]').on('change', function() {
                if ($(this).val() === 'users') {
                    $('#btnSelectUsers').removeClass('d-none');
                } else {
                    $('#btnSelectUsers').addClass('d-none');
                }
            });

            // Simpan produk dari modal
            $('#saveProduct').on('click', function() {
                let $list = $('#listProducts').empty();
                let $hidden = $('#hiddenProducts').empty();

                let selected = $('#modalProduct input:checked').map(function() {
                    return {
                        id: $(this).val(), // value (id)
                        name: $(this).siblings('label').text() // teks label (nama produk)
                    };
                }).get();

                if (selected.length > 0) {
                    $('#titleProducts').removeClass('d-none');
                    $.each(selected, function(_, item) {
                        // tampil nama produk
                        $list.append(`<li class="list-group-item">${item.name}</li>`);
                        // hidden input array (isi id)
                        $hidden.append(
                            `<input type="hidden" name="selected_products[]" value="${item.id}">`
                        );
                    });
                } else {
                    $('#titleProducts').addClass('d-none');
                }

                $('#modalProduct').modal('hide');
            });


            // Simpan users dari modal
            $('#saveUsers').on('click', function() {
                let selected = $('#modalUsers input:checked').map(function() {
                    return {
                        id: $(this).val(),
                        name: $(this).data('name') // ambil dari attribute
                    };
                }).get();

                let $list = $('#listUsers').empty();
                let $hidden = $('#hiddenUsers').empty();

                if (selected.length > 0) {
                    $('#titleUsers').removeClass('d-none');
                    $.each(selected, function(_, val) {
                        // tampil nama user
                        $list.append(`<li class="list-group-item">${val.name}</li>`);
                        // hidden input array
                        $hidden.append(
                            `<input type="hidden" name="selected_users[]" value="${val.id}">`
                        );
                    });
                } else {
                    $('#titleUsers').addClass('d-none');
                }

                $('#modalUsers').modal('hide');
            });




            // --- Tambahan kontrol tampil / sembunyi ---
            function toggleResultSection() {
                if ($('#listProducts li').length > 0 || $('#listUsers li').length > 0) {
                    $('#resultSection').removeClass('d-none');
                } else {
                    $('#resultSection').addClass('d-none');
                }
            }

            // Hook ke event save product
            $('#saveProduct').on('click', function() {
                setTimeout(toggleResultSection, 100); // kasih delay kecil biar list sudah terupdate
            });

            // Hook ke event save users
            $('#saveUsers').on('click', function() {
                setTimeout(toggleResultSection, 100);
            });

            function toggleMaxValue() {
                let voucherType = $('input[name="type"]:checked').val();
                let amountType = $('input[name="amount_type"]:checked').val();

                if (voucherType === 'transaction' && amountType === 'percent') {
                    $('#maxValue').prop('disabled', false);
                } else {
                    $('#maxValue').prop('disabled', true).val('');
                }
            }

            // Jalankan saat load
            toggleMaxValue();

            // Event listener
            $('input[name="type"]').on('change', toggleMaxValue);
            $('input[name="amount_type"]').on('change', toggleMaxValue);
        });
    </script>
@endpush
