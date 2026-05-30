@extends('layouts.admin')
@section('title','Payment Settings')
@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h5>Payment Settings</h5>

        <a href="{{ route('admin.settings.index') }}" class="btn btn-dark btn-sm">
            Back
        </a>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.settings.payment.update') }}">
            @csrf

            {{-- bKash --}}
            <div class="mb-3">
                <label>bKash Number</label>
                <input type="text" name="bkash_number" class="form-control"
                       value="{{ $settings['bkash_number'] ?? '' }}">
            </div>

            {{-- Nagad --}}
            <div class="mb-3">
                <label>Nagad Number</label>
                <input type="text" name="nagad_number" class="form-control"
                       value="{{ $settings['nagad_number'] ?? '' }}">
            </div>

            {{-- Stripe --}}
            <div class="mb-3">
                <label>Stripe Status</label>
                <select name="stripe_status" class="form-control">
                    <option value="1" {{ ($settings['stripe_status'] ?? '') == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ ($settings['stripe_status'] ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            {{-- PayPal --}}
            <div class="mb-3">
                <label>PayPal Status</label>
                <select name="paypal_status" class="form-control">
                    <option value="1" {{ ($settings['paypal_status'] ?? '') == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ ($settings['paypal_status'] ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button class="btn btn-success">
                Save Payment Settings
            </button>

        </form>

    </div>

</div>

@endsection