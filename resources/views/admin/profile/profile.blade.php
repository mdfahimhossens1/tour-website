@extends('layouts.admin')
@section('page')

<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="mb-3">
      <div class="card-header text-center">
        <h3><strong>My Profile</strong></h3>
        
      </div>
      <div class="p_circle_img text-center mb-3 mt-3">
        <img
        src="{{ $user->photo ? asset('uploads/users/'.$user->photo) : asset('contents/admin/images/avatar.png') }}"
        width="220" height="220" style="border-radius:50%; object-fit:cover;"
        alt="avatar">
      </div>
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-control">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Role</label>
            <input type="text" value="{{ $user->role->role_name ?? 'User' }}" class="form-control" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Profile Photo</label>
            <input type="file" name="photo" class="form-control">
            @error('photo') <small class="text-danger">{{ $message }}</small> @enderror

            <div class="mt-3 d-flex align-items-center gap-2">
              <img
                src="{{ $user->photo ? asset('uploads/users/'.$user->photo) : asset('contents/admin/images/avatar.png') }}"
                width="52" height="52" style="border-radius:50%; object-fit:cover;"
                alt="avatar">
              <small class="text-muted">JPG/PNG/WebP (max 2MB)</small>
            </div>
          </div>

          <button class="btn btn-primary">Update Profile</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
