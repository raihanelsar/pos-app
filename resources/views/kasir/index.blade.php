@extends('layouts.app')
@section('title', 'Kasir - Transaksi')

@section('content')
<div class="pagetitle">
  <h1><i class="mdi mdi-cash-register"></i> Kasir</h1>
</div>

<section class="section">
  <div class="row">

    {{-- PRODUK --}}
    <div class="col-lg-8 mb-3">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
          <span><i class="mdi mdi-package-variant-closed"></i> Produk Tersedia</span>
          <span class="badge bg-light text-dark">{{ count($products) }} produk</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            @forelse($products as $p)
              <div class="col-md-4">
                <div class="card h-100 shadow-sm product-card">
                  <img src="{{ $p->product_photo ? asset('storage/'.$p->product_photo) : asset('images/no-image.png') }}"
                       class="card-img-top" style="height:180px; object-fit:cover;">
                  <div class="card-body d-flex flex-column justify-content-between text-white">
                    <div>
                      <h6 class="fw-bold mb-1">{{ $p->product_name ?? $p->name }}</h6>
                      <p class="text-muted small mb-1">
                        Rp {{ number_format($p->price ?? $p->product_price, 0, ',', '.') }}
                      </p>
                      <span class="badge bg-info">Stok: {{ $p->product_qty ?? '-' }}</span>
                    </div>
                    <button
                      class="btn btn-sm btn-success mt-2 add-to-cart"
                      data-id="{{ $p->id }}"
                      data-name="{{ $p->product_name ?? $p->name }}"
                      data-price="{{ $p->price ?? $p->product_price }}">
                      <i class="mdi mdi-cart-plus"></i> Tambah
                    </button>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12 text-center text-muted">Belum ada produk aktif</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- KERANJANG --}}
    <div class="col-lg-4 mb-3">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-success text-white">
          <i class="mdi mdi-cart-outline"></i> Keranjang
        </div>
        <div class="card-body">
          <form id="transaction-form" method="POST" action="{{ route('kasir.transaksi.store') }}">
            @csrf

            <div class="table-responsive mb-3">
              <table class="table table-sm table-hover align-middle" id="cart-table">
                <thead class="table-light">
                  <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th class="text-end">Subtotal</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="text-center text-muted"><td colspan="4">Belum ada item</td></tr>
                </tbody>
              </table>
            </div>

            {{-- Total & Payment --}}
            <div class="row g-2 mb-3 text-white">
              <div class="col-12">
                <div class="card bg-light p-2">
                  <label class="form-label mb-0">Total</label>
                  <h4 id="total-display" class="mb-0 text-end">Rp 0</h4>
                </div>
              </div>
              <div class="col-6">
                <label class="form-label">Bayar</label>
                <input type="number" name="paid_amount" id="paid-amount"
                       class="form-control text-start" required min="0">
              </div>
              <div class="col-6">
                <label class="form-label">Kembalian</label>
                <input type="text" id="change-display"
                       class="form-control text-start fw-bold" readonly>
              </div>
            </div>

            <div class="mb-2 text-white">
              <label class="form-label">Customer</label>
              <input type="text" name="customer_name" class="form-control">
            </div>

            <button type="submit" class="btn btn-success w-100">
              <i class="mdi mdi-check-circle"></i> Simpan Transaksi
            </button>
          </form>
        </div>
      </div>

      {{-- RIWAYAT --}}
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
          <i class="mdi mdi-history"></i> Riwayat Transaksi
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0">
              <thead class="table-light">
                <tr>
                  <th>Kode</th>
                  <th>Waktu</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse($orders as $t)
                  <tr>
                    <td>{{ $t->order_code }}</td>
                    <td>{{ $t->order_date?->format('d M H:i') }}</td>
                    <td class="text-end">
                      Rp {{ number_format($t->order_amount, 0, ',', '.') }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted">Belum ada transaksi</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="p-2">
            {{ $orders->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('script')
<script>
(function(){
  const cart = [];
  const cartTbody = document.querySelector('#cart-table tbody');
  const totalDisplay = document.getElementById('total-display');
  const paidInput = document.getElementById('paid-amount');
  const changeDisplay = document.getElementById('change-display');
  const form = document.getElementById('transaction-form');

  function findIndex(id) {
    return cart.findIndex(i => String(i.id) === String(id));
  }

  function addToCart(id, name, price) {
  console.log("Add to cart:", id, name, price); // debug
  const idx = findIndex(id);
  if (idx > -1) {
    cart[idx].qty++;
  } else {
    cart.push({ id: String(id), name, price, qty: 1 });
  }
  renderCart();
}
  window.addToCart = addToCart;

  document.addEventListener('click', function(e) {
  const btn = e.target.closest('.add-to-cart');
  if (btn) {
    addToCart(btn.dataset.id, btn.dataset.name, parseFloat(btn.dataset.price));
  }
});

  function renderCart() {
    cartTbody.innerHTML = '';
    if (cart.length === 0) {
      cartTbody.innerHTML = '<tr class="text-center text-muted"><td colspan="4">Belum ada item</td></tr>';
      totalDisplay.textContent = 'Rp 0';
      changeDisplay.value = '';
      return;
    }

    let total = 0;
    cart.forEach((item, index) => {
      const subtotal = item.qty * item.price;
      total += subtotal;

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          ${escapeHtml(item.name)}
          <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
        </td>
        <td style="width:90px;">
          <input type="number" name="items[${index}][quantity]"
                 class="form-control form-control-sm qty-input"
                 value="${item.qty}" min="1" data-index="${index}">
          <input type="hidden" name="items[${index}][unit_price]" value="${item.price}">
        </td>
        <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
        <td>
          <button type="button" class="btn btn-sm btn-danger remove-btn" data-index="${index}">X</button>
        </td>
      `;
      cartTbody.appendChild(tr);
    });

    totalDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    recalcChange();

    document.querySelectorAll('.qty-input').forEach(el => {
      el.addEventListener('change', function(){
        const idx = Number(this.dataset.index);
        cart[idx].qty = Math.max(1, Number(this.value) || 1);
        renderCart();
      });
    });

    document.querySelectorAll('.remove-btn').forEach(btn => {
      btn.addEventListener('click', function(){
        cart.splice(this.dataset.index, 1);
        renderCart();
      });
    });
  }

  function recalcChange(){
    const rawTotal = cart.reduce((s,i)=> s + (i.price * i.qty), 0);
    const bayar = Number(paidInput.value || 0);
    const change = Math.max(bayar - rawTotal, 0);
    changeDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(change);
  }

  paidInput.addEventListener('input', recalcChange);

  form.addEventListener('submit', async function(evt){
    evt.preventDefault();
    if (cart.length === 0) {
      Swal.fire('Peringatan', 'Keranjang masih kosong!', 'warning');
      return;
    }

    const rawTotal = cart.reduce((s,i)=> s + (i.price * i.qty), 0);
    const bayar = Number(paidInput.value || 0);
    if (bayar < rawTotal) {
      Swal.fire('Gagal', 'Uang bayar kurang dari total belanja!', 'error');
      return;
    }

    const fd = new FormData(this);
    try {
      const res = await fetch(this.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
        body: fd
      });
      const data = await res.json().catch(()=>({}));
      if (res.ok && (data.success || data.message)) {
        Swal.fire({
          title: 'Sukses',
          text: data.message || 'Transaksi berhasil',
          icon: 'success',
          showCancelButton: true,
          confirmButtonText: 'Cetak Struk',
          cancelButtonText: 'OK'
        }).then((result) => {
          if (result.isConfirmed && data.print_url) {
            window.open(data.print_url, '_blank');
          }
        });
        cart.length = 0;
        this.reset();
        renderCart();
      } else {
        Swal.fire('Error', data.message || 'Gagal menyimpan transaksi', 'error');
      }
    } catch (err) {
      console.error(err);
      Swal.fire('Error', 'Terjadi kesalahan koneksi.', 'error');
    }
  });

  function escapeHtml(string) {
    return String(string).replace(/[&<>"'`=\/]/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    })[s]);
  }

  renderCart();
})();
</script>
@endsection
