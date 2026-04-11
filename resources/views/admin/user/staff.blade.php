@extends('layouts.admin')
@section('page')

@php
  $role = strtolower(optional(Auth::user()->role)->role_name ?? 'user');
  $isManager = $role === 'manager';
@endphp

<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">

      <div class="card-header">
        <div class="row">
          <div class="col-md-8 col-8">
            <i class="fas fa-users-cog"></i> Staff Information
          </div>
          <div class="col-md-4 col-4 text-end">
            <a href="{{ route('dashboard.user.index') }}" class="btn btn-sm btn-dark"><i class="fas fa-arrow-left me-1"></i> Back</a>
            @if(!$isManager)
              <a href="{{ route('dashboard.user.add') }}" class="btn btn-sm btn-dark">
                <i class="fas fa-plus-circle"></i> Add Staff
              </a>
            @endif
          </div>
        </div>
      </div>

      <div class="card-body">

        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Username</th>
              <th>Role</th>
              <th>Status</th>
              <th>Photo</th>
              <th>Manage</th>
            </tr>
          </thead>

          <tbody>

            @foreach($staff as $s)
                        @php
            $rk = str_replace([' ', '-'], '_', strtolower(trim(optional($s->role)->role_name ?? 'user')));

            $badgeClass = match ($rk) {
                'super_admin' => 'bg-danger',
                'admin'       => 'bg-primary',
                'manager'     => 'bg-warning text-dark',
                default       => 'bg-secondary',
            };
            @endphp
            <tr>
              <td>{{ $s->name }}</td>
              <td>{{ $s->email }}</td>
              <td>{{ $s->username }}</td>

            <td>
            <span class="badge {{ $badgeClass }}">
                {{ $s->role->role_name ?? 'N/A' }}
            </span>
            </td>

              <td>
                @if($s->status)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-danger">Inactive</span>
                @endif
              </td>

              <td>
                @if($s->photo)
                  <img src="{{ asset('uploads/users/'.$s->photo) }}" width="45" height="45" style="border-radius:50%;object-fit:cover;">
                @else
                  <img src="{{ asset('contents/admin/images/avatar.jpg') }}" width="45" height="45" style="border-radius:50%;">
                @endif
              </td>

              <td>
                <div class="btn-group">
                  <button class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                    Manage
                  </button>

                  <ul class="dropdown-menu">
                    <li>
                      <a class="dropdown-item" href="{{ route('dashboard.user.show', $s->slug) }}">
                        View
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('dashboard.user.edit', $s->slug) }}">
                        Edit
                      </a>
                    </li>

                    @if($role !== 'manager')
                    <li>
                      <button class="dropdown-item text-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteUserModal"
                        data-userid="{{ $s->id }}">
                        Delete
                      </button>
                    </li>
                    @endif
                  </ul>
                </div>
              </td>

            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

@endsection
