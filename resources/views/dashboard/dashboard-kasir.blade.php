@extends('layouts.app')
@section('title', 'Dashboard Kasir')

@section('content')
<div class="pagetitle">
  <h1>Dashboard</h1>
</div>

<section class="section dashboard">
  <div class="row">

    <!-- Left side -->
    <div class="col-lg-8">
      <div class="row">

        <!-- Orders Today -->
        <div class="col-md-4">
          <div class="card info-card sales-card">
            <div class="card-body">
              <h5 class="card-title">Orders <span>| Today</span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="mdi mdi-cart"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $todayOrders }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Revenue Today -->
        <div class="col-md-4">
          <div class="card info-card revenue-card">
            <div class="card-body">
              <h5 class="card-title">Revenue <span>| Today</span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="mdi mdi-currency-usd"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ 'Rp. ' . number_format($todaySales, 0, ',', '.') }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Active Products -->
        <div class="col-md-4">
          <div class="card info-card customers-card">
            <div class="card-body">
              <h5 class="card-title">Produk Aktif</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="mdi mdi-package-variant"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $activeProducts }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-12">
          <div class="card recent-sales overflow-auto">
            <div class="card-body">
              <h5 class="card-title">Recent Orders <span>| Latest 5</span></h5>
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Kembalian</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($orders as $order)
                    <tr>
                      <td>{{ $order->order_code }}</td>
                      <td>{{ $order->order_date->format('d/m/Y H:i') }}</td>
                      <td>{{ 'Rp. ' . number_format($order->order_amount, 0, ',', '.') }}</td>
                      <td>{{ 'Rp. ' . number_format($order->order_change, 0, ',', '.') }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center">Belum ada transaksi</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              <div class="mt-2">
                {{ $orders->links() }}
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Right side -->
    <div class="col-lg-4">
      <div class="card top-selling overflow-auto">
        <div class="card-body pb-0">
          <h5 class="card-title">Top Products <span>| Best Seller</span></h5>
          <table class="table table-borderless">
            <thead>
              <tr>
                <th>Produk</th>
                <th>Terjual</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topProducts as $product)
                <tr>
                  <td><span class="text-primary fw-bold">{{ $product->product->product_name ?? 'Unknown' }}</span></td>
                  <td class="fw-bold">{{ $product->sold_qty }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="2" class="text-center">Belum ada data</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
