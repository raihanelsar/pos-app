@extends('app')
@section('title','Transaksi')

@section('content')
<section class="row mt-2">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header text-white">
                <h5>Transaksi POS</h5>
            </div>
            <div class="card-body text-white">
                <div class="mb-3">
                    <label>Customer (opsional)</label>
                    <input type="text" id="customer_name" class="form-control">
                </div>
                @csrf
                <div class="row g-2 align-items-end mb-3">
                    <div class="col">
                        <label>Produk</label>
                        <select id="tx_product" class="form-select">
                            <option value="">-- Pilih produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" data-price="{{ $p->product_price }}">{{ $p->product_name }} - {{ number_format($p->product_price,0,',','.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <label>Jumlah</label>
                        <input type="number" id="tx_qty" value="1" class="form-control" min="1">
                    </div>
                    <div class="col-auto">
                        <button id="addItem" class="btn btn-primary">Tambah</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table" id="tx_items_tbl">
                        <thead>
                            <tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th><th></th></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                                <label>Bayar</label>
                                <div class="input-group">
                                    <input type="number" id="payment_amount" class="form-control" min="0" value="0">
                                    <button id="exactPay" class="btn btn-outline-secondary" type="button" title="Isi jumlah pas">Exact</button>
                                </div>
                                <div id="payment_feedback" class="invalid-feedback d-none">Jumlah bayar kurang dari total.</div>
                            </div>
                        <div class="mb-2">
                            <label>Kembalian</label>
                            <input type="text" id="payment_change_display" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                        <div>
                            <h5>Total: <span id="tx_total">0</span></h5>
                            <div class="mt-2 text-end">
                                <button id="submitTx" class="btn btn-success">Simpan Transaksi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header text-white">
                <h5>Riwayat Transaksi</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($transactions as $t)
                        <li class="list-group-item">
                            #{{ $t->id }} - {{ $t->customer_name ?? 'Pelanggan' }} - {{ number_format($t->total_amount,0,',','.') }}
                        </li>
                    @endforeach
                </ul>
                <div class="mt-2">{{ $transactions->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    const items = [];
    function formatRupiah(n){ return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0,maximumFractionDigits:0}).format(n||0); }

    function recalc(){
        let total = 0;
        const $body = $('#tx_items_tbl tbody').empty();
        items.forEach((it,idx)=>{
            const sub = it.qty * it.price;
            total += sub;
            $body.append(`<tr data-idx="${idx}"><td>${it.name}</td><td>${it.qty}</td><td>${formatRupiah(it.price)}</td><td>${formatRupiah(sub)}</td><td><button class="btn btn-sm btn-danger removeItem">Hapus</button></td></tr>`);
        });
        $('#tx_total').text(formatRupiah(total));
        // update payment feedback and change
        updatePaymentState();
    }

    $('#addItem').on('click', function(e){
        e.preventDefault();
        const pid = $('#tx_product').val();
        if(!pid) return Swal.fire('Pilih produk','Silakan pilih produk terlebih dahulu','info');
        const $opt = $('#tx_product option:selected');
        const name = $opt.text();
        const price = parseInt($opt.data('price')||0);
        const qty = parseInt($('#tx_qty').val()||1);
        items.push({product_id: pid, name, price, qty});
        recalc();
        $('#tx_product').focus();
    });

    $(document).on('click', '.removeItem', function(){
        const idx = $(this).closest('tr').data('idx');
        items.splice(idx,1);
        recalc();
    });

    function getTotalRaw(){ return items.reduce((s,it)=>s + (it.qty*it.price),0); }

    function updatePaymentState(){
        const payment = parseInt($('#payment_amount').val()||0);
        const total = getTotalRaw();
        const change = Math.max(0, payment - total);
        $('#payment_change_display').val(formatRupiah(change));

        const $pay = $('#payment_amount');
        const $fb = $('#payment_feedback');
        const $submit = $('#submitTx');

        if(payment < total){
            $pay.addClass('is-invalid').removeClass('is-valid');
            $fb.removeClass('d-none');
            $submit.prop('disabled', true);
        } else {
            $pay.removeClass('is-invalid').addClass('is-valid');
            $fb.addClass('d-none');
            $submit.prop('disabled', items.length===0);
        }
    }

    $('#payment_amount').on('input', function(){ updatePaymentState(); });

    // Exact button fills payment with exact total amount
    $('#exactPay').on('click', function(){
        const total = getTotalRaw();
        $('#payment_amount').val(total);
        updatePaymentState();
    });

    // keep submit button disabled until items added
    $('#submitTx').prop('disabled', true);
    // enable/disable based on items change
    const origSubmitHandler = function(){
        if(items.length===0) return Swal.fire('Error','Tambahkan item terlebih dahulu','error');
        const payment = parseInt($('#payment_amount').val()||0);
        const total = getTotalRaw();

        if(payment < total){
            return Swal.fire('Pembayaran Kurang', 'Jumlah bayar kurang dari total transaksi. Mohon periksa kembali.', 'warning');
        }

        const payload = {
            _token: $('input[name="_token"]').first().val(),
            customer_name: $('#customer_name').val(),
            payment_amount: payment,
            items: items.map(i=>({product_id:i.product_id, quantity:i.qty, unit_price:i.price}))
        };

        const $btn = $('#submitTx');
        $btn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: "{{ route('transactions.store') }}",
            method: 'POST',
            data: payload,
            dataType: 'json'
        }).done(function(res){
            Swal.fire('Sukses', res.message || 'Transaksi tersimpan', 'success').then(()=>location.reload());
        }).fail(function(xhr){
            const json = xhr.responseJSON;
            const msg = json?.message || 'Terjadi kesalahan';
            let detail = '';
            if(json?.errors){
                detail = Object.values(json.errors).flat().join('<br>');
            }
            Swal.fire('Gagal', msg + (detail? '<br>'+detail:''), 'error');
        }).always(function(){
            $btn.prop('disabled', false).text('Simpan Transaksi');
        });
    };

    $('#submitTx').off('click').on('click', origSubmitHandler);

    // ensure initial state
    $(function(){
        recalc();
        updatePaymentState();
    });

</script>
@endsection
