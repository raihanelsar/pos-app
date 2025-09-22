@extends('layouts.app')
@section('title', 'Data Produk')

@section('content')
<section class="row mt-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Data Produk
                    <div class="float-end">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
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

@section('modal')
{{-- Modal Create --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCreate" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->category_name ?? $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Foto Produk</label>
                        <input type="file" name="product_photo" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="text" id="create_price_display" class="form-control" placeholder="Rp 1.500.000">
                        <input type="hidden" name="product_price" id="create_price" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="product_description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="is_active" class="form-select" required>
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

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-white">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="category_id" id="edit_category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->category_name ?? $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="product_name" id="edit_product_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Foto Produk (kosongkan jika tidak ingin mengganti)</label>
                        <input type="file" name="product_photo" id="edit_product_photo" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="text" id="edit_price_display" class="form-control">
                        <input type="hidden" name="product_price" id="edit_price" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="product_description" id="edit_product_description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="is_active" id="edit_is_active" class="form-select" required>
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
    // CSRF setup
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Helper: parse & format Rupiah
    function parseNumber(str){ return String(str||'').replace(/[^\d]/g,''); }
    function formatRupiah(n){ return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0,maximumFractionDigits:0}).format(Number(n)||0); }

    // DataTable
    let table = $('#table1').DataTable({
        processing:true,
        serverSide:true,
        ajax:"{{ route('products.data') }}",
        columns:[
            {data:'action', orderable:false, searchable:false},
            {data:'category_name', name:'category_name'},
            {data:'product_name', name:'product_name'},
            {data:'product_photo', render:data=>data},
            {data:'product_price', render:data=>formatRupiah(data)},
            {data:'product_description'},
            {data:'is_active', render:data=>data==1?'<span class="badge bg-success">Ada</span>':'<span class="badge bg-danger">Tidak Ada</span>'}
        ]
    });

    // Price input (Create)
    $('#create_price_display').on('input', function(){
        const n = parseNumber(this.value);
        $('#create_price').val(n); this.value = n? formatRupiah(n): '';
    });

    // Price input (Edit)
    $('#edit_price_display').on('input', function(){
        const n = parseNumber(this.value);
        $('#edit_price').val(n); this.value = n? formatRupiah(n): '';
    });

    // Create
    $('#formCreate').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url:'/admin/products',
            method:'POST',
            data:new FormData(this),
            processData:false, contentType:false,
            success:function(res){
                bootstrap.Modal.getOrCreateInstance($('#modalCreate')[0]).hide();
                table.ajax.reload(null,false);
                Swal.fire('Berhasil', res.message, 'success');
                $('#formCreate')[0].reset();
            },
            error:function(err){ Swal.fire('Gagal', err.responseJSON?.message||'Gagal tambah produk','error'); }
        });
    });

    // Open Edit Modal
    $(document).on('click','.btnEdit', function(){
        const id=$(this).data('id');
        $.get(`/admin/products/${id}`).done(res=>{
            $('#edit_id').val(res.id);
            $('#edit_category_id').val(res.category_id);
            $('#edit_product_name').val(res.product_name);
            $('#edit_product_description').val(res.product_description);
            $('#edit_is_active').val(res.is_active==1?1:0);
            $('#edit_price').val(res.product_price);
            $('#edit_price_display').val(formatRupiah(res.product_price));
            bootstrap.Modal.getOrCreateInstance($('#modalEdit')[0]).show();
        }).fail(()=>Swal.fire('Error','Gagal ambil data','error'));
    });

    // Edit
    $('#formEdit').on('submit', function(e){
        e.preventDefault();
        const id = $('#edit_id').val();
        $.ajax({
            url:`/admin/products/${id}`,
            method:'POST', // menggunakan method PUT di form
            data:new FormData(this),
            processData:false, contentType:false,
            success:function(res){
                bootstrap.Modal.getOrCreateInstance($('#modalEdit')[0]).hide();
                table.ajax.reload(null,false);
                Swal.fire('Berhasil', res.message, 'success');
            },
            error:function(err){ Swal.fire('Gagal', err.responseJSON?.message||'Gagal update produk','error'); }
        });
    });

    // Delete
    $(document).on('click','.btnDelete', function(e){
        e.preventDefault();
        const $form = $(this).closest('form');
        Swal.fire({
            title:'Hapus produk?', text:'Tindakan ini tidak bisa dibatalkan.', icon:'warning',
            showCancelButton:true, confirmButtonText:'Ya, hapus', cancelButtonText:'Batal'
        }).then(result=>{
            if(result.isConfirmed){
                $.post($form.attr('action'), {_method:'DELETE', _token:$form.find('input[name="_token"]').val()}, function(res){
                    Swal.fire('Terhapus', res.message, 'success');
                    table.ajax.reload(null,false);
                }).fail(()=>Swal.fire('Gagal','Gagal hapus produk','error'));
            }
        });
    });
</script>
@endsection
