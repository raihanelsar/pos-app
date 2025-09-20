@extends('layouts.app')
@section('title', 'Detail Laporan')
@section('content')
<section class="py-5 bg-white rounded">
  <div class="container">
    <h1 class="display-4 fw-bold text-center">POS Sale Details</h1>
    <div class="row">
      <div class="col-12">
        <h2>Order Information</h2>
        <div class="row">
          <div class="col-md-6">
            <p><strong>Order Code:</strong> {{ $order->order_code }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</p>
            <p><strong>Order Status:</strong>
              @if($order->order_status == 0)
                <span class="badge bg-warning">Pending Payment</span>
              @else
                <span class="badge bg-success">Paid</span>
              @endif
            </p>
          </div>
        </div>
      </div>

      <div class="col-12">
        <h2>Order Details</h2>
        <div class="table-responsive" style="overflow-x: auto;">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->orderDetails as $item)
                <tr>
                  <td>{{ $item->product->product_name }}</td>
                  <td>{{ $item->qty }}</td>
                  <td>{{$item->product->formatted_price}}</td>
                  <td>{{$item->formatted_subtotal }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end fw-bold">Grand Total</td>
                <td> {{ $order->formatted_amount }}</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

        <div class="col-12">
          <h2>Payment Details</h2>
          <p><strong>Change:</strong> {{ $order->formatted_change }}</p>
        </div>
        {{-- <div class="col-12 text-center">
            <a href="{{ route('report.print', $order->id) }}" target="_blank" class="btn btn-success">Print Receipt</a>
        </div> --}}
    </div>
  </div>
</section>
@endsection
@section('script')
@endsection
