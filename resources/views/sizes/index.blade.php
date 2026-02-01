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
                                <h3>Sizes</h3>
                            </div>
                            <div class="float-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addSizeModal">
                                    Add Size
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
                                        <th class="text-start">Size</th>
                                        <th class="text-start">Unit</th>
                                        <th style="width: 10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sizes as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-start">{{ $item->label }}</td>
                                            <td class="text-start">{{ $item->unit }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-warning btn-sm me-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editSizeModal{{ $item->id }}">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Edit Size Modal -->
                                        @include('sizes.modaledit')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Size Modal -->
    @include('sizes.modalcreate')
@endsection
