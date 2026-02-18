@extends('layouts.admin')
@section('page')

<div class="row">
  <div class="col-12">
    <div class="card mb-3">

      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-8 card_title_part">
            <i class="fas fa-clipboard-list me-2"></i> Inventory Logs
          </div>
          <div class="col-md-4 col-4 card_button_part text-end">
            <a href="{{ route('dashboard.inventory.index') }}" class="btn btn-sm btn-dark">
              Back to Inventory
            </a>
          </div>
        </div>
      </div>

      <div class="card-body">

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle mb-0 custom_table">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Note</th>
                <th>Date</th>
              </tr>
            </thead>

            <tbody>
              @forelse($logs as $log)
                <tr>
                  <td>{{ $log->id }}</td>
                  <td>{{ $log->product->name ?? '-' }}</td>
                  <td>{{ $log->product->sku ?? '-' }}</td>

                  <td>
                    @if($log->type === 'in')
                      <span class="badge bg-success">IN</span>
                    @else
                      <span class="badge bg-danger">OUT</span>
                    @endif
                  </td>

                  <td>{{ $log->quantity }}</td>
                  <td>{{ $log->note ?? '-' }}</td>
                  <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">No logs found</td>
                </tr>
              @endforelse
            </tbody>

          </table>
        </div>

        <div class="mt-3">
          {{ $logs->links() }}
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
