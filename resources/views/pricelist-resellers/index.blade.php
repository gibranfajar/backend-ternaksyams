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
                                <h3>Pricelist Resellers</h3>
                            </div>
                            <div class="float-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addModal">
                                    Add Pricelist Reseller
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="myTable" class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center">No</th>
                                        <th>Image</th>
                                        <th style="width: 10%" class="text-center">Active</th>
                                        <th style="width: 10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pricelistResellers as $item)
                                        <tr class="align-middle">

                                            <td class="text-center fw-semibold">{{ $loop->iteration }}</td>

                                            <td class="text-center">
                                                <img src="{{ asset('storage/' . $item->path) }}" alt="thumbnail"
                                                    class="img-thumbnail" style="width:60px;height:auto">
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <form
                                                        action="{{ route('pricelist-resellers.toggleActive', $item->id) }}"
                                                        method="POST" class="m-0 p-0">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input cursor-pointer" type="checkbox"
                                                                onchange="this.form.submit()"
                                                                {{ $item->active ? 'checked' : '' }} title="Toggle Popup">
                                                        </div>
                                                    </form>
                                                </div>
                                            </td>
                                            <!-- ACTION -->
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $item->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <form action="{{ route('pricelist-resellers.destroy', $item->id) }}"
                                                        method="POST" class="m-0 p-0 btn-delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                        </tr>
                                        @include('pricelist-resellers.modaledit')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Promotion Modal -->
    @include('pricelist-resellers.modalcreate')
@endsection
