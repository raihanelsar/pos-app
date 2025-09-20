@extends('layouts.app')
@section('title', 'Laporan Transaksi')

@section('content')
<section class="row mt-2">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-body text-white">

                <div class="pagetitle mb-4 text-center">
                    <h3 class="fw-bold text-uppercase">
                        Laporan Transaksi
                    </h3>
                    <p class="text-muted">Filter laporan berdasarkan tanggal, minggu, bulan atau custom range</p>
                </div>

                <!-- Filter -->
                <div class="mb-4 d-flex align-items-center flex-wrap">
                    <div class="me-3">
                        <label class="fw-bold me-2">Preset Filter:</label>
                        <select id="preset-filter" class="form-select d-inline-block w-auto">
                            <option value="">-- Select --</option>
                            <option value="daily">Daily (Today)</option>
                            <option value="weekly">Weekly (Last 7 Days)</option>
                            <option value="monthly">Monthly (Last 30 Days)</option>
                        </select>
                    </div>

                    <div class="me-3">
                        <label class="fw-bold me-2">Start Date:</label>
                        <input type="text" id="start-date" class="form-control d-inline-block w-auto" autocomplete="off">
                    </div>

                    <div class="me-3">
                        <label class="fw-bold me-2">End Date:</label>
                        <input type="text" id="end-date" class="form-control d-inline-block w-auto" autocomplete="off">
                    </div>

                    <div>
                        <button id="reset-filter" class="btn btn-outline-secondary">
                            <i class="fas fa-refresh"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="tabelorder" class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode Order</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Kembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr data-href="{{ route('pimpinan.detailLaporan', $order->id) }}">
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->customer_name ?? '-' }}</td>
                                    <td class="text-success">
                                    Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $order->order_date }}</td>
                                    <td>Rp {{ number_format($order->order_change ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    $("#start-date, #end-date").datepicker({
        dateFormat: 'yy-mm-dd'
    });

    var table = $('#tabelorder').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'csvHtml5', text: '<i class="fas fa-file-csv"></i> CSV' },
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel' },
            { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i> PDF' },
            { extend: 'print', text: '<i class="fas fa-print"></i> Print' }
        ]
    });

    $('#tabelorder tbody').on('click', 'tr', function () {
        var href = $(this).data('href');
        if (href) window.location = href;
    });

    function applyPreset(preset) {
        var today = new Date();
        var start, end;

        if (preset === "daily") start = end = today;
        if (preset === "weekly") { start = new Date(); start.setDate(today.getDate() - 6); end = today; }
        if (preset === "monthly") { start = new Date(); start.setDate(today.getDate() - 29); end = today; }

        if (start && end) {
            $("#start-date").val(formatDate(start));
            $("#end-date").val(formatDate(end));
            table.draw();
        }
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }

    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var startDate = $('#start-date').val();
            var endDate = $('#end-date').val();
            var orderDate = data[3];
            if (startDate) startDate = new Date(startDate);
            if (endDate) endDate = new Date(endDate);
            var orderDateObj = new Date(orderDate);

            if ((!startDate && !endDate) ||
                (!startDate && orderDateObj <= endDate) ||
                (startDate <= orderDateObj && !endDate) ||
                (startDate <= orderDateObj && orderDateObj <= endDate)) {
                return true;
            }
            return false;
        }
    );

    $('#preset-filter').on('change', function () {
        var preset = $(this).val();
        if (preset) applyPreset(preset);
    });

    $('#start-date, #end-date').change(function () {
        $('#preset-filter').val('');
        table.draw();
    });

    $('#reset-filter').click(function () {
        $('#start-date, #end-date, #preset-filter').val('');
        table.search('').columns().search('').draw();
        $("#start-date, #end-date").datepicker("setDate", null);
    });
});
</script>
@endsection
