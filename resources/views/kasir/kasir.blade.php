@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<div class="container mt-4">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3 class="mb-3">Kasir</h3>

    <div class="row">
        {{-- Produk --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Produk</span>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
                        Lihat Keranjang (<span id="cart-count">0</span>)
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-md-3 mb-3">
                                <div class="card h-100">
                                    <img src="{{ asset('storage/'.$product['image']) }}"
                                         class="card-img-top"
                                         alt="{{ $product['name'] }}"
                                         style="height:150px; object-fit:cover;">
                                    <div class="card-body">
                                        <h6>{{ $product['name'] }}</h6>
                                        <p class="mb-1">Rp {{ number_format($product['price'],0,',','.') }}</p>
                                        <p class="mb-1">Stok: {{ $product['qty'] }}</p>
                                        <button class="btn btn-sm btn-success add-to-cart"
                                            data-id="{{ $product['id'] }}"
                                            data-name="{{ $product['name'] }}"
                                            data-price="{{ $product['price'] }}">
                                            + Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Keranjang --}}
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('kasir.post') }}" method="POST" id="kasirForm">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="cartModalLabel">Keranjang Belanja</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">

            <ul id="cart-list" class="list-group mb-3"></ul>

            <div class="mb-2">
                <label>Total:</label>
                <input type="text" id="total" name="total" class="form-control" readonly>
            </div>
            <div class="mb-2">
                <label>Uang Cash:</label>
                <input type="number" id="cash" name="cash" class="form-control">
            </div>
            <div class="mb-2">
                <label>Kembalian:</label>
                <input type="text" id="change" name="change" class="form-control" readonly>
            </div>

            <input type="hidden" name="cart" id="cart-input">
            <input type="hidden" name="order_code" value="{{ 'POS-'.date('Ymd-His') }}">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="clearCart()">Clear Cart</button>
            <button type="submit" class="btn btn-success">Proses Transaksi</button>
          </div>
        </div>
    </form>
  </div>
</div>
@endsection

@section('script')
{{-- Script --}}
<script>
    let cart = [];

    function renderCart() {
        let cartList = document.getElementById("cart-list");
        cartList.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            total += item.price * item.qty;
            cartList.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.name}</strong><br>
                        Rp ${item.price} x ${item.qty} = Rp ${item.price * item.qty}
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="decreaseQty(${index})">-</button>
                        <span class="px-2">${item.qty}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="increaseQty(${index})">+</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">&times;</button>
                    </div>
                </li>
            `;
        });

        document.getElementById("total").value = total;
        document.getElementById("cart-input").value = JSON.stringify(cart);
        document.getElementById("cart-count").innerText = cart.length;

        // hitung kembalian
        let cash = parseInt(document.getElementById("cash").value) || 0;
        document.getElementById("change").value = cash - total >= 0 ? cash - total : 0;
    }

    function increaseQty(index) {
        cart[index].qty += 1;
        renderCart();
    }

    function decreaseQty(index) {
        if (cart[index].qty > 1) {
            cart[index].qty -= 1;
        } else {
            cart.splice(index, 1);
        }
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    document.querySelectorAll(".add-to-cart").forEach(btn => {
        btn.addEventListener("click", function() {
            let id = this.dataset.id;
            let name = this.dataset.name;
            let price = parseInt(this.dataset.price);

            let existing = cart.find(item => item.productId == id);
            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({
                    productId: id,
                    name: name,
                    price: price,
                    qty: 1
                });
            }
            renderCart();
        });
    });

    document.getElementById("cash").addEventListener("input", renderCart);
</script>
@endsection
