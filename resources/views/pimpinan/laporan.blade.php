@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<section class="row mt-2">
    <div class="col-lg-12">
        <div class="card">
  <div class="card-body">
    <div class="pagetitle mt-4 mb-4">
      <h1 align="center" style="text-transform: uppercase; font-weight: bold">Order Report (Daily, Weekly, Monthly, Custom Filter + Export)</h1>
    </div>

    <!-- Filter -->
    <div class="filter-container mb-4">
      <label for="preset-filter">Preset Filter:</label>
      <select id="preset-filter">
        <option value="">-- Select --</option>
        <option value="daily">Daily (Today)</option>
        <option value="weekly">Weekly (Last 7 Days)</option>
        <option value="monthly">Monthly (Last 30 Days)</option>
      </select>

      <label for="start-date" style="margin-left:20px;">Start Date:</label>
      <input type="text" id="start-date" autocomplete="off">

      <label for="end-date" style="margin-left:20px;">End Date:</label>
      <input type="text" id="end-date" autocomplete="off">

      <button id="reset-filter"><i class="fas fa-refresh"></i> Reset Filter</button>

    </div>

    <!-- Table -->
    <table id="tabelorder" class="display nowrap table" style="width:100%">
      <thead>
        <tr>
          <th>Order Code</th>
          <th>Amount</th>
          <th>Order Date</th>
          <th>Order Change</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($orders as $order)
          <tr data-href="{{ route('reportDetail', $order->id) }}" >
            <td>{{ $order->order_code }}</td>
            <td>{{ $order->formatted_amount }}</td>
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->formatted_change }}</td>
          </tr>
          @endforeach

            </tbody>
            </table>
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

<!-- Export Buttons -->

<script>
  $(document).ready(function () {
    $("#start-date, #end-date").datepicker({
      dateFormat: 'yy-mm-dd'
    });

    var table = $('#tabelorder').DataTable({
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'csvHtml5',
          text: '<i class="fas fa-file-csv"></i><span> CSV</span>',
          titleAttr: 'Export to CSV',
          className: 'btn-csv'
        },
        {
          extend: 'excelHtml5',
          text: '<i class="fas fa-file-excel"></i><span> Excel</span>',
          titleAttr: 'Export to Excel',
          className: 'btn-excel',
          exportOptions: {
            modifier: {
              page: 'all'
            }
          },
          customize: function (xlsx) {
            var sheet = xlsx.xl.worksheets['sheet1.xml'];
            $('row c[r^="C"]').each(function () {
              $(this).attr('s', '2');
            });

            $('row c[r^="D"]').each(function () {
              $(this).attr('t', 'n');
              $(this).attr('s', '18');
            });
          }
        },
        {
          extend: 'pdfHtml5',
          text: '<i class="fas fa-file-pdf"></i><span> PDF</span>',
          titleAttr: 'Export to PDF',
          className: 'btn-pdf',
          customize: function (doc) {
            var totalAmount = 0;

            $('#tabelorder tbody tr:visible').each(function () {
              var amount = parseFloat($(this).find('td:eq(1)').text().replace(/[^\d.-]/g, '')) || 0;
              totalAmount += amount;
            });

            doc.content.push({
              text: '\nTotal Amount: Rp ' + totalAmount.toLocaleString('id-ID'),
              alignment: 'right',
              margin: [0, 10, 0, 0],
              bold: true,
              fontSize: 12
            });
          }
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i><span> Print</span>',
          titleAttr: 'Print Table',
          className: 'btn-print'
        }
      ]
    });

    function applyPreset(preset) {
      var today = new Date();
      var start, end;

      if (preset === "daily") {
        start = end = today;
      }
      if (preset === "weekly") {
        start = new Date();
        start.setDate(today.getDate() - 6);
        end = today;
      }
      if (preset === "monthly") {
        start = new Date();
        start.setDate(today.getDate() - 29);
        end = today;
      }

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

    $('#tabelorder tbody').on('click', 'tr', function () {
      var href = $(this).data('href');
      if (href) {
        window.location = href;
      }
    });

    $.fn.dataTable.ext.search.push(
      function (settings, data, dataIndex) {
        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();
        var orderDate = data[2];

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
      if (preset) {
        applyPreset(preset);
      }
    });

    $('#start-date, #end-date').change(function () {
      $('#preset-filter').val('');
      table.draw();
    });

    $('#reset-filter').click(function () {
      $('#start-date').val('');
      $('#end-date').val('');
      $('#preset-filter').val('');

      table.search('').columns().search('').draw();

      $("#start-date").datepicker("setDate", null);
      $("#end-date").datepicker("setDate", null);
    });


  });
</script>
@endsection
