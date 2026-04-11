@extends('layouts.admin')
@section('page')

@php
$roles = App\Models\Role::all();
$role = strtolower(optional(Auth::user()->role)->role_name ?? 'user');
$isSuperAdmin   = $role === 'super_admin';
$isAdmin   = $role === 'admin';
$isManager = $role === 'manager';
@endphp

<div class="row">
  <div class="col-12">
    <div class="card mb-3">

      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-8 card_title_part">
            <i class="fas fa-truck me-2"></i> Shipping Methods
          </div>
          <div class="col-md-4 col-4 card_button_part text-end">
            @if(!$isManager)
            <a href="{{ route('dashboard.shipping.create') }}" class="btn btn-sm btn-dark">
              <i class="fas fa-plus-circle me-1"></i> Add Shipping
            </a>
            @endif
          </div>
        </div>
      </div>

      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif


          <table id="myTable" class="table table-bordered table-striped table-hover align-middle mb-0 custom_table">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Zone</th>
                <th>Cost</th>
                <th>Min Order</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              @forelse($methods as $m)
                <tr>
                  <td>{{ $m->id }}</td>

                  <td>
                    <div class="fw-semibold">{{ $m->name }}</div>
                    <div class="text-muted small">{{ $m->slug }}</div>
                  </td>

                  <td>
                    @php
                      $z = $m->zone ?? 'nationwide';
                      $label = match($z){
                        'inside_city' => 'Inside City',
                        'outside_city' => 'Outside City',
                        'international' => 'International',
                        'pickup' => 'Pickup',
                        default => 'Nationwide',
                      };
                    @endphp
                    <span class="badge bg-info text-dark">{{ $label }}</span>
                  </td>

                  <td>৳ {{ number_format((float)$m->cost, 2) }}</td>

                  <td>{{ $m->min_order !== null ? '৳ '.number_format((float)$m->min_order,2) : '—' }}</td>

                  <td>{{ $m->sort_order }}</td>

                  <td>
                    @if($m->is_active)
                      <span class="badge bg-success">Active</span>
                    @else
                      <span class="badge bg-secondary">Inactive</span>
                    @endif
                  </td>

                  <td>
                    <div class="btn-group btn_group_manage" role="group">
                      <button type="button" class="btn btn-sm btn-dark dropdown-toggle"
                              data-bs-toggle="dropdown" aria-expanded="false">
                        Manage
                      </button>

                      <ul class="dropdown-menu">
                        <li>
                          <a href="{{ route('dashboard.shipping.edit', $m->id) }}" class="dropdown-item">
                            Edit
                          </a>
                        </li>

                        <li>
                          <form action="{{ route('dashboard.shipping.toggle', $m->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                              Toggle
                            </button>
                          </form>
                        </li>

                        @if($role !== 'manager')
                        <li>
                          <form action="{{ route('dashboard.shipping.destroy', $m->id) }}" method="POST"
                                onsubmit="return confirm('Delete this shipping method?')">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                              Delete
                            </button>
                          </form>
                        </li>
                        @endif
                      </ul>
                    </div>
                  </td>

                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center">No shipping methods found.</td>
                </tr>
              @endforelse
            </tbody>

          </table>
      </div>
    </div>
  </div>
</div>

@endsection
