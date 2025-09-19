@extends('app')
@section('title', 'Data Produk')

@section('content')
<section class="row mt-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Data Produk
                    <div class="float-end">
                        <button type="button" class="btn btn-primary btn-sm" aria-label="Tambah produk" data-bs-toggle="modal" data-bs-target="#modalCreate">
                            <i class="mdi mdi-plus-circle-outline me-1"></i> Tambah
                        </button>
                    </div>
                </h5>
            </div>
            <div class="card-body text-white">
                <div class="table-responsive">
                    <table class="table table-hover table-dark" id="table1">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Kategori</th>
                                <th>Nama Produk</th>
                                <th>Foto Produk</th>
                                <th>Harga</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('modalEdit')
<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                                <label>Kategori</label>
                                <select id="edit_category_id" name="category_id" class="form-control" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->category_name ?? $c->name }}</option>
                                        @endforeach
                                </select>
                        </div>

                        <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" id="edit_product_name" name="product_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                                <label>Foto Produk (kosongkan jika tidak ingin mengganti)</label>
                                <input type="file" id="edit_product_photo" name="product_photo" class="form-control" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label>Harga</label>
                            <input type="text" id="edit_product_price_display" class="form-control">
                            <input type="hidden" name="product_price" id="edit_product_price" required>
                        </div>

                        <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea id="edit_product_description" name="product_description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                                <label>Status</label>
                                <select id="edit_is_active" name="is_active" class="form-control" required>
                                        <option value="1">Ada</option>
                                        <option value="0">Tidak Ada</option>
                                </select>
                        </div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('modal')
<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateLabel">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreate" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_category_id" class="form-label">Kategori</label>
                        <select id="create_category_id" name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->category_name ?? $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="create_product_name" class="form-label">Nama Produk</label>
                        <input type="text" id="create_product_name" name="product_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="create_product_photo" class="form-label">Foto Produk</label>
                        <input type="file" id="create_product_photo" name="product_photo" class="form-control" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label for="create_product_price_display" class="form-label">Harga</label>
                        <input type="text" id="create_product_price_display" class="form-control">
                        <input type="hidden" name="product_price" id="create_product_price" required>
                    </div>

                    <div class="mb-3">
                        <label for="create_product_description" class="form-label">Deskripsi</label>
                        <textarea id="create_product_description" name="product_description" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="create_is_active" class="form-label">Status</label>
                        <select id="create_is_active" name="is_active" class="form-select" required>
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Helper: format number to Indonesian Rupiah (coerce to Number)
    // Ensure no fraction digits for IDR (whole rupiah amounts)
    function formatRupiah(angka) {
        const n = Number(angka) || 0;
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(n);
    }

    // Helper: remove all non-digit characters (null-safe)
    function parseNumber(str) {
        return String(str || '').replace(/[^\d]/g, '');
    }

    // Initialize DataTable with plain functions (more compatible than arrow in some contexts)
    let table = $('#table1').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('products.data') }}",
        columns: [
            {data: 'action', orderable: false, searchable: false},
            {data: 'category_name', name: 'category_name'},
            {data: 'product_name', name: 'product_name'},
            {
                data: 'product_photo',
                render: function(data, type, row, meta) {
                    return data ? '<img src="/storage/' + data + '" width="50">' : '-';
                }
            },
            {
                data: 'product_price',
                name: 'product_price',
                render: function(data) {
                    return formatRupiah(data);
                }
            },
            {data: 'product_description', name: 'product_description'},
            {data: 'is_active', name: 'is_active'}
        ]
    });

    // Bind price formatting only if the inputs exist
    const $createPriceDisplay = $('#create_product_price_display');
    if ($createPriceDisplay.length) {
        $createPriceDisplay.on('input', function () {
            const angka = parseNumber($(this).val());
            if (angka) {
                $('#create_product_price').val(angka);
                $(this).val(formatRupiah(angka));
            } else {
                $('#create_product_price').val('');
                $(this).val('');
            }
        });
    }

    const $editPriceDisplay = $('#edit_product_price_display');
    if ($editPriceDisplay.length) {
        $editPriceDisplay.on('input', function () {
            const angka = parseNumber($(this).val());
            if (angka) {
                $('#edit_product_price').val(angka);
                $(this).val(formatRupiah(angka));
            } else {
                $('#edit_product_price').val('');
                $(this).val('');
            }
        });
    }

    // CREATE form submit
    $('#formCreate').on('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: "{{ route('products.store') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                try { bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCreate')).hide(); } catch(e) {}
                $('#formCreate')[0].reset();
                table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Produk berhasil ditambahkan!' });
            },
            error: function(err) {
                if (err && err.status === 422 && err.responseJSON && err.responseJSON.errors) {
                    const errors = err.responseJSON.errors;
                    const messages = Object.values(errors).flat().join('\n');
                    Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: messages });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: err.responseJSON?.message || 'Terjadi kesalahan' });
                }
            }
        });
    });

    // EDIT: open modal and fill data
    $(document).on('click', '.btnEdit', function(){
        const id = $(this).data('id');
        if (!id) return;
        $.get(`/products/${id}`).done(function(res){
            $('#edit_id').val(res.id);
            $('#edit_category_id').val(res.category_id);
            $('#edit_product_name').val(res.product_name);
            $('#edit_product_description').val(res.product_description);
            $('#edit_is_active').val(res.is_active ? 1 : 0);

            // isi harga: store numeric-only value in hidden input and formatted display
            const numericPrice = parseNumber(res.product_price ?? '');
            $('#edit_product_price').val(numericPrice);
            $('#edit_product_price_display').val(numericPrice ? formatRupiah(numericPrice) : '');

            try { bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEdit')).show(); } catch(e) {}
        }).fail(function(err){
            Swal.fire('Error', err.responseJSON?.message || 'Gagal ambil data produk', 'error');
        });
    });

    // EDIT form submit
    $('#formEdit').on('submit', function(e){
        e.preventDefault();
        const id = $('#edit_id').val();
        if (!id) return Swal.fire('Error', 'ID produk tidak ditemukan', 'error');
        const formData = new FormData(this);
        $.ajax({
            url: `/products/${id}`,
            method: 'POST', // uses @method('PUT') in the form
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                try { bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEdit')).hide(); } catch(e) {}
                table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Produk berhasil diperbarui!' });
            },
            error: function(err) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Gagal update produk' });
            }
        });
    });

    // DELETE: delegated handler for delete buttons inside the table
    $(document).on('click', '#table1 button.btn-danger', function(e){
        e.preventDefault();
        const $btn = $(this);
        const $form = $btn.closest('form');
        if (!$form.length) return;

        Swal.fire({
            title: 'Hapus produk?',
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const action = $form.attr('action');
                const token = $form.find('input[name="_token"]').val();
                $.ajax({
                    url: action,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: token
                    },
                    success: function(res) {
                        Swal.fire({ icon: 'success', title: 'Terhapus', text: res.message || 'Produk berhasil dihapus' });
                        table.ajax.reload(null, false);
                    },
                    error: function(err) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Gagal menghapus produk' });
                    }
                });
            }
        });
    });
</script>
@endsection

