@extends('layouts.admin')
@section('page')

@php
  $role = strtolower(optional(Auth::user()->role)->role_name ?? 'user');
@endphp

<div class="row">
  <div class="col-12">
    <div class="card mb-3">

      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-8 card_title_part">
            <i class="fas fa-list me-2"></i> Categories
          </div>
          <div class="col-md-4 col-4 card_button_part text-end">
            <a href="{{ route('dashboard.categories.create') }}" class="btn btn-sm btn-dark">
              <i class="fas fa-plus-circle me-1"></i> Add Category
            </a>
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
                <th>Img</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              @forelse($categories as $c)
                <tr>
                  <td>{{ $c->id }}</td>

                  <td>
                    @if($c->image)
                      <img src="{{ asset('uploads/categories/'.$c->image) }}"
                           style="width:46px;height:46px;object-fit:cover;border-radius:10px;">
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>

                  <td>{{ $c->name }}</td>

                  <td>
                    @php
                      $parent = $categories->firstWhere('id', $c->parent_id);
                    @endphp
                    {{ $parent?->name ?? '—' }}
                  </td>

                  <td>{{ $c->sort_order ?? 0 }}</td>

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
                          <a class="dropdown-item"
                             href="{{ route('dashboard.categories.edit', $c->id) }}">
                             Edit
                          </a>
                        </li>

                        @if($role !== 'manager')
                        <li>
                          <form action="{{ route('dashboard.categories.destroy', $c->id) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this category?')">
                            @csrf
                            <button type="submit"
                                    class="dropdown-item text-danger">
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
                  <td colspan="7" class="text-center">
                    No categories found.
                  </td>
                </tr>
              @endforelse
            </tbody>

          </table>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
