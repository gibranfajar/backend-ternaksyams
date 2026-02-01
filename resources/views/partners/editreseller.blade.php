<div class="modal fade" id="editResellerModal{{ $reseller->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Reseller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('partner-resellers.update', $reseller->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    {{-- ================= DATA UMUM ================= --}}
                    <h6 class="text-muted mb-3">Data Umum</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ $reseller->name }}"
                                required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ $reseller->whatsapp }}"
                                required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $reseller->email }}"
                                required>
                        </div>
                    </div>

                    {{-- ================= ALAMAT ================= --}}
                    <hr>
                    <h6 class="text-muted mb-3">Alamat</h6>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2" required>{{ $reseller->address }}</textarea>
                    </div>

                    <div class="row">
                        {{-- Province --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Provinsi</label>
                            <select class="form-select province-select" data-id="{{ $reseller->id }}" name="province_id"
                                data-selected="{{ $reseller->province_id }}" required>
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>

                        {{-- City --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Kota / Kabupaten</label>
                            <select class="form-select city-select" data-id="{{ $reseller->id }}" name="city_id"
                                data-selected="{{ $reseller->city_id }}" required>
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>

                        {{-- District --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Kecamatan</label>
                            <select class="form-select district-select" data-id="{{ $reseller->id }}"
                                name="district_id" data-selected="{{ $reseller->district_id }}" required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        {{-- Postal Code --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="postal_code" class="form-control"
                                value="{{ $reseller->postal_code }}">
                        </div>
                    </div>

                    {{-- ================= BANK ================= --}}
                    <hr>
                    <h6 class="text-muted mb-3">Data Bank</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bank</label>
                            <input type="text" name="bank" class="form-control" value="{{ $reseller->bank }}"
                                required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">No Rekening</label>
                            <input type="text" name="account_number" class="form-control"
                                value="{{ $reseller->account_number }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nama Rekening</label>
                            <input type="text" name="account_name" class="form-control"
                                value="{{ $reseller->account_name }}" required>
                        </div>
                    </div>

                    {{-- ================= STATUS ================= --}}
                    <hr>
                    <h6 class="text-muted mb-3">Status</h6>

                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            @foreach (['pending', 'approved', 'rejected', 'suspended', 'inactive'] as $status)
                                <option value="{{ $status }}"
                                    {{ $reseller->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            async function fetchData(url) {
                const res = await fetch(url);
                const json = await res.json();

                if (!Array.isArray(json.data)) {
                    console.error('Invalid response:', json);
                    return [];
                }

                return json.data;
            }

            async function loadProvinces(modalId) {
                const select = document.querySelector(`.province-select[data-id="${modalId}"]`);
                const selected = select.dataset.selected;

                const data = await fetchData('/api/provinces');

                data.forEach(p => {
                    select.innerHTML +=
                        `<option value="${p.id}" ${p.id == selected ? 'selected' : ''}>${p.name}</option>`;
                });

                if (selected) loadCities(modalId, selected);
            }

            async function loadCities(modalId, provinceId) {
                const select = document.querySelector(`.city-select[data-id="${modalId}"]`);
                const selected = select.dataset.selected;

                select.innerHTML = `<option value="">Pilih Kota</option>`;

                const data = await fetchData(`/api/cities/${provinceId}`);

                data.forEach(c => {
                    select.innerHTML +=
                        `<option value="${c.id}" ${c.id == selected ? 'selected' : ''}>${c.name}</option>`;
                });

                if (selected) loadDistricts(modalId, selected);
            }

            async function loadDistricts(modalId, cityId) {
                const select = document.querySelector(`.district-select[data-id="${modalId}"]`);
                const selected = select.dataset.selected;

                select.innerHTML = `<option value="">Pilih Kecamatan</option>`;

                const data = await fetchData(`/api/districts/${cityId}`);

                data.forEach(d => {
                    select.innerHTML +=
                        `<option value="${d.id}" ${d.id == selected ? 'selected' : ''}>${d.name}</option>`;
                });
            }

            document.querySelectorAll('.province-select').forEach(el => {
                loadProvinces(el.dataset.id);

                el.addEventListener('change', function() {
                    loadCities(this.dataset.id, this.value);
                });
            });

            document.querySelectorAll('.city-select').forEach(el => {
                el.addEventListener('change', function() {
                    loadDistricts(this.dataset.id, this.value);
                });
            });

        });
    </script>
@endpush
