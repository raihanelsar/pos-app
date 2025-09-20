@extends('layouts.app')
@section('title', 'Kasir Dashboard')

@section('content')
<section class="row text-white">
    <div class="col-12 col-lg-12">
        <div class="row">

            {{-- Card Produk --}}
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card bg-dark">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-package-variant text-info fs-2 me-3"></i>
                            <div>
                                <h6 class="text-white">Produk</h6>
                                <h5 class="font-extrabold text-white mb-0">
                                    {{ number_format($productCount ?? 0) }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Kategori --}}
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card bg-dark">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-shape text-warning fs-2 me-3"></i>
                            <div>
                                <h6 class="text-white">Kategori</h6>
                                <h5 class="font-extrabold text-white mb-0">
                                    {{ number_format($categoriesCount ?? 0) }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Transaksi --}}
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card bg-dark">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-receipt text-primary fs-2 me-3"></i>
                            <div>
                                <h6 class="text-white">Transaksi</h6>
                                <h5 class="font-extrabold text-white mb-0">
                                    {{ number_format($transactionCount ?? 0) }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Total Profit --}}
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card bg-dark">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-cash-multiple text-success fs-2 me-3"></i>
                            <div>
                                <h6 class="text-white">Total Profit</h6>
                                <h5 class="font-extrabold text-white mb-0">
                                    {{ 'Rp. '.number_format($totalProfitSum ?? 0,0,',','.') }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Grafik Riwayat Transaksi --}}
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card bg-dark">
                    <div class="card-header">
                        <h6 class="text-white mb-0">Riwayat Transaksi</h6>
                    </div>
                    <div class="card-body">
                        <div id="transactionChart"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        var currencyFormatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });

        var options = {
            chart: {
                type: "line",
                height: 350,
                toolbar: { show: false }
            },
            series: [{
                name: 'Total Profit',
                data: @json($totalProfits ?? [])
            }],
            xaxis: {
                categories: @json($dates ?? []),
                labels: { style: { colors: '#fff' } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#fff' },
                    formatter: function (val) {
                        return currencyFormatter.format(Math.round(val || 0));
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return currencyFormatter.format(Math.round(val || 0));
                    }
                }
            },
            colors: ['#00E396'],
            stroke: { curve: 'smooth' },
            grid: { borderColor: '#444' }
        };

        var chart = new ApexCharts(document.querySelector("#transactionChart"), options);
        chart.render();
    });
</script>
@endsection
