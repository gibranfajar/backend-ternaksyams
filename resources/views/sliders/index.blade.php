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
                                <h3>Sliders</h3>
                            </div>
                            <div class="float-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addSliderModal">
                                    Add Slider
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
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th style="width: 30%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sliders as $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle text-center">
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                                    width="60" class="rounded">
                                            </td>
                                            <td class="align-middle">{{ $item->title }}</td>
                                            <td class="align-middle">
                                                @if ($item->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-inline-flex align-items-center gap-2">

                                                    {{-- tombol ubah status --}}
                                                    <form action="{{ route('sliders.toggleStatus', $item->id) }}"
                                                        method="POST" class="m-0 p-0 d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if ($item->status)
                                                            <button type="submit" class="btn btn-secondary btn-sm"
                                                                title="Active">
                                                                Set Inactive
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-success btn-sm"
                                                                title="Inactive">
                                                                Set Active
                                                            </button>
                                                        @endif
                                                    </form>

                                                    <!-- Tombol Edit -->
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSliderModal{{ $item->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>

                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('sliders.destroy', $item->id) }}" method="POST"
                                                        class="form-delete m-0 p-0 d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm btn-delete">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit -->
                                        @include('sliders.modaledit')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    @include('sliders.modalcreate')
@endsection
