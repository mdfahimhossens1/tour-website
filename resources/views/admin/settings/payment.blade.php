@extends('layouts.admin')
@section('page')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <b>Payment Settings</b>
    <div class="d-flex gap-2">
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard.settings.general') }}">General</a>
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard.settings.inventory') }}">Inventory</a>
    </div>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ route('dashboard.settings.payment.update') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label fw-semibold">Enable Payment Methods</label>
        <div class="d-flex flex-wrap gap-3">
          @foreach($data['methods'] as $m)
            <label class="d-flex align-items-center gap-2">
              <input type="checkbox" name="enabled[]" value="{{ $m }}"
                {{ in_array($m, old('enabled', $data['enabled'])) ? 'checked' : '' }}>
              <span>{{ $m }}</span>
            </label>
          @endforeach
        </div>
        <div class="small text-muted mt-2">These should match <code>orders.payment_method</code> values.</div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Manual Payment Verification</label>
        <select class="form-control" name="manual_verify">
          <option value="0" {{ old('manual_verify', $data['manual_verify'])==0 ? 'selected' : '' }}>No</option>
          <option value="1" {{ old('manual_verify', $data['manual_verify'])==1 ? 'selected' : '' }}>Yes</option>
        </select>
        <div class="small text-muted mt-1">Manual verify ON থাকলে admin confirm না করা পর্যন্ত payment pending থাকবে (logic তুমি পরে add করতে পারো)।</div>
      </div>

      <hr>

      <div class="mb-2 fw-semibold">Payment Instructions (Optional)</div>
      <div class="row">
        @foreach($data['methods'] as $m)
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ $m }} Instruction</label>
            <textarea class="form-control" rows="3" name="instruction[{{ $m }}]">{{ old("instruction.$m", $data['instructions'][$m] ?? '') }}</textarea>
          </div>
        @endforeach
      </div>

      <button class="btn btn-dark">Save</button>
    </form>
  </div>
</div>

@endsection
