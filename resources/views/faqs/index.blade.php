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
                                <h3>Faqs</h3>
                            </div>
                            <div class="float-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addFaqModal">
                                    Add Faq
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('faqs.modalcreate')

            <!-- [ Main Content ] start -->
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" role="tablist">
                @foreach ($types as $key => $type)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $key === 0 ? 'active' : '' }}" id="variant-tab-{{ $type['id'] }}"
                            data-bs-toggle="tab" data-bs-target="#variant-{{ $type['id'] }}" type="button"
                            role="tab">
                            {{ ucfirst($type['target']) }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                @foreach ($types as $key => $type)
                    <div class="tab-pane fade {{ $key === 0 ? 'show active' : '' }}" id="variant-{{ $type['id'] }}"
                        role="tabpanel">

                        <!-- Table -->
                        <div class="table-responsive">
                            <table id="myTable-{{ $type['id'] }}" class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center">No</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th style="width: 20%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($faqs->where('target', $type['target']) as $faq)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $faq->question }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($faq->answer, 30) }}</td>
                                            <td>{{ $faq->category->name }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $faq->status === 'show' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($faq->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group gap-2" role="group">
                                                    <form action="{{ route('faqs.toggleStatus', $faq->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if ($faq->status == 'show')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                Set Hidden
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                Set Show
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editFaqModal{{ $faq->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('faqs.modaledit', ['faq' => $faq])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @foreach ($types as $type)
                $('#myTable-{{ $type['id'] }}').DataTable();
            @endforeach
        });
    </script>
@endpush
