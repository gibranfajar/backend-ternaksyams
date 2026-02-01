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
                                <h3>Products</h3>
                            </div>
                            <div class="float-end">
                                <a href="{{ route('products.create') }}" class="btn btn-primary">
                                    Add Product
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
                                        <th>Product</th>
                                        <th style="width: 10%" class="text-center">Variant</th>
                                        <th style="width: 30%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    {{ $item->variants->count() }}
                                                    variants
                                                </span>
                                            </td>
                                            <td class="d-flex gap-2 align-items-center justify-content-center">
                                                <button type="button" class="btn btn-info btn-sm me-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#showVariantModal{{ $item->id }}">
                                                    <i class="ti ti-eye"></i>
                                                    <span>View Variant</span>
                                                </button>
                                                @include('products.modalvariant')
                                                <a href="{{ route('products.edit', $item->id) }}" type="button"
                                                    class="btn btn-warning btn-sm me-1">
                                                    <i class="ti ti-edit"></i>
                                                    <span>Edit</span>
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- Edit Flavour Modal -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
