@extends('layouts.admin')
@section('page')

<div class="row">
  <div class="col-12">

    {{-- Order Details --}}
    <div class="card mb-3">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-8 card_title_part">
            <i class="fas fa-receipt me-2"></i> Order Details
            <span class="ms-2 badge bg-dark">{{ $order->order_number }}</span>
          </div>
          <div class="col-md-4 col-4 card_button_part text-end">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-dark">Back</a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row g-3">

          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-bold mb-2">Customer Info</div>
              <div><span class="text-muted">Name:</span> {{ $order->customer_name ?? '—' }}</div>
              <div><span class="text-muted">Phone:</span> {{ $order->customer_phone ?? '—' }}</div>
              <div><span class="text-muted">Email:</span> {{ $order->customer_email ?? '—' }}</div>

              <div class="mt-2">
                <div class="text-muted">Shipping Address</div>
                <div>{{ $order->shipping_address ?? '—' }}</div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-bold mb-2">Order Summary</div>
              <div><span class="text-muted">Status:</span> {{ $order->status ?? '—' }}</div>
              <div><span class="text-muted">Payment Method:</span> {{ $order->payment_method ?? '—' }}</div>
              <div><span class="text-muted">Payment Status:</span> {{ $order->payment_status ?? '—' }}</div>
              <div><span class="text-muted">Date:</span> {{ optional($order->created_at)->format('d M Y, h:i A') }}</div>

              <hr>

              <div class="d-flex justify-content-between">
                <span class="text-muted">Subtotal</span>
                <span>৳ {{ number_format((float)$order->subtotal, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Discount</span>
                <span>৳ {{ number_format((float)$order->discount, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Shipping</span>
                <span>৳ {{ number_format((float)$order->shipping, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Tax</span>
                <span>৳ {{ number_format((float)$order->tax, 2) }}</span>
              </div>

              <hr>

              <div class="d-flex justify-content-between fw-bold">
                <span>Grand Total</span>
                <span>৳ {{ number_format((float)$order->grand_total, 2) }}</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- Order Items --}}
    <div class="card mb-3">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-12 card_title_part">
            <i class="fas fa-boxes me-2"></i> Order Items
          </div>
        </div>
      </div>

      <div class="card-body">

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle mb-0 custom_table">
            <thead class="table-dark">
              <tr>
                <th style="width:80px;">#</th>
                <th>Product</th>
                <th style="width:140px;">SKU</th>
                <th style="width:150px;">Unit Price</th>
                <th style="width:90px;">Qty</th>
                <th style="width:160px;">Line Total</th>
              </tr>
            </thead>

            <tbody>
              @forelse($order->items as $i => $item)
                <tr>
                  <td>{{ $i+1 }}</td>

                  <td>
                    <div class="fw-semibold">{{ $item->product_name }}</div>
                    <div class="text-muted small">PID: {{ $item->product_id }}</div>
                  </td>

                  <td>{{ $item->sku ?? '—' }}</td>
                  <td>৳ {{ number_format((float)$item->unit_price, 2) }}</td>
                  <td>{{ $item->qty }}</td>
                  <td class="fw-semibold">৳ {{ number_format((float)$item->line_total, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">No items found for this order.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="row justify-content-end mt-3">
          <div class="col-md-5">
            <div class="border rounded p-3">
              <div class="d-flex justify-content-between">
                <span class="text-muted">Subtotal</span>
                <span>৳ {{ number_format((float)$order->subtotal, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Discount</span>
                <span>৳ {{ number_format((float)$order->discount, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Shipping</span>
                <span>৳ {{ number_format((float)$order->shipping, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Tax</span>
                <span>৳ {{ number_format((float)$order->tax, 2) }}</span>
              </div>

              <hr>

              <div class="d-flex justify-content-between fw-bold">
                <span>Grand Total</span>
                <span>৳ {{ number_format((float)$order->grand_total, 2) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

@endsection
