<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

<script>
    /* ============================
     * GLOBAL READY (SATU AJA)
     * ============================ */
    $(function() {

        /* ============================
         * DATATABLES
         * ============================ */
        if ($('#myTable').length) {
            $('#myTable').DataTable({
                dom: 'lfrtip',
                scrollX: true,
                autoWidth: false
            });
        }

        if ($('#usersTable').length) {
            $('#usersTable').DataTable({
                paging: false,
                searching: true,
                info: false,
                scrollY: "300px",
                scrollCollapse: true,
                dom: 'frtip'
            });
        }

        /* ============================
         * TEMPLATE CONFIG (SAFE)
         * ============================ */
        if (typeof layout_change === 'function') {
            layout_change('light');
            change_box_container('false');
            layout_rtl_change('false');
            preset_change("preset-1");
            font_change("Public-Sans");
        }

        /* ============================
         * IMAGE PREVIEW (GIZI)
         * ============================ */
        $('#giziInput').on('change', function() {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => {
                $('#previewImg').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        });

        /* ============================
         * IMAGE PREVIEW (THUMBNAIL - MODAL SAFE)
         * ============================ */
        $(document).on('change', '#thumbnail', function() {
            const modal = $(this).closest('.modal');
            const preview = modal.length ?
                modal.find('#thumbnailPreview') :
                $('#thumbnailPreview');

            preview.empty();

            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => {
                preview.append(
                    $('<img>', {
                        src: e.target.result,
                        class: 'img-thumbnail mt-2',
                        css: {
                            maxWidth: '200px'
                        }
                    })
                );
            };
            reader.readAsDataURL(file);
        });
    });
</script>
<script>
    jQuery(function($) {

        /* ============================
         * TOASTR CONFIG
         * ============================ */
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "2000",
                extendedTimeOut: "1000"
            };
        }

        /* ============================
         * FLASH MESSAGE
         * ============================ */
        @if (session('success'))
            toastr.success(@json(session('success')));
        @endif

        @if (session('error'))
            toastr.error(@json(session('error')));
        @endif

        @if ($errors->any())
            toastr.error("{!! implode('<br>', $errors->all()) !!}");
        @endif

    });
</script>

<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        e.preventDefault();

        const form = btn.closest('form');

        Swal.fire({
            title: 'Yakin?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
