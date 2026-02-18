@extends('layouts.admin')
@section('page')

<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card mb-3">
      <div class="card-header">
        <strong>Change Password</strong>
      </div>

      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('dashboard.account.password') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control">
            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>

          <button class="btn btn-primary">Update Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
