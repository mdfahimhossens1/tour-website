@extends('layouts.admin')

@section('title', 'Tax Management')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                Tax Management
            </h4>

            <p class="text-muted mb-0">
                Manage booking and vendor payout tax rules.
            </p>
        </div>

        <a href="{{ route('admin.tax-rules.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            Add Tax Rule

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                <i class="fas fa-exclamation-triangle me-2"></i>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Tax Rules
                            </p>

                            <h3 class="mb-0 fw-bold">
                                {{ $stats['total'] }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-primary bg-opacity-10
                                    text-primary d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="fas fa-file-invoice-dollar fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Active Rules
                            </p>

                            <h3 class="mb-0 fw-bold text-success">
                                {{ $stats['active'] }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-success bg-opacity-10
                                    text-success d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="fas fa-check-circle fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Inactive --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Inactive Rules
                            </p>

                            <h3 class="mb-0 fw-bold text-secondary">
                                {{ $stats['inactive'] }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-secondary bg-opacity-10
                                    text-secondary d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="fas fa-ban fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Percentage --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Percentage / Fixed
                            </p>

                            <h3 class="mb-0 fw-bold">

                                {{ $stats['percentage'] }}

                                <span class="text-muted fs-6">
                                    /
                                </span>

                                {{ $stats['fixed'] }}

                            </h3>

                        </div>

                        <div class="rounded-circle bg-warning bg-opacity-10
                                    text-warning d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="fas fa-percentage fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.tax-rules.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Search by name, code..."
                                   value="{{ request('search') }}">

                        </div>

                    </div>


                    {{-- Type --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Type
                        </label>

                        <select name="type"
                                class="form-select">

                            <option value="">
                                All Types
                            </option>

                            <option value="percentage"
                                {{ request('type') === 'percentage' ? 'selected' : '' }}>
                                Percentage
                            </option>

                            <option value="fixed"
                                {{ request('type') === 'fixed' ? 'selected' : '' }}>
                                Fixed
                            </option>

                        </select>

                    </div>


                    {{-- Applies To --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Applies To
                        </label>

                        <select name="applies_to"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="booking"
                                {{ request('applies_to') === 'booking' ? 'selected' : '' }}>
                                Booking
                            </option>

                            <option value="vendor_payout"
                                {{ request('applies_to') === 'vendor_payout' ? 'selected' : '' }}>
                                Vendor Payout
                            </option>

                            <option value="both"
                                {{ request('applies_to') === 'both' ? 'selected' : '' }}>
                                Both
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-2 col-md-6 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary flex-fill">

                                <i class="fas fa-filter me-1"></i>
                                Filter

                            </button>

                            <a href="{{ route('admin.tax-rules.index') }}"
                               class="btn btn-light border"
                               title="Reset">

                                <i class="fas fa-redo"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Tax Rules Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Tax Rules
                    </h5>

                </div>

                <span class="badge bg-light text-dark border">

                    {{ $taxRules->total() }} Rules

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                #
                            </th>

                            <th>
                                Tax Rule
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Rate
                            </th>

                            <th>
                                Applies To
                            </th>

                            <th>
                                Priority
                            </th>

                            <th>
                                Effective Period
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($taxRules as $taxRule)

                            <tr>

                                {{-- ID --}}
                                <td class="px-3">

                                    <span class="text-muted">
                                        {{ $taxRules->firstItem() + $loop->index }}
                                    </span>

                                </td>


                                {{-- Tax Rule --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $taxRule->name }}
                                    </div>

                                    <small class="text-muted">

                                        <i class="fas fa-code me-1"></i>

                                        {{ $taxRule->code }}

                                    </small>

                                </td>


                                {{-- Type --}}
                                <td>

                                    @if($taxRule->type === 'percentage')

                                        <span class="badge bg-info bg-opacity-10 text-info">

                                            <i class="fas fa-percentage me-1"></i>

                                            Percentage

                                        </span>

                                    @else

                                        <span class="badge bg-warning bg-opacity-10 text-warning">

                                            <i class="fas fa-money-bill-wave me-1"></i>

                                            Fixed

                                        </span>

                                    @endif

                                </td>


                                {{-- Rate --}}
                                <td>

                                    <strong>

                                        @if($taxRule->type === 'percentage')

                                            {{ number_format((float) $taxRule->rate, 2) }}%

                                        @else

                                            {{ number_format((float) $taxRule->rate, 2) }}

                                        @endif

                                    </strong>

                                </td>


                                {{-- Applies To --}}
                                <td>

                                    @switch($taxRule->applies_to)

                                        @case('booking')

                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                Booking
                                            </span>

                                            @break

                                        @case('vendor_payout')

                                            <span class="badge bg-purple bg-opacity-10 text-dark">
                                                Vendor Payout
                                            </span>

                                            @break

                                        @case('both')

                                            <span class="badge bg-dark bg-opacity-10 text-dark">
                                                Both
                                            </span>

                                            @break

                                    @endswitch

                                </td>


                                {{-- Priority --}}
                                <td>

                                    <span class="badge bg-light text-dark border">
                                        {{ $taxRule->priority }}
                                    </span>

                                </td>


                                {{-- Effective Period --}}
                                <td>

                                    @if($taxRule->starts_at || $taxRule->ends_at)

                                        <div class="small">

                                            @if($taxRule->starts_at)

                                                <div>
                                                    <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                                    {{ $taxRule->starts_at->format('d M Y') }}
                                                </div>

                                            @else

                                                <div class="text-muted">
                                                    No start date
                                                </div>

                                            @endif


                                            @if($taxRule->ends_at)

                                                <div>
                                                    <i class="fas fa-calendar-check me-1 text-muted"></i>
                                                    {{ $taxRule->ends_at->format('d M Y') }}
                                                </div>

                                            @else

                                                <div class="text-muted">
                                                    No end date
                                                </div>

                                            @endif

                                        </div>

                                    @else

                                        <span class="text-muted small">
                                            Always effective
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($taxRule->is_active)

                                        <span class="badge bg-success">

                                            <i class="fas fa-check-circle me-1"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            <i class="fas fa-ban me-1"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="text-end px-3">

                                    <div class="dropdown">

                                        <button class="btn btn-sm btn-light border"
                                                type="button"
                                                data-bs-toggle="dropdown">

                                            <i class="fas fa-ellipsis-v"></i>

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">

                                            {{-- View --}}
                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.tax-rules.show', $taxRule->id) }}">

                                                    <i class="fas fa-eye text-info me-2"></i>

                                                    View

                                                </a>

                                            </li>


                                            {{-- Edit --}}
                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.tax-rules.edit', $taxRule->id) }}">

                                                    <i class="fas fa-edit text-primary me-2"></i>

                                                    Edit

                                                </a>

                                            </li>


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            {{-- Toggle Status --}}
                                            <li>

                                                <form method="POST"
                                                      action="{{ route('admin.tax-rules.toggle-status', $taxRule->id) }}">

                                                    @csrf

                                                    <button type="submit"
                                                            class="dropdown-item">

                                                        @if($taxRule->is_active)

                                                            <i class="fas fa-toggle-off text-warning me-2"></i>

                                                            Deactivate

                                                        @else

                                                            <i class="fas fa-toggle-on text-success me-2"></i>

                                                            Activate

                                                        @endif

                                                    </button>

                                                </form>

                                            </li>


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            {{-- Delete --}}
                                            <li>

                                                <button type="button"
                                                        class="dropdown-item text-danger"
                                                        onclick="confirmDelete({{ $taxRule->id }}, '{{ addslashes($taxRule->name) }}')">

                                                    <i class="fas fa-trash-alt me-2"></i>

                                                    Delete

                                                </button>

                                            </li>

                                        </ul>

                                    </div>


                                    {{-- Delete Form --}}
                                    <form id="delete-form-{{ $taxRule->id }}"
                                          method="POST"
                                          action="{{ route('admin.tax-rules.destroy', $taxRule->id) }}"
                                          class="d-none">

                                        @csrf
                                        @method('DELETE')

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fas fa-file-invoice-dollar fa-3x mb-3 opacity-50"></i>

                                        <h5>
                                            No Tax Rules Found
                                        </h5>

                                        <p class="mb-3">
                                            No tax rules match your current filters.
                                        </p>

                                        <a href="{{ route('admin.tax-rules.create') }}"
                                           class="btn btn-primary">

                                            <i class="fas fa-plus me-1"></i>
                                            Create Tax Rule

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($taxRules->hasPages())

            <div class="card-footer bg-white">

                {{ $taxRules->links() }}

            </div>

        @endif

    </div>

</div>


{{-- Delete Confirmation Modal --}}
<div class="modal fade"
     id="deleteTaxModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title text-danger">

                    <i class="fas fa-trash-alt me-2"></i>

                    Delete Tax Rule

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <p class="mb-1">
                    Are you sure you want to delete this tax rule?
                </p>

                <strong id="deleteTaxName"></strong>

                <div class="alert alert-warning mt-3 mb-0">

                    <i class="fas fa-exclamation-triangle me-2"></i>

                    This action cannot be undone.

                </div>

            </div>


            <div class="modal-footer">

                <button type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal">

                    Cancel

                </button>

                <button type="button"
                        class="btn btn-danger"
                        id="confirmDeleteBtn">

                    <i class="fas fa-trash-alt me-1"></i>

                    Delete

                </button>

            </div>

        </div>

    </div>

</div>


@endsection


@push('styles')

<style>

    .card {
        border-radius: 12px;
    }

    .table th {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .table td {
        font-size: 14px;
    }

    .table tbody tr {
        transition: background-color .15s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, .02);
    }

    .badge {
        font-weight: 500;
    }

    .bg-opacity-10 {
        --bs-bg-opacity: .1;
    }

    .dropdown-item {
        font-size: 14px;
    }

    .dropdown-item i {
        width: 18px;
    }

</style>

@endpush


@push('scripts')

<script>

    let deleteTaxId = null;

    function confirmDelete(id, name)
    {
        deleteTaxId = id;

        document.getElementById('deleteTaxName').textContent = name;

        const modalElement =
            document.getElementById('deleteTaxModal');

        const modal =
            new bootstrap.Modal(modalElement);

        modal.show();
    }


    document.getElementById('confirmDeleteBtn')
        .addEventListener('click', function () {

            if (!deleteTaxId) {
                return;
            }

            document
                .getElementById('delete-form-' + deleteTaxId)
                .submit();

        });

</script>

@endpush