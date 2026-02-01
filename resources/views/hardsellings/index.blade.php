@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title mb-3">
                                <h3>Hardsellings</h3>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                @if ($hardsellings->isEmpty())
                                    <a href="{{ route('hardsellings.create') }}" class="btn btn-primary">
                                        Add Hardselling
                                    </a>
                                @else
                                    <a href="{{ route('hardsellings.editPreview') }}" class="btn btn-warning">
                                        Edit
                                    </a>
                                    <a href="{{ route('hardsellings.destroy') }}" class="btn btn-danger btn-delete">
                                        Delete
                                    </a>
                                @endif
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
                                        <th style="width: 10%" class="text-center">Sort</th>
                                        <th>Content Image</th>
                                        <th>Button Image</th>
                                        <th>Button Link</th>
                                        <th style="width: 10%" class="text-center">Position</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hardsellings as $item)
                                        <tr class="align-middle">
                                            <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                <img src="{{ asset('storage/' . $item->content_image) }}" alt="thumbnail"
                                                    class="img-thumbnail" style="width:60px;height:auto">
                                            </td>
                                            <td class="text-center">
                                                <img src="{{ asset('storage/' . $item->button_image) }}" alt="thumbnail"
                                                    class="img-thumbnail" style="width:60px;height:auto">
                                            </td>
                                            <td class="text-center">{{ $item->button_link }}</td>
                                            <td class="text-center">{{ $item->position }}</td>
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
