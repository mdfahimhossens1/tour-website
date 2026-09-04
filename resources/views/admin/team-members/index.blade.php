@extends('layouts.admin')

@section('title', 'Team Members')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-users me-2"></i>
                Team Members
            </h4>

            <p class="text-muted mb-0">
                Manage your website team members.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.team-members.create') }}"
               class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Add Team Member
            </a>
        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <strong>
                <i class="fas fa-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>

        </div>
    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Total Members
                            </div>

                            <h3 class="mb-0 fw-bold">
                                {{ number_format($totalMembers) }}
                            </h3>
                        </div>

                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="fas fa-users fa-lg"></i>
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
                            <div class="text-muted small mb-1">
                                Active Members
                            </div>

                            <h3 class="mb-0 fw-bold text-success">
                                {{ number_format($activeMembers) }}
                            </h3>
                        </div>

                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="fas fa-user-check fa-lg"></i>
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
                            <div class="text-muted small mb-1">
                                Inactive Members
                            </div>

                            <h3 class="mb-0 fw-bold text-secondary">
                                {{ number_format($inactiveMembers) }}
                            </h3>
                        </div>

                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle p-3">
                            <i class="fas fa-user-slash fa-lg"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Featured --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Featured Members
                            </div>

                            <h3 class="mb-0 fw-bold text-warning">
                                {{ number_format($featuredMembers) }}
                            </h3>
                        </div>

                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="fas fa-star fa-lg"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        SEARCH + FILTER
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.team-members.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Search by name, designation, email or phone...">

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2 col-md-4">

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


                    {{-- Featured --}}
                    <div class="col-lg-2 col-md-4">

                        <label class="form-label fw-semibold">
                            Featured
                        </label>

                        <select name="featured"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="yes"
                                {{ request('featured') === 'yes' ? 'selected' : '' }}>
                                Featured
                            </option>

                            <option value="no"
                                {{ request('featured') === 'no' ? 'selected' : '' }}>
                                Not Featured
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-3 col-md-4">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter me-1"></i>
                                Filter
                            </button>

                            <a href="{{ route('admin.team-members.index') }}"
                               class="btn btn-light border"
                               title="Reset Filters">
                                <i class="fas fa-redo"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        TEAM MEMBERS TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1 fw-bold">
                        Team Members
                    </h5>

                    <small class="text-muted">
                        Showing
                        {{ $teamMembers->firstItem() ?? 0 }}
                        -
                        {{ $teamMembers->lastItem() ?? 0 }}
                        of
                        {{ $teamMembers->total() }}
                        members
                    </small>
                </div>

                <div class="text-muted small mt-2 mt-md-0">
                    <i class="fas fa-sort-amount-down me-1"></i>
                    Sorted by display order
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($teamMembers->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3" style="width: 70px;">
                                    #
                                </th>

                                <th style="min-width: 260px;">
                                    Member
                                </th>

                                <th style="min-width: 180px;">
                                    Contact
                                </th>

                                <th class="text-center">
                                    Order
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                                <th class="text-center">
                                    Featured
                                </th>

                                <th class="text-end px-3" style="width: 180px;">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($teamMembers as $index => $member)

                                <tr>

                                    {{-- Number --}}
                                    <td class="px-3 text-muted">
                                        {{ $teamMembers->firstItem() + $index }}
                                    </td>


                                    {{-- Member --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            {{-- Image --}}
                                            <div class="me-3">

                                                @if($member->image)

                                                    <img src="{{ $member->image_url }}"
                                                         alt="{{ $member->name }}"
                                                         class="rounded-circle border"
                                                         width="52"
                                                         height="52"
                                                         style="object-fit: cover;">

                                                @else

                                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                                                         style="width:52px;height:52px;">

                                                        <i class="fas fa-user text-muted"></i>

                                                    </div>

                                                @endif

                                            </div>


                                            {{-- Name --}}
                                            <div>

                                                <div class="fw-semibold">
                                                    {{ $member->name }}
                                                </div>

                                                @if($member->designation)

                                                    <div class="small text-muted">
                                                        {{ $member->designation }}
                                                    </div>

                                                @else

                                                    <div class="small text-muted">
                                                        No designation
                                                    </div>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Contact --}}
                                    <td>

                                        @if($member->email)

                                            <div class="small mb-1">
                                                <i class="fas fa-envelope text-muted me-1"></i>
                                                {{ $member->email }}
                                            </div>

                                        @endif

                                        @if($member->phone)

                                            <div class="small text-muted">
                                                <i class="fas fa-phone text-muted me-1"></i>
                                                {{ $member->phone }}
                                            </div>

                                        @endif

                                        @if(!$member->email && !$member->phone)

                                            <span class="text-muted small">
                                                No contact info
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Order --}}
                                    <td class="text-center">

                                        <span class="badge bg-light text-dark border">
                                            {{ $member->sort_order }}
                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td class="text-center">

                                        <form method="POST"
                                              action="{{ route('admin.team-members.toggle-status', $member->id) }}"
                                              class="d-inline">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-sm {{ $member->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                    title="Click to change status">

                                                <i class="fas {{ $member->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>

                                                {{ $member->is_active ? 'Active' : 'Inactive' }}

                                            </button>

                                        </form>

                                    </td>


                                    {{-- Featured --}}
                                    <td class="text-center">

                                        <form method="POST"
                                              action="{{ route('admin.team-members.toggle-featured', $member->id) }}"
                                              class="d-inline">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-sm {{ $member->is_featured ? 'btn-warning' : 'btn-light border' }}"
                                                    title="Click to change featured status">

                                                <i class="fas fa-star {{ $member->is_featured ? '' : 'text-muted' }}"></i>

                                                @if($member->is_featured)
                                                    Featured
                                                @else
                                                    No
                                                @endif

                                            </button>

                                        </form>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end px-3">

                                        <div class="d-inline-flex gap-1">

                                            {{-- View --}}
                                            <a href="{{ route('admin.team-members.show', $member->id) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View">

                                                <i class="fas fa-eye"></i>

                                            </a>


                                            {{-- Edit --}}
                                            <a href="{{ route('admin.team-members.edit', $member->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            {{-- Delete --}}
                                            <form method="POST"
                                                  action="{{ route('admin.team-members.destroy', $member->id) }}"
                                                  class="d-inline delete-member-form">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width:80px;height:80px;">

                            <i class="fas fa-users fa-2x text-muted"></i>

                        </div>

                    </div>

                    <h5 class="fw-semibold">
                        No Team Members Found
                    </h5>

                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['search', 'status', 'featured']))
                            No team members match your current filters.
                        @else
                            You haven't added any team members yet.
                        @endif
                    </p>

                    @if(request()->hasAny(['search', 'status', 'featured']))

                        <a href="{{ route('admin.team-members.index') }}"
                           class="btn btn-light border me-2">
                            <i class="fas fa-redo me-1"></i>
                            Reset Filters
                        </a>

                    @endif

                    <a href="{{ route('admin.team-members.create') }}"
                       class="btn btn-primary">

                        <i class="fas fa-plus me-1"></i>
                        Add Team Member

                    </a>

                </div>

            @endif

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if($teamMembers->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $teamMembers->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
    DELETE CONFIRMATION
============================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-member-form').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const confirmed = confirm(
                'Are you sure you want to delete this team member?'
            );

            if (!confirmed) {
                event.preventDefault();
            }

        });

    });

});
</script>

@endsection