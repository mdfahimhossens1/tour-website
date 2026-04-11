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
            <i class="fas fa-ticket-alt me-2"></i> Coupons
          </div>
          <div class="col-md-4 col-4 card_button_part text-end">
            @if(!$isManager)
            <a href="{{ route('dashboard.coupons.create') }}" class="btn btn-sm btn-dark">
              <i class="fas fa-plus-circle me-1"></i> Add Coupon
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

        <div class="">
          <table id="myTable" class="table table-bordered table-striped table-hover align-middle mb-0 custom_table">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Code</th>
                <th>Type</th>
                <th>Value</th>
                <th>Min Order</th>
                <th>Usage</th>
                <th>Validity</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              @forelse($coupons as $c)
              @php
                $now = now();
                $expired = $c->expires_at && $c->expires_at->lt($now);
              @endphp

                <tr>
                  <td>{{ $c->id }}</td>

                  <td class="fw-semibold">{{ $c->code }}</td>

                  <td>
                    <span class="badge bg-secondary text-uppercase">{{ $c->type }}</span>
                  </td>

                  <td>
                    @if($c->type === 'percent')
                      {{ rtrim(rtrim(number_format((float)$c->value, 2), '0'), '.') }}%
                    @else
                      ৳ {{ number_format((float)$c->value, 2) }}
                    @endif
                  </td>

                  <td>{{ $c->min_order_amount !== null ? '৳ '.number_format((float)$c->min_order_amount,2) : '—' }}</td>

                  <td>
                    {{ $c->used_count ?? 0 }}
                    @if($c->usage_limit) / {{ $c->usage_limit }} @else / ∞ @endif
                  </td>

                  <td class="small">
                    {{ $c->starts_at ? $c->starts_at->format('d M Y') : '—' }}
                    →
                    {{ $c->expires_at ? $c->expires_at->format('d M Y') : '—' }}
                    @if($expired)
                      <div class="mt-1"><span class="badge bg-danger">Expired</span></div>
                    @endif
                  </td>

                  <td>
                    @if($c->is_active)
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
                          <a href="{{ route('dashboard.coupons.edit', $c->id) }}" class="dropdown-item">
                            Edit
                          </a>
                        </li>

                        <li>
                          <form action="{{ route('dashboard.coupons.toggle', $c->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                              Toggle
                            </button>
                          </form>
                        </li>

                        @if($role !== 'manager')
                        <li>
                          <form action="{{ route('dashboard.coupons.destroy', $c->id) }}" method="POST"
                                onsubmit="return confirm('Delete this coupon?')">
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
                  <td colspan="9" class="text-center">No coupons found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $coupons->links() }}
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
