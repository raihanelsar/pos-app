@extends('layouts.app')
@section('title', 'Dashboard Pimpinan')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
</div>

<section class="section dashboard">
    <div class="row text-white">

        <!-- Statistik Card -->
        @foreach ([
            'Produk' => $productCount,
            'Kategori' => $categoryCount,
            'Transaksi' => $transactionCount,
            'Pendapatan' => 'Rp ' . number_format($totalRevenue, 0, ',', '.')
        ] as $title => $value)
            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>
                        <p class="fs-4 fw-bold text-white">{{ $value }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Grafik Pendapatan Bulanan -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Grafik Pendapatan Bulanan</h5>
                    <div id="reportsChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 Produk Terlaris -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Top 5 Produk Terlaris</h5>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topSelling as $item)
                                    <tr>
                                        <td>{{ $item->product->product_name }}</td>
                                        <td>{{ $item->total_qty }}</td>
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
    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const options = {
        chart: { height: 350, type: 'line', toolbar: { show: false }, zoom: { enabled: false } },
        series: [{ name: 'Pendapatan', data: @json($chartData) }],
        xaxis: { categories: @json($chartLabels), labels: { rotate: -45 } },
        stroke: { curve: 'smooth', width: 2 },
        markers: { size: 4 },
        colors: ['#4154f1'],
        fill: { type: "gradient", gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] } },
        dataLabels: { enabled: false },
        tooltip: {
            x: { format: 'yyyy-MM' },
            y: { formatter: value => "Rp " + new Intl.NumberFormat('id-ID').format(value) }
        }
    };
    new ApexCharts(document.querySelector("#reportsChart"), options).render();
});
</script>
@endsection
