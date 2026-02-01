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
                                <h3>Flash Sale</h3>
                            </div>
                            <div class="float-end">
                                <a href="{{ route('flash-sales.create') }}" type="button" class="btn btn-primary">
                                    Add Flash Sale
                                </a>
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
                                        <th>Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Active</th>
                                        <th style="width: 10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statusColors = [
                                            'ongoing' => 'bg-success',
                                            'done' => 'bg-primary',
                                            'draft' => 'bg-secondary',
                                        ];
                                    @endphp
                                    @foreach ($flashSales as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ Carbon\Carbon::parse($item->start_date)->format('d M Y | H:i') }}</td>
                                            <td>{{ Carbon\Carbon::parse($item->end_date)->format('d M Y | H:i') }}</td>
                                            <td>
                                                <span class="badge {{ $statusColors[$item->status] ?? 'bg-secondary' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <form action="{{ route('flash-sales.toggleStatus', $item->id) }}"
                                                        method="POST" class="m-0 p-0">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input cursor-pointer" type="checkbox"
                                                                onchange="this.form.submit()"
                                                                {{ $item->status == 'ongoing' ? 'checked' : '' }}
                                                                title="Toggle Popup">
                                                        </div>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="d-flex justify-content-center align-items-center gap-2">
                                                <button type="button" class="btn btn-info btn-sm me-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#showVariantModal{{ $item->id }}">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                                @include('flashsales.modalvariant')
                                                <a href="{{ route('flash-sales.edit', $item->id) }}" type="button"
                                                    class="btn btn-warning btn-sm me-1">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('flash-sales.destroy', $item->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
