@extends('layouts.admin')
@section('page')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <b>General Settings</b>
    <div class="d-flex gap-2">
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard.settings.payment') }}">Payment</a>
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard.settings.inventory') }}">Inventory</a>
    </div>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ route('dashboard.settings.general.update') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Site Name</label>
        <input class="form-control" name="site_name" value="{{ old('site_name', $data['site_name']) }}">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Currency</label>
          <input class="form-control" name="currency" value="{{ old('currency', $data['currency']) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Currency Symbol</label>
          <input class="form-control" name="currency_symbol" value="{{ old('currency_symbol', $data['currency_symbol']) }}">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Support Email</label>
          <input class="form-control" name="support_email" value="{{ old('support_email', $data['support_email']) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Support Phone</label>
          <input class="form-control" name="support_phone" value="{{ old('support_phone', $data['support_phone']) }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Shop Address</label>
        <input class="form-control" name="shop_address" value="{{ old('shop_address', $data['shop_address']) }}">
      </div>

      <button class="btn btn-dark">Save</button>
    </form>
  </div>
</div>

@endsection
