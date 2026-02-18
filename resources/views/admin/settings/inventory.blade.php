@extends('layouts.admin')
@section('page')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <b>Inventory Settings</b>
    <div class="d-flex gap-2">
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard.settings.general') }}">General</a>
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard.settings.payment') }}">Payment</a>
    </div>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ route('dashboard.settings.inventory.update') }}">
      @csrf

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Default Low Stock Threshold</label>
          <input type="number" class="form-control" name="default_low_stock_threshold"
                 value="{{ old('default_low_stock_threshold', $data['default_low_stock_threshold']) }}">
          <div class="small text-muted mt-1">New products এর default threshold হিসেবে use করতে পারো।</div>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Out of Stock Action</label>
          <select class="form-control" name="out_of_stock_action">
            <option value="disable" {{ old('out_of_stock_action', $data['out_of_stock_action'])==='disable' ? 'selected' : '' }}>
              Show but disable Add to Cart
            </option>
            <option value="hide" {{ old('out_of_stock_action', $data['out_of_stock_action'])==='hide' ? 'selected' : '' }}>
              Hide product from shop
            </option>
          </select>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Allow Backorder</label>
          <select class="form-control" name="allow_backorder">
            <option value="0" {{ old('allow_backorder', $data['allow_backorder'])==0 ? 'selected' : '' }}>No</option>
            <option value="1" {{ old('allow_backorder', $data['allow_backorder'])==1 ? 'selected' : '' }}>Yes</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Reduce Stock On</label>
        <select class="form-control" name="stock_reduce_on">
          <option value="placed" {{ old('stock_reduce_on', $data['stock_reduce_on'])==='placed' ? 'selected' : '' }}>Order Placed</option>
          <option value="paid" {{ old('stock_reduce_on', $data['stock_reduce_on'])==='paid' ? 'selected' : '' }}>Payment Paid</option>
          <option value="completed" {{ old('stock_reduce_on', $data['stock_reduce_on'])==='completed' ? 'selected' : '' }}>Order Completed</option>
        </select>
        <div class="small text-muted mt-1">তোমার order flow অনুযায়ী best হলো: <b>paid</b></div>
      </div>

      <button class="btn btn-dark">Save</button>
    </form>
  </div>
</div>

@endsection
