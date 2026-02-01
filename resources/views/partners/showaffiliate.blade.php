<!-- Show Affiliate Modal -->
<div class="modal fade" id="showAffiliateModal{{ $affiliate->id }}" tabindex="-1" aria-labelledby="showAffiliateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showAffiliateModalLabel">Detail Affiliate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">{{ $affiliate->name }}</dd>

                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $affiliate->email }}</dd>

                    <dt class="col-sm-4">WhatsApp</dt>
                    <dd class="col-sm-8">{{ $affiliate->whatsapp }}</dd>

                    <dt class="col-sm-4">Province</dt>
                    <dd class="col-sm-8">{{ $affiliate->province }}</dd>

                    <dt class="col-sm-4">City</dt>
                    <dd class="col-sm-8">{{ $affiliate->city }}</dd>

                    <hr class="my-2">

                    <dt class="col-sm-4">Sosmed Account</dt>
                    <dd class="col-sm-8">{{ $affiliate->sosmed_account }}</dd>

                    <dt class="col-sm-4">Shopee Account</dt>
                    <dd class="col-sm-8">{{ $affiliate->shopee_account }}</dd>

                    <dt class="col-sm-4">Tokopedia Account</dt>
                    <dd class="col-sm-8">{{ $affiliate->tokopedia_account }}</dd>

                    <dt class="col-sm-4">Tiktok Account</dt>
                    <dd class="col-sm-8">{{ $affiliate->tiktok_account }}</dd>

                    <dt class="col-sm-4">Lazada Account</dt>
                    <dd class="col-sm-8">{{ $affiliate->lazada_account }}</dd>

                </dl>
            </div>
        </div>
    </div>
</div>
