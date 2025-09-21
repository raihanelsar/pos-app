@extends('layouts.app')
@section('title', 'Laporan Transaksi')

@section('content')
<div class="pagetitle">
    <h1>Laporan Transaksi</h1>
</div>

<section class="section">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Daftar Transaksi</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><span class="badge bg-primary">{{ $order->order_code }}</span></td>
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->customer_name ?? '-' }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('pimpinan.detailLaporan', $order->id) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @if($orders->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Belum ada transaksi</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
