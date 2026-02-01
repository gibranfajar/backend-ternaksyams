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
                                <h3>Tutorials</h3>
                            </div>
                            <div class="float-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addTutorialModal">
                                    Add Tutorial
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
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th style="width: 20%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tutorials as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="thumbnail"
                                                    width="80">
                                            </td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->category->name }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group gap-2" role="group">
                                                    <form action="{{ route('tutorials.toggleStatus', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if ($item->status == 'published')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                Set Archived
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                Set Published
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editTutorialModal{{ $item->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Edit Tutorial Modal -->
                                        @include('tutorials.modaledit')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Tutorial Modal -->
    @include('tutorials.modalcreate')
@endsection
