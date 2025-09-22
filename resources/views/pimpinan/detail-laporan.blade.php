@extends('layouts.app')
@section('title', 'Detail Laporan')

@section('content')
<section class="row mt-2">
    <div class="col-lg-10 offset-lg-1">
        <div class="card shadow-sm">
            <div class="card-body text-white">

                <h3 class="text-center fw-bold text-uppercase mb-3">
                    Detail Transaksi
                </h3>
                <div class="text-center mb-4">
                    <span class="badge bg-primary">Kode: {{ $order->order_code }}</span>
                    <p class="mt-2 mb-0"><b>Tanggal:</b> {{ $order->order_date }}</p>
                    <p class="mb-0"><b>Customer:</b> {{ $order->customer_name ?? '-' }}</p>
                </div>

                <!-- Items Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->product_name }}</td>
                                    <td>{{ $item->formatted_price }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->formatted_subtotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="mt-4 text-end">
                    <h5><b>Total:</b> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>
                    <h6><b>Dibayar:</b> Rp {{ number_format($order->total_amount + $order->order_change, 0, ',', '.') }}</h6>
                    <h6><b>Kembalian:</b> Rp {{ number_format($order->order_change, 0, ',', '.') }}</h6>
                </div>

                <div class="mt-4 text-start">
                    <a href="{{ route('pimpinan.laporan') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
