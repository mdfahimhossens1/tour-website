@extends('layouts.admin')
@section('title','General Settings')
@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h5>General Settings</h5>

        <a href="{{ route('admin.settings.index') }}" class="btn btn-dark btn-sm">
            Back
        </a>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- Site Name --}}
            <div class="mb-3">
                <label>Site Name</label>
                <input type="text" name="site_name" class="form-control"
                       value="{{ $settings['site_name'] ?? '' }}">
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="site_email" class="form-control"
                       value="{{ $settings['site_email'] ?? '' }}">
            </div>

            {{-- Phone --}}
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control"
                       value="{{ $settings['phone'] ?? '' }}">
            </div>

            {{-- Address --}}
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control">{{ $settings['address'] ?? '' }}</textarea>
            </div>

            {{-- Logo --}}
            <div class="mb-3">
                <label>Logo</label>
                <input type="file" name="logo" class="form-control">
            </div>

            <button class="btn btn-dark">
                Save Settings
            </button>

        </form>

    </div>

</div>

@endsection