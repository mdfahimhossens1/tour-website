@extends('layouts.admin')
@section('page')

<div class="row">
  <div class="col-12">
    <div class="card mb-3">

      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-8 card_title_part">
            <i class="fas fa-boxes me-2"></i> Inventory Management
          </div>
          <div class="col-md-4 col-4 card_button_part text-end">
            <a href="{{ route('dashboard.inventory.logs') }}" class="btn btn-sm btn-dark">
              Logs
            </a>
          </div>
        </div>
      </div>

      <div class="card-body">

        <div class="">
          <table id="myTable" class="table table-bordered table-striped table-hover align-middle mb-0 custom_table">
            <thead class="table-dark">
              <tr>
                <th>Product</th>
                <th style="width:120px;">Stock</th>
                <th style="width:140px;">Status</th>
                <th style="width:320px;">Action</th>
              </tr>
            </thead>

            <tbody>
              @foreach($products as $product)
              <tr>
                <td>{{ $product->name }}</td>

                <td>{{ $product->stock }}</td>

                <td>
                  @if($product->stock <= $product->low_stock_limit)
                    <span class="badge bg-danger">Low Stock</span>
                  @else
                    <span class="badge bg-success">In Stock</span>
                  @endif
                </td>

                <td>
                  <form action="{{ route('dashboard.inventory.update', $product->id) }}" 
                        method="POST" 
                        class="row g-2 align-items-center">
                    @csrf

                    <div class="col-md-4">
                      <select name="type" class="form-select form-select-sm">
                        <option value="in">Stock In</option>
                        <option value="out">Stock Out</option>
                      </select>
                    </div>

                    <div class="col-md-4">
                      <input type="number" 
                             name="quantity" 
                             class="form-control form-control-sm"
                             placeholder="Qty"
                             min="1">
                    </div>

                    <div class="col-md-4">
                      <button type="submit" class="btn btn-sm btn-primary w-100">
                        Update
                      </button>
                    </div>

                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>

          </table>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
