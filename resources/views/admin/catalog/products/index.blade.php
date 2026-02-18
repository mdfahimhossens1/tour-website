@extends('layouts.admin')
@section('page')
@php
$role = strtolower(optional(Auth::user()->role)->role_name ?? 'user');
@endphp
<div class="row">
   <div class="col-md-12">
      <div class="card mb-3">
         <div class="card-header">
            <div class="row">
               <div class="col-md-8 col-8 card_title_part">
                  <i class="fab fa-gg-circle"></i>Products
               </div>
               <div class="col-md-4 col-4 card_button_part">
                  <a href="{{ route('dashboard.products.create') }}" class="btn btn-sm btn-dark"><i class="fas fa-plus-circle"></i>Add Product</a>
               </div>
            </div>
         </div>
      </div>
      <div class="card-body">
         @if(session('success')) 
         <div class="alert alert-success">{{ session('success') }}</div>
         @endif
         @if(session('error')) 
         <div class="alert alert-danger">{{ session('error') }}</div>
         @endif
         <table class="table table-bordered table-striped table-hover custom_table">
            <thead class="table-dark">
               <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Category</th>
                  <th>SKU</th>
                  <th>Price</th>
                  <th>Sale</th>
                  <th>Stock</th>
                  <th>Low</th>
                  <th>Status</th>
                  <th>Img</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @forelse($products as $p)
               <tr>
                  <td>{{ $p->id }}</td>
                  <td>
                     <div class="fw-semibold">{{ $p->name }}</div>
                     <div class="text-muted small">{{ $p->slug }}</div>
                  </td>
                  <td>{{ $p->category?->name ?? '—' }}</td>
                  <td>{{ $p->sku }}</td>
                  <td>৳ {{ number_format($p->price, 2) }}</td>
                  <td>
                     @if($p->sale_price !== null)
                     ৳ {{ number_format($p->sale_price, 2) }}
                     @else
                     —
                     @endif
                  </td>
                  <td>
                     <span class="{{ $p->stock <= ($p->low_stock_threshold ?? 5) ? 'text-danger fw-bold' : '' }}">
                     {{ $p->stock }}
                     </span>
                  </td>
                  <td>{{ $p->low_stock_threshold ?? 5 }}</td>
                  <td>
                     @if($p->is_active)
                     <span class="badge bg-success">Active</span>
                     @else
                     <span class="badge bg-secondary">Inactive</span>
                     @endif
                  </td>
                  <td>
                     @if($p->image)
                     <img src="{{ asset('uploads/products/'.$p->image) }}"
                        style="width:46px;height:46px;object-fit:cover;border-radius:10px;">
                     @else
                     <span class="text-muted">—</span>
                     @endif
                  </td>
                  <td>
                     <div class="btn-group btn_group_manage" role="group">
                        <button type="button" class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Manage</button>
                        <ul class="dropdown-menu">
                           <li><a class="dropdown-item" href="{{ route('dashboard.products.edit', $p->id) }}">Edit</a></li>
                           <li>
                           <li>
                              @if($role !== 'manager')
                              <form action="{{ route('dashboard.products.destroy', $p->id) }}" method="POST" class="d-inline"
                                 onsubmit="return confirm('Delete this product?')">
                                 @csrf
                                 <button type="button" class="dropdown-item text-danger">Delete</button>
                              </form>
                              @endif
                           </li>
                        </ul>
                     </div>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="11" class="text-center">No products found.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
</div>
@endsection