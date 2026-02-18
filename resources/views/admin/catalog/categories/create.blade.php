@extends('layouts.admin')
@section('page')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div><i class="fas fa-plus-circle me-2"></i> Add Category</div>
    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-sm btn-dark">All Categories</a>
  </div>

  <div class="card-body">
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <form method="POST" action="{{ route('dashboard.categories.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Category Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Parent Category</label>
        <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
          <option value="">— None —</option>
          @foreach($parents as $p)
            <option value="{{ $p->id }}" {{ old('parent_id')==$p->id?'selected':'' }}>
              {{ $p->name }}
            </option>
          @endforeach
        </select>
        @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Sort Order</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control @error('sort_order') is-invalid @enderror">
          @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
            <option value="1" {{ old('is_active','1')=='1'?'selected':'' }}>Active</option>
            <option value="0" {{ old('is_active')=='0'?'selected':'' }}>Inactive</option>
          </select>
          @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <button class="btn btn-success">Save Category</button>
    </form>
  </div>
</div>

@endsection
