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
                                <h3>Brand Variant</h3>
                            </div>
                            <div class="float-end">
                                <a href="{{ route('brands.create') }}" class="btn btn-primary">
                                    Add Brand Variant
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
                                        <th>Image</th>
                                        <th>Brand</th>
                                        <th>Description</th>
                                        <th style="width: 10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brands as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="thumbnail"
                                                    width="60">
                                            </td>
                                            <td>{{ $item->brand }}</td>
                                            <td>{!! \Illuminate\Support\Str::limit(strip_tags($item->description), 100, '...') !!}</td>
                                            <td class="text-center">
                                                <a href="{{ route('brands.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm me-1"><i class="ti ti-edit"></i></a>
                                            </td>
                                        </tr>
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
