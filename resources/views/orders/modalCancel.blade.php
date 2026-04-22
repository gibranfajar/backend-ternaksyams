<!-- Modal -->
<div class="modal fade" id="showModalCancel{{ $item->id }}" tabindex="-1" aria-labelledby="showModalCancelLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="showModalCancelLabel">Order Cancel - #{{ $item->invoice }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('orders.cancel', $item->id) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason</label>
                        <input type="text" class="form-control" id="cancelReason" name="reason" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
