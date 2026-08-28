@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- ============================================================
    PAGE HEADER
============================================================ --}}

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Transport Vehicles
        </h4>

        <p class="text-muted mb-0">
            Manage your vehicles and transport services.
        </p>
    </div>

    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#addVehicleModal"
    >
        <i class="bi bi-plus-lg me-1"></i>
        Add Vehicle
    </button>

</div>


{{-- ============================================================
    ALERT MESSAGES
============================================================ --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show" role="alert">

        <i class="bi bi-exclamation-circle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- ============================================================
    VALIDATION ERRORS
============================================================ --}}

@if($errors->any())

    <div class="alert alert-danger alert-dismissible fade show">

        <div class="fw-semibold mb-2">
            Please fix the following errors:
        </div>

        <ul class="mb-0 ps-3">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- ============================================================
    VEHICLE STATISTICS
============================================================ --}}

<div class="row g-3 mb-4">

    {{-- Total --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small mb-1">
                            Total Vehicles
                        </div>

                        <h4 class="fw-bold mb-0">
                            {{ $vehicles->total() }}
                        </h4>

                    </div>

                    <div
                        class="rounded-circle bg-primary bg-opacity-10
                               text-primary d-flex align-items-center
                               justify-content-center"
                        style="width:48px;height:48px;"
                    >
                        <i class="bi bi-car-front fs-5"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Approved --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small mb-1">
                            Approved Vehicles
                        </div>

                        <h4 class="fw-bold mb-0 text-success">
                            {{ $vehicles->where('status', 'approved')->count() }}
                        </h4>

                    </div>

                    <div
                        class="rounded-circle bg-success bg-opacity-10
                               text-success d-flex align-items-center
                               justify-content-center"
                        style="width:48px;height:48px;"
                    >
                        <i class="bi bi-check-circle fs-5"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Pending --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small mb-1">
                            Pending Approval
                        </div>

                        <h4 class="fw-bold mb-0 text-warning">
                            {{ $vehicles->where('status', 'pending')->count() }}
                        </h4>

                    </div>

                    <div
                        class="rounded-circle bg-warning bg-opacity-10
                               text-warning d-flex align-items-center
                               justify-content-center"
                        style="width:48px;height:48px;"
                    >
                        <i class="bi bi-clock fs-5"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Rejected --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small mb-1">
                            Rejected Vehicles
                        </div>

                        <h4 class="fw-bold mb-0 text-danger">
                            {{ $vehicles->where('status', 'rejected')->count() }}
                        </h4>

                    </div>

                    <div
                        class="rounded-circle bg-danger bg-opacity-10
                               text-danger d-flex align-items-center
                               justify-content-center"
                        style="width:48px;height:48px;"
                    >
                        <i class="bi bi-x-circle fs-5"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
    VEHICLE TABLE
============================================================ --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    My Vehicles
                </h5>

                <p class="text-muted small mb-0">
                    Vehicles added by your business.
                </p>

            </div>

        </div>

    </div>


    <div class="card-body p-0">

        @if($vehicles->count())

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                Vehicle
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Registration
                            </th>

                            <th>
                                Capacity
                            </th>

                            <th>
                                Price / Day
                            </th>

                            <th>
                                Driver
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end px-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach($vehicles as $vehicle)

                        <tr>

                            {{-- VEHICLE --}}
                            <td class="px-4">

                                <div class="d-flex align-items-center gap-3">

                                    <div
                                        class="rounded overflow-hidden bg-light
                                               d-flex align-items-center
                                               justify-content-center"
                                        style="width:60px;height:50px;"
                                    >

                                        @if($vehicle->featured_image)

                                            <img
                                                src="{{ asset('storage/' . $vehicle->featured_image) }}"
                                                alt="{{ $vehicle->name }}"
                                                style="width:100%;height:100%;object-fit:cover;"
                                            >

                                        @else

                                            <i class="bi bi-car-front text-secondary fs-4"></i>

                                        @endif

                                    </div>


                                    <div>

                                        <div class="fw-semibold">
                                            {{ $vehicle->name }}
                                        </div>

                                        @if($vehicle->brand || $vehicle->model)

                                            <div class="text-muted small">

                                                {{ $vehicle->brand }}

                                                @if($vehicle->brand && $vehicle->model)
                                                    -
                                                @endif

                                                {{ $vehicle->model }}

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- TYPE --}}
                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ ucfirst($vehicle->vehicle_type) }}

                                </span>

                            </td>


                            {{-- REGISTRATION --}}
                            <td>

                                @if($vehicle->registration_number)

                                    <span class="fw-medium">
                                        {{ $vehicle->registration_number }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- CAPACITY --}}
                            <td>

                                <i class="bi bi-people me-1 text-muted"></i>

                                {{ $vehicle->passenger_capacity }}

                                <small class="text-muted">
                                    seats
                                </small>

                            </td>


                            {{-- PRICE --}}
                            <td>

                                <div class="fw-bold">
                                    ৳{{ number_format($vehicle->price_per_day, 2) }}
                                </div>

                                <small class="text-muted">
                                    per day
                                </small>

                            </td>


                            {{-- DRIVER --}}
                            <td>

                                @if($vehicle->with_driver)

                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-person-check me-1"></i>
                                        Included
                                    </span>

                                @else

                                    <span class="badge bg-light text-muted border">
                                        Without Driver
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @switch($vehicle->status)

                                    @case('approved')

                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Approved
                                        </span>

                                        @break

                                    @case('pending')

                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="bi bi-clock me-1"></i>
                                            Pending
                                        </span>

                                        @break

                                    @case('rejected')

                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Rejected
                                        </span>

                                        @break

                                    @case('inactive')

                                        <span class="badge bg-secondary-subtle text-secondary">
                                            <i class="bi bi-pause-circle me-1"></i>
                                            Inactive
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-light text-dark border">
                                            {{ ucfirst($vehicle->status) }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- ACTION --}}
                            <td class="text-end px-4">

                                <div class="dropdown">

                                    <button
                                        class="btn btn-sm btn-light border"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>


                                    <ul class="dropdown-menu dropdown-menu-end">

                                        {{-- EDIT --}}
                                        <li>

                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editVehicleModal{{ $vehicle->id }}"
                                            >

                                                <i class="bi bi-pencil me-2"></i>

                                                Edit Vehicle

                                            </button>

                                        </li>


                                        {{-- TOGGLE ACTIVE --}}
                                        @if($vehicle->status === 'approved')

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('vendor.vehicles.toggle-status', $vehicle) }}"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item"
                                                    >

                                                        <i class="bi bi-pause-circle me-2"></i>

                                                        Set Inactive

                                                    </button>

                                                </form>

                                            </li>

                                        @elseif($vehicle->status === 'inactive')

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('vendor.vehicles.toggle-status', $vehicle) }}"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item"
                                                    >

                                                        <i class="bi bi-check-circle me-2"></i>

                                                        Activate Vehicle

                                                    </button>

                                                </form>

                                            </li>

                                        @endif


                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>


                                        {{-- DELETE --}}
                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route('vendor.vehicles.destroy', $vehicle) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this vehicle?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-danger"
                                                >

                                                    <i class="bi bi-trash me-2"></i>

                                                    Delete Vehicle

                                                </button>

                                            </form>

                                        </li>

                                    </ul>

                                </div>

                            </td>

                        </tr>


                        {{-- ====================================================
                            EDIT VEHICLE MODAL
                        ===================================================== --}}

                        <div
                            class="modal fade"
                            id="editVehicleModal{{ $vehicle->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-xl modal-dialog-centered">

                                <div class="modal-content border-0 shadow">

                                    <form
                                        method="POST"
                                        action="{{ route('vendor.vehicles.update', $vehicle) }}"
                                        enctype="multipart/form-data"
                                    >

                                        @csrf
                                        @method('PUT')


                                        <div class="modal-header">

                                            <div>

                                                <h5 class="modal-title fw-bold">
                                                    Edit Vehicle
                                                </h5>

                                                <small class="text-muted">
                                                    Update your vehicle information.
                                                </small>

                                            </div>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            <div class="row g-3">

                                                {{-- NAME --}}
                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Vehicle Name
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="name"
                                                        class="form-control"
                                                        value="{{ $vehicle->name }}"
                                                        placeholder="Toyota Axio"
                                                        required
                                                    >

                                                </div>


                                                {{-- TYPE --}}
                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Vehicle Type
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select
                                                        name="vehicle_type"
                                                        class="form-select"
                                                        required
                                                    >

                                                        <option value="">
                                                            Select vehicle type
                                                        </option>

                                                        @foreach([
                                                            'car' => 'Car',
                                                            'microbus' => 'Microbus',
                                                            'bus' => 'Bus',
                                                            'motorcycle' => 'Motorcycle',
                                                            'bike' => 'Bike',
                                                            'cng' => 'CNG / Auto',
                                                            'pickup' => 'Pickup',
                                                            'hiace' => 'Hiace',
                                                            'other' => 'Other',
                                                        ] as $value => $label)

                                                            <option
                                                                value="{{ $value }}"
                                                                @selected($vehicle->vehicle_type === $value)
                                                            >
                                                                {{ $label }}
                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                {{-- BRAND --}}
                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Brand
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="brand"
                                                        class="form-control"
                                                        value="{{ $vehicle->brand }}"
                                                        placeholder="Toyota, Honda, Yamaha..."
                                                    >

                                                </div>


                                                {{-- MODEL --}}
                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Model
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="model"
                                                        class="form-control"
                                                        value="{{ $vehicle->model }}"
                                                        placeholder="Axio, Noah, Civic..."
                                                    >

                                                </div>


                                                {{-- REGISTRATION --}}
                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Registration Number
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="registration_number"
                                                        class="form-control"
                                                        value="{{ $vehicle->registration_number }}"
                                                        placeholder="Dhaka Metro-GA..."
                                                    >

                                                </div>


                                                {{-- CAPACITY --}}
                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Passenger Capacity
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="passenger_capacity"
                                                        class="form-control"
                                                        min="1"
                                                        value="{{ $vehicle->passenger_capacity }}"
                                                        required
                                                    >

                                                </div>


                                                {{-- PRICE PER DAY --}}
                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Price Per Day (৳)
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="price_per_day"
                                                        class="form-control"
                                                        min="0"
                                                        step="0.01"
                                                        value="{{ $vehicle->price_per_day }}"
                                                        required
                                                    >

                                                </div>


                                                {{-- PRICE PER HOUR --}}
                                                <div class="col-md-6">

                                                    <label class="form-label fw-semibold">
                                                        Price Per Hour (৳)
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="price_per_hour"
                                                        class="form-control"
                                                        min="0"
                                                        step="0.01"
                                                        value="{{ $vehicle->price_per_hour }}"
                                                        placeholder="Optional"
                                                    >

                                                </div>


                                                {{-- LOCATION --}}
                                                <div class="col-md-4">

                                                    <label class="form-label fw-semibold">
                                                        Division
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="division"
                                                        class="form-control"
                                                        value="{{ $vehicle->division }}"
                                                        placeholder="Dhaka"
                                                    >

                                                </div>


                                                <div class="col-md-4">

                                                    <label class="form-label fw-semibold">
                                                        District
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="district"
                                                        class="form-control"
                                                        value="{{ $vehicle->district }}"
                                                        placeholder="Dhaka"
                                                    >

                                                </div>


                                                <div class="col-md-4">

                                                    <label class="form-label fw-semibold">
                                                        Area
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="area"
                                                        class="form-control"
                                                        value="{{ $vehicle->area }}"
                                                        placeholder="Gulshan"
                                                    >

                                                </div>


                                                {{-- ADDRESS --}}
                                                <div class="col-12">

                                                    <label class="form-label fw-semibold">
                                                        Address
                                                    </label>

                                                    <textarea
                                                        name="address"
                                                        rows="2"
                                                        class="form-control"
                                                        placeholder="Vehicle service address..."
                                                    >{{ $vehicle->address }}</textarea>

                                                </div>


                                                {{-- DRIVER --}}
                                                <div class="col-md-4">

                                                    <label class="form-label fw-semibold d-block">
                                                        Driver Service
                                                    </label>

                                                    <div class="form-check form-switch mt-2">

                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="with_driver"
                                                            value="1"
                                                            @checked($vehicle->with_driver)
                                                        >

                                                        <label class="form-check-label">
                                                            With Driver
                                                        </label>

                                                    </div>

                                                </div>


                                                {{-- FEATURED --}}
                                                <div class="col-md-4">

                                                    <label class="form-label fw-semibold d-block">
                                                        Featured Vehicle
                                                    </label>

                                                    <div class="form-check form-switch mt-2">

                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="is_featured"
                                                            value="1"
                                                            @checked($vehicle->is_featured)
                                                        >

                                                        <label class="form-check-label">
                                                            Mark as Featured
                                                        </label>

                                                    </div>

                                                </div>


                                                {{-- IMAGE --}}
                                                <div class="col-md-4">

                                                    <label class="form-label fw-semibold">
                                                        Vehicle Image
                                                    </label>

                                                    <input
                                                        type="file"
                                                        name="featured_image"
                                                        class="form-control"
                                                        accept="image/jpeg,image/png,image/webp"
                                                    >

                                                    @if($vehicle->featured_image)

                                                        <small class="text-muted d-block mt-1">
                                                            Existing image available. Upload only to replace it.
                                                        </small>

                                                    @else

                                                        <small class="text-muted d-block mt-1">
                                                            JPG, PNG or WEBP. Maximum 2MB.
                                                        </small>

                                                    @endif

                                                </div>


                                                {{-- DESCRIPTION --}}
                                                <div class="col-12">

                                                    <label class="form-label fw-semibold">
                                                        Description
                                                    </label>

                                                    <textarea
                                                        name="description"
                                                        rows="4"
                                                        class="form-control"
                                                        placeholder="Describe your vehicle, features, condition, service details..."
                                                    >{{ $vehicle->description }}</textarea>

                                                </div>

                                            </div>

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-light"
                                                data-bs-dismiss="modal"
                                            >
                                                Cancel
                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                            >

                                                <i class="bi bi-check-lg me-1"></i>

                                                Update Vehicle

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            <div class="p-3 border-top">

                {{ $vehicles->links() }}

            </div>

        @else

            {{-- EMPTY STATE --}}

            <div class="text-center py-5">

                <div
                    class="mx-auto mb-3 rounded-circle bg-primary bg-opacity-10
                           text-primary d-flex align-items-center
                           justify-content-center"
                    style="width:70px;height:70px;"
                >

                    <i class="bi bi-car-front fs-2"></i>

                </div>


                <h5 class="fw-bold">
                    No vehicles added yet
                </h5>

                <p class="text-muted mb-4">
                    Add your first vehicle to start offering transport services.
                </p>


                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#addVehicleModal"
                >

                    <i class="bi bi-plus-lg me-1"></i>

                    Add Your First Vehicle

                </button>

            </div>

        @endif

    </div>

</div>
```

</div>

{{-- ================================================================
ADD VEHICLE MODAL
================================================================ --}}

<div
    class="modal fade"
    id="addVehicleModal"
    tabindex="-1"
    aria-hidden="true"
>

```
<div class="modal-dialog modal-xl modal-dialog-centered">

    <div class="modal-content border-0 shadow">

        <form
            method="POST"
            action="{{ route('vendor.vehicles.store') }}"
            enctype="multipart/form-data"
        >

            @csrf


            <div class="modal-header">

                <div>

                    <h5 class="modal-title fw-bold">
                        Add New Vehicle
                    </h5>

                    <small class="text-muted">
                        Add a vehicle to your transport service.
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="row g-3">

                    {{-- NAME --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Vehicle Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="Toyota Axio"
                            required
                        >

                    </div>


                    {{-- TYPE --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Vehicle Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="vehicle_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select vehicle type
                            </option>

                            @foreach([
                                'car' => 'Car',
                                'microbus' => 'Microbus',
                                'bus' => 'Bus',
                                'motorcycle' => 'Motorcycle',
                                'bike' => 'Bike',
                                'cng' => 'CNG / Auto',
                                'pickup' => 'Pickup',
                                'hiace' => 'Hiace',
                                'other' => 'Other',
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(old('vehicle_type') === $value)
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BRAND --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Brand
                        </label>

                        <input
                            type="text"
                            name="brand"
                            class="form-control"
                            value="{{ old('brand') }}"
                            placeholder="Toyota, Honda, Yamaha..."
                        >

                    </div>


                    {{-- MODEL --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Model
                        </label>

                        <input
                            type="text"
                            name="model"
                            class="form-control"
                            value="{{ old('model') }}"
                            placeholder="Axio, Noah, Civic..."
                        >

                    </div>


                    {{-- REGISTRATION --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Registration Number
                        </label>

                        <input
                            type="text"
                            name="registration_number"
                            class="form-control"
                            value="{{ old('registration_number') }}"
                            placeholder="Dhaka Metro-GA..."
                        >

                    </div>


                    {{-- CAPACITY --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Passenger Capacity
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="passenger_capacity"
                            class="form-control"
                            min="1"
                            value="{{ old('passenger_capacity', 1) }}"
                            required
                        >

                    </div>


                    {{-- PRICE PER DAY --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Price Per Day (৳)
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="price_per_day"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="{{ old('price_per_day') }}"
                            placeholder="2500"
                            required
                        >

                    </div>


                    {{-- PRICE PER HOUR --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Price Per Hour (৳)
                        </label>

                        <input
                            type="number"
                            name="price_per_hour"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="{{ old('price_per_hour') }}"
                            placeholder="Optional"
                        >

                    </div>


                    {{-- DIVISION --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Division
                        </label>

                        <input
                            type="text"
                            name="division"
                            class="form-control"
                            value="{{ old('division') }}"
                            placeholder="Dhaka"
                        >

                    </div>


                    {{-- DISTRICT --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            District
                        </label>

                        <input
                            type="text"
                            name="district"
                            class="form-control"
                            value="{{ old('district') }}"
                            placeholder="Dhaka"
                        >

                    </div>


                    {{-- AREA --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Area
                        </label>

                        <input
                            type="text"
                            name="area"
                            class="form-control"
                            value="{{ old('area') }}"
                            placeholder="Gulshan"
                        >

                    </div>


                    {{-- ADDRESS --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Address
                        </label>

                        <textarea
                            name="address"
                            rows="2"
                            class="form-control"
                            placeholder="Vehicle service address..."
                        >{{ old('address') }}</textarea>

                    </div>


                    {{-- DRIVER --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold d-block">
                            Driver Service
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="with_driver"
                                value="1"
                                @checked(old('with_driver'))
                            >

                            <label class="form-check-label">
                                With Driver
                            </label>

                        </div>

                    </div>


                    {{-- FEATURED --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold d-block">
                            Featured Vehicle
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                @checked(old('is_featured'))
                            >

                            <label class="form-check-label">
                                Mark as Featured
                            </label>

                        </div>

                    </div>


                    {{-- IMAGE --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Vehicle Image
                        </label>

                        <input
                            type="file"
                            name="featured_image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp"
                        >

                        <small class="text-muted">
                            JPG, PNG or WEBP. Maximum 2MB.
                        </small>

                    </div>


                    {{-- DESCRIPTION --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            placeholder="Describe your vehicle, features, condition, service details..."
                        >{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="bi bi-plus-lg me-1"></i>

                    Add Vehicle

                </button>

            </div>

        </form>

    </div>

</div>
</div>

@endsection
