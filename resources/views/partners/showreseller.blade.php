<!-- Show Reseller Modal -->
<div class="modal fade" id="showResellerModal{{ $reseller->id }}" tabindex="-1" aria-labelledby="showResellerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showResellerModalLabel">Detail Reseller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">{{ $reseller->name }}</dd>

                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $reseller->email }}</dd>

                    <dt class="col-sm-4">WhatsApp</dt>
                    <dd class="col-sm-8">{{ $reseller->whatsapp }}</dd>

                    <dt class="col-sm-4">Address</dt>
                    <dd class="col-sm-8">{{ $reseller->address }}</dd>

                    <dt class="col-sm-4">Location</dt>
                    <dd class="col-sm-8">
                        {{ $reseller->district_name }}, {{ $reseller->city_name }},
                        {{ $reseller->province_name }} - {{ $reseller->postal_code }}
                    </dd>

                    <hr class="my-2">

                    <dt class="col-sm-4">Bank</dt>
                    <dd class="col-sm-8">{{ $reseller->bank }}</dd>

                    <dt class="col-sm-4">Account Number</dt>
                    <dd class="col-sm-8">{{ $reseller->account_number }}</dd>

                    <dt class="col-sm-4">Account Name</dt>
                    <dd class="col-sm-8">{{ $reseller->account_name }}</dd>

                </dl>
            </div>
        </div>
    </div>
</div>
