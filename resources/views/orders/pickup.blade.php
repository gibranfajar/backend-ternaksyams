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
                                <h3>Pickup Orders</h3>
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
                            <form action="{{ route('orders.pickup.store') }}" method="POST">
                                @csrf

                                <div class="mb-3 row">
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Pickup Date</label>
                                        <input type="date" name="pickup_date" class="form-control"
                                            placeholder="Pickup Date" value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Pickup Time</label>
                                        <input type="time" name="pickup_time" class="form-control"
                                            placeholder="Pickup Time">
                                        <small class="form-text text-muted fst-italic">Waktu pickup harus minimal 90 menit
                                            setelah waktu saat ini.</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Vehicle</label>
                                        <select name="pickup_vehicle" id="" class="form-select">
                                            <option value="">-- Select Vehicle --</option>
                                            <option value="motor">Motor</option>
                                            <option value="mobil">Mobil</option>
                                            <option value="truck">Truck</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#selectOrder">Select Order</button>
                                </div>

                                <div class="mb-3">
                                    <ul class="list-group" id="selected-order-list"></ul>
                                </div>

                                @include('orders.modalOrder')

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function updateSelectedOrders() {
                let selectedList = $("#selected-order-list");
                selectedList.empty();

                let totalWeight = 0;

                $(".form-check-input:checked").each(function() {
                    let orderId = $(this).data("id");
                    let orderLabel = $("label[for='order-" + orderId + "']").text();
                    let weightVal = parseInt($("input[name='weight[]'][data-id='" + orderId + "']")
                        .val()) || 0;

                    totalWeight += weightVal;

                    selectedList.append(`
                    <li class='list-group-item d-flex justify-content-between align-items-center'>
                        ${orderLabel}
                        <button type='button' class='btn btn-sm btn-danger ms-2 remove-order'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </li>
                `);
                });

                checkVehicle(totalWeight);
            }

            function checkVehicle(totalWeight) {
                let currentVehicle = $("select[name='pickup_vehicle']").val();

                if (totalWeight > 500) {
                    if (currentVehicle === "motor") {
                        $("select[name='pickup_vehicle']").val("mobil").trigger("change");

                        // tutup modal (ganti #selectOrderModal sesuai id modal-mu)
                        $("#selectOrder").modal("hide");

                        Swal.fire("Warning", "Total weight exceeds 500 grams. Changed to mobil.", "warning");
                    }
                    $("select[name='pickup_vehicle'] option[value='motor']").prop("disabled", true);
                } else {
                    $("select[name='pickup_vehicle'] option[value='motor']").prop("disabled", false);
                }
            }



            // Checkbox handler
            $(".form-check-input").on("change", updateSelectedOrders);

            // Dropdown handler (biar kalau user klik motor manual juga dicek)
            $("select[name='vehicle']").on("change", function() {
                let totalWeight = 0;
                $(".form-check-input:checked").each(function() {
                    let weightVal = parseInt($("input[name='weight[]'][data-id='" + $(this).data(
                        "id") + "']").val()) || 0;
                    totalWeight += weightVal;
                });
                checkVehicle(totalWeight);
            });

            // Event delegation untuk tombol hapus
            $(document).on("click", ".remove-order", function() {
                let orderLabel = $(this).closest("li").text().trim();
                $(".form-check-input").each(function() {
                    let orderId = $(this).attr("id");
                    let labelText = $("label[for='" + orderId + "']").text().trim();
                    if (labelText === orderLabel) {
                        $(this).prop("checked", false).trigger("change");
                    }
                });
            });
        });
    </script>
@endpush
