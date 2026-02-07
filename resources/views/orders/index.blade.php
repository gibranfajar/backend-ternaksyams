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
                                <h3>Orders</h3>
                            </div>
                            <div class="float-end">
                                <a href="{{ route('orders.pickup') }}" type="button" class="btn btn-primary btn-sm me-1">
                                    <i class="bi bi-truck"></i>
                                    Requst Pickup
                                </a>
                            </div>
                            <div class="float-end">
                                <a href="{{ route('orders.printLabel') }}" type="button"
                                    class="btn btn-success btn-sm me-1">
                                    <i class="bi bi-tags"></i>
                                    Print Label
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
                            <div class="table-responsive">
                                <table id="myTable" class="table table-bordered align-middle text-nowrap"
                                    style="font-size: 14px; width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%" class="text-center">No</th>
                                            <th class="text-center">Invoice</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Status Order</th>
                                            <th class="text-center">Status Payment</th>
                                            <th class="text-center">Order Date</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->invoice }}</td>
                                                <td>{{ $item->shipping->shippingInfo->name }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge {{ $item->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge 
                                                            @if ($item->payment->status == 'pending') bg-warning 
                                                            @elseif($item->payment->status == 'settlement') bg-success 
                                                            @elseif($item->payment->status == 'expire') bg-danger 
                                                            @else bg-secondary @endif">
                                                        {{ ucfirst($item->payment->status) }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    {{ $item->created_at->format('d M Y | H:i') }}
                                                </td>
                                                <td class="d-flex gap-2 align-items-center justify-content-center">
                                                    <button type="button" class="btn btn-info btn-sm me-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#showItemsModal{{ $item->id }}">
                                                        <i class="ti ti-eye"></i>
                                                    </button>

                                                    @if ($item->payment->status == 'settlement')
                                                        <button type="button"
                                                            class="btn {{ empty($item->shipping->order_number) ? 'btn-warning' : 'btn-success' }} btn-sm me-1 text-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#showOrderModal{{ $item->id }}"
                                                            {{ empty($item->shipping->order_number) ? '' : 'disabled' }}>

                                                            @if (empty($item->shipping->order_number))
                                                                {!! '<i class="ti ti-package me-1"></i> Send Order to Komship' !!}
                                                            @else
                                                                <i class="ti ti-check me-1"></i> Order Process Komship Done
                                                            @endif
                                                        </button>

                                                        @include('orders.modalorderrequest')
                                                    @endif

                                                    @include('orders.modalitems')
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
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
