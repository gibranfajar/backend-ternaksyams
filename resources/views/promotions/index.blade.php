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
                                <h3>Promotions</h3>
                            </div>
                            <div class="float-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addPromotionModal">
                                    Add Promotion
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
                                        <th>Thumbnail</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th style="width: 10%" class="text-center">Popup</th>
                                        <th style="width: 10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($promotions as $item)
                                        <tr class="align-middle">

                                            <td class="text-center fw-semibold">{{ $loop->iteration }}</td>

                                            <td class="text-center">
                                                <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="thumbnail"
                                                    class="img-thumbnail" style="width:60px;height:auto">
                                            </td>

                                            <td class="fw-semibold">{{ $item->name }}</td>

                                            <td class="text-center">
                                                {{ Carbon\Carbon::parse($item->start_date)->format('d M Y') }}
                                            </td>

                                            <td class="text-center">
                                                {{ Carbon\Carbon::parse($item->end_date)->format('d M Y') }}
                                            </td>

                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill px-3 {{ $item->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>

                                            <!-- POPUP SWITCH -->
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <form action="{{ route('promotions.togglePopup', $item->id) }}"
                                                        method="POST" class="m-0 p-0">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input cursor-pointer" type="checkbox"
                                                                onchange="this.form.submit()"
                                                                {{ $item->is_popup ? 'checked' : '' }} title="Toggle Popup">
                                                        </div>
                                                    </form>
                                                </div>
                                            </td>

                                            <!-- ACTION -->
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editPromotionModal{{ $item->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                </div>
                                            </td>

                                        </tr>
                                        @include('promotions.modaledit')
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
    @include('promotions.modalcreate')
@endsection
