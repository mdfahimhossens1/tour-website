@extends('layouts.admin')

@section('title', 'All Tour Types')

@section('page')

<style>
    /* =========================================================
       MODAL BACKDROP
    ========================================================= */

    .modal-backdrop.show {
        backdrop-filter: blur(4px);
        background: rgba(0, 0, 0, .55);
    }


    /* =========================================================
       MODAL SHELL
    ========================================================= */

    .tour-modal .modal-dialog {
        max-width: 860px;
        margin: 1.5rem auto;
    }

    .tour-modal .modal-content {
        border: none;
        border-radius: 18px;
        box-shadow: 0 32px 80px rgba(0, 0, 0, .22);
        overflow: hidden;
    }


    /* =========================================================
       MODAL HEADER
    ========================================================= */

    .tour-modal .modal-header {
        background: linear-gradient(
            135deg,
            #1a1a2e 0%,
            #16213e 60%,
            #0f3460 100%
        );

        border: none;
        padding: 1.4rem 1.8rem;

        position: sticky;
        top: 0;

        z-index: 10;
    }

    .tour-modal .modal-header .modal-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: .3px;

        display: flex;
        align-items: center;
        gap: .55rem;
    }

    .tour-modal .modal-header .modal-title i {
        background: rgba(255, 255, 255, .12);

        width: 34px;
        height: 34px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: .85rem;
        flex-shrink: 0;
    }

    .tour-modal .btn-close {
        filter: invert(1) grayscale(1);
        opacity: .7;
    }

    .tour-modal .btn-close:hover {
        opacity: 1;
    }


    /* =========================================================
       MODAL BODY
    ========================================================= */

    .tour-modal .modal-body {
        padding: 1.8rem;
        background: #f8f9fd;

        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }

    .tour-modal .modal-body::-webkit-scrollbar {
        width: 5px;
    }

    .tour-modal .modal-body::-webkit-scrollbar-thumb {
        background: #cdd0e8;
        border-radius: 99px;
    }


    /* =========================================================
       MODAL FOOTER
    ========================================================= */

    .tour-modal .modal-footer {
        background: #f0f1f7;
        border-top: 1px solid #e3e5ef;

        padding: 1rem 1.8rem;

        position: sticky;
        bottom: 0;

        z-index: 10;

        display: flex;
        justify-content: flex-end;
        gap: .5rem;
    }


    /* =========================================================
       FORM ELEMENTS
    ========================================================= */

    .tour-modal label {
        font-size: .8rem;
        font-weight: 600;
        color: #4a4f6a;

        text-transform: uppercase;
        letter-spacing: .6px;

        margin-bottom: .35rem;
        display: block;
    }

    .tour-modal .form-control,
    .tour-modal .form-select,
    .tour-modal select.form-control {
        border: 1.5px solid #dde0ef;
        border-radius: 10px;

        font-size: .9rem;
        padding: .55rem .85rem;

        color: #2d3050;
        background: #fff;

        transition:
            border-color .2s,
            box-shadow .2s;

        width: 100%;
    }

    .tour-modal .form-control:focus,
    .tour-modal .form-select:focus,
    .tour-modal select.form-control:focus {
        border-color: #0f3460;

        box-shadow:
            0 0 0 3px rgba(15, 52, 96, .1);

        outline: none;
    }

    .tour-modal textarea.form-control {
        resize: vertical;
    }


    /* =========================================================
       MODAL SECTIONS
    ========================================================= */

    .modal-section {
        background: #fff;

        border: 1.5px solid #e3e5ef;
        border-radius: 14px;

        padding: 1.2rem 1.4rem;
        margin-bottom: 1.1rem;
    }

    .modal-section:last-child {
        margin-bottom: 0;
    }

    .modal-section-title {
        font-size: .72rem;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .8px;

        color: #0f3460;

        margin-bottom: 1rem;

        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .modal-section-title::after {
        content: '';

        flex: 1;

        height: 1.5px;

        background:
            linear-gradient(
                to right,
                #e3e5ef,
                transparent
            );

        margin-left: .4rem;
    }


    /* =========================================================
       IMAGE PREVIEW
    ========================================================= */

    .img-preview-wrap {
        border: 2px dashed #dde0ef;
        border-radius: 12px;

        padding: .6rem;

        background: #f8f9fd;

        display: flex;
        align-items: center;

        gap: .8rem;

        margin-bottom: .6rem;
    }

    .img-preview-wrap img {
        width: 70px;
        height: 55px;

        object-fit: cover;

        border-radius: 8px;

        border: 2px solid #e3e5ef;
    }

    .img-preview-wrap small {
        color: #7a7f9a;
        font-size: .78rem;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    #tourTypesTable thead th {
        background: #1a1a2e;
        color: #c9cfe8;

        font-size: .75rem;

        text-transform: uppercase;
        letter-spacing: .7px;

        font-weight: 600;

        border: none;

        padding: .9rem .85rem;
    }

    #tourTypesTable tbody tr {
        transition: background .15s;
        vertical-align: middle;
    }

    #tourTypesTable tbody tr:hover {
        background: #f0f3ff;
    }

    #tourTypesTable td {
        border-color: #eceef8;

        padding: .75rem .85rem;

        font-size: .88rem;
        color: #2d3050;
    }


    /* =========================================================
       IMAGE THUMB
    ========================================================= */

    .tour-type-thumb {
        width: 54px;
        height: 44px;

        object-fit: cover;

        border-radius: 8px;

        border: 2px solid #e3e5ef;

        display: block;
    }


    /* =========================================================
       ICON DISPLAY
    ========================================================= */

    .icon-display {
        font-size: 1.3rem;
        color: #0f3460;

        width: 30px;

        text-align: center;

        display: inline-block;
    }


    /* =========================================================
       BADGE
    ========================================================= */

    .badge-status {
        padding: .35em .7em;

        border-radius: 20px;

        font-size: .75rem;

        font-weight: 600;

        letter-spacing: .3px;
    }


    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .btn-act {
        width: 32px;
        height: 32px;

        border-radius: 8px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border: none;

        font-size: .76rem;

        transition:
            transform .15s,
            box-shadow .15s;

        text-decoration: none;

        cursor: pointer;

        flex-shrink: 0;
    }

    .btn-act:hover {
        transform: translateY(-2px);

        box-shadow:
            0 4px 12px rgba(0, 0, 0, .15);
    }

    .btn-act-view {
        background: #e8f4fd;
        color: #1a73e8;
    }

    .btn-act-edit {
        background: #fff3e0;
        color: #f57c00;
    }

    .btn-act-del {
        background: #fdecea;
        color: #d32f2f;
    }


    /* =========================================================
       ACTION WRAPPER
    ========================================================= */

    .actions-wrap {
        display: flex;

        align-items: center;

        gap: 6px;

        flex-wrap: nowrap;
    }

    .actions-wrap form {
        margin: 0;
        padding: 0;

        display: inline-flex;
    }


    /* =========================================================
       ADD BUTTON
    ========================================================= */

    .btn-add-tour-type {
        background:
            linear-gradient(
                135deg,
                #0f3460,
                #1a73e8
            );

        color: #fff;

        border: none;

        border-radius: 10px;

        padding: .45rem 1.1rem;

        font-size: .85rem;

        font-weight: 600;

        display: inline-flex;

        align-items: center;

        gap: .4rem;

        transition:
            opacity .2s,
            transform .2s;

        cursor: pointer;
    }

    .btn-add-tour-type:hover {
        opacity: .9;

        transform: translateY(-1px);

        color: #fff;
    }


    /* =========================================================
       SUBMIT BUTTON
    ========================================================= */

    .btn-submit {
        background:
            linear-gradient(
                135deg,
                #0f3460,
                #1a73e8
            );

        color: #fff;

        border: none;

        border-radius: 10px;

        padding: .5rem 1.5rem;

        font-weight: 600;

        font-size: .9rem;

        transition: opacity .2s;

        cursor: pointer;

        display: inline-flex;

        align-items: center;

        gap: .4rem;
    }

    .btn-submit:hover {
        opacity: .88;
        color: #fff;
    }


    /* =========================================================
       CANCEL BUTTON
    ========================================================= */

    .btn-cancel {
        background: #e3e5ef;

        color: #4a4f6a;

        border: none;

        border-radius: 10px;

        padding: .5rem 1.2rem;

        font-weight: 600;

        font-size: .9rem;

        cursor: pointer;

        transition: background .2s;
    }

    .btn-cancel:hover {
        background: #d0d3e8;
    }


    /* =========================================================
       VIEW HERO
    ========================================================= */

    .view-hero {
        position: relative;

        border-radius: 14px;

        overflow: hidden;

        margin-bottom: 1rem;
    }

    .view-hero img {
        width: 100%;
        height: 220px;

        object-fit: cover;

        display: block;
    }

    .view-hero-overlay {
        position: absolute;

        inset: 0;

        background:
            linear-gradient(
                to top,
                rgba(0, 0, 0, .6) 0%,
                transparent 60%
            );
    }

    .view-hero-title {
        position: absolute;

        bottom: 1rem;

        left: 1rem;
        right: 1rem;

        color: #fff;

        font-size: 1.15rem;

        font-weight: 700;
    }


    /* =========================================================
       VIEW INFO GRID
    ========================================================= */

    .view-info-grid {
        display: grid;

        grid-template-columns: 1fr 1fr;

        gap: .6rem;

        margin-bottom: 1rem;
    }

    .view-info-item {
        background: #fff;

        border: 1.5px solid #e3e5ef;

        border-radius: 10px;

        padding: .7rem 1rem;
    }

    .view-info-label {
        font-size: .7rem;

        text-transform: uppercase;

        letter-spacing: .6px;

        color: #7a7f9a;

        font-weight: 600;

        margin-bottom: .2rem;
    }

    .view-info-value {
        font-size: .95rem;

        font-weight: 600;

        color: #2d3050;
    }


    /* =========================================================
       VIEW DESCRIPTION
    ========================================================= */

    .view-desc-block {
        background: #fff;

        border: 1.5px solid #e3e5ef;

        border-radius: 12px;

        padding: 1rem 1.2rem;

        margin-bottom: .7rem;
    }

    .view-desc-label {
        font-size: .72rem;

        text-transform: uppercase;

        letter-spacing: .6px;

        color: #0f3460;

        font-weight: 700;

        margin-bottom: .5rem;
    }

    .view-desc-content {
        font-size: .88rem;

        color: #4a4f6a;

        line-height: 1.6;
    }


    /* =========================================================
       SLUG
    ========================================================= */

    .slug-text {
        font-size: .78rem;

        color: #7a7f9a;

        font-family: monospace;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767px) {

        .tour-modal .modal-dialog {
            margin: .5rem;
        }

        .tour-modal .modal-body {
            padding: 1rem;

            max-height: calc(100vh - 150px);
        }

        .tour-modal .modal-footer {
            padding: .8rem 1rem;
        }

        .view-info-grid {
            grid-template-columns: 1fr;
        }

        .card-header {
            gap: 1rem;
            flex-wrap: wrap;
        }

    }
</style>


{{-- =========================================================
     INDEX CARD
========================================================= --}}

<div
    class="card border-0 shadow-sm"
    style="border-radius:16px; overflow:hidden;"
>

    {{-- CARD HEADER --}}
    <div
        class="card-header d-flex justify-content-between align-items-center py-3 px-4"
        style="background:#fff; border-bottom:1.5px solid #eceef8;"
    >

        <div>

            <h5
                class="mb-0 fw-bold"
                style="color:#1a1a2e;"
            >
                Tour Types
            </h5>

            <small class="text-muted">
                Manage all tour types
            </small>

        </div>


        <button
            type="button"
            class="btn-add-tour-type"
            data-bs-toggle="modal"
            data-bs-target="#createModal"
        >
            <i class="fas fa-plus"></i>
            Add Tour Type
        </button>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show m-3 mb-0"
            role="alert"
        >

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- TABLE --}}
    <div class="card-body p-0">

        <div class="table-responsive">

            <table
                class="table mb-0"
                id="tourTypesTable"
            >

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Image</th>

                        <th>Name / Slug</th>

                        <th>Icon</th>

                        <th>Sort Order</th>

                        <th>Status</th>

                        <th style="min-width:110px;">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($tourTypes as $tourType)

                        <tr>

                            {{-- NUMBER --}}
                            <td class="text-muted">
                                {{ $loop->iteration }}
                            </td>


                            {{-- IMAGE --}}
                            <td>

                                @if($tourType->image)

                                    <img
                                        src="{{ asset('uploads/tour-types/' . $tourType->image) }}"
                                        class="tour-type-thumb"
                                        alt="{{ $tourType->name }}"
                                    >

                                @else

                                    <img
                                        src="{{ asset('contents/admin/images/no-image.png') }}"
                                        class="tour-type-thumb"
                                        alt="No Image"
                                    >

                                @endif

                            </td>


                            {{-- NAME / SLUG --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $tourType->name }}
                                </div>

                                <div class="slug-text">
                                    /{{ $tourType->slug }}
                                </div>

                            </td>


                            {{-- ICON --}}
                            <td>

                                @if($tourType->icon)

                                    <i
                                        class="{{ $tourType->icon }} icon-display"
                                    ></i>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- SORT ORDER --}}
                            <td>
                                {{ $tourType->sort_order ?? '—' }}
                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if((int) $tourType->status === 1)

                                    <span class="badge badge-status bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge badge-status bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}
                            <td>

                                <div class="actions-wrap">

                                    {{-- VIEW --}}
                                    <button
                                        type="button"
                                        class="btn-act btn-act-view"
                                        title="View"
                                        onclick="openViewModal({{ $tourType->id }})"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </button>


                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        class="btn-act btn-act-edit"
                                        title="Edit"
                                        onclick="openEditModal({{ $tourType->id }})"
                                    >

                                        <i class="fas fa-pencil-alt"></i>

                                    </button>


                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('admin.tour-types.destroy', $tourType->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this tour type?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn-act btn-act-del"
                                            title="Delete"
                                        >

                                            <i class="fas fa-trash-alt"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5 text-muted"
                            >

                                <i
                                    class="fas fa-box-open fa-2x mb-2 d-block opacity-25"
                                ></i>

                                No tour types found

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- =========================================================
     CREATE MODAL
========================================================= --}}

<div
    class="modal fade tour-modal"
    id="createModal"
    tabindex="-1"
    aria-labelledby="createModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">


            {{-- HEADER --}}
            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="createModalLabel"
                >

                    <i class="fas fa-plus"></i>

                    Add New Tour Type

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- FORM --}}
            <form
                method="POST"
                action="{{ route('admin.tour-types.store') }}"
                enctype="multipart/form-data"
            >

                @csrf


                <div class="modal-body">


                    {{-- BASIC INFO --}}
                    <div class="modal-section">

                        <div class="modal-section-title">

                            <i class="fas fa-info-circle"></i>

                            Basic Info

                        </div>


                        <div class="row g-3">


                            {{-- NAME --}}
                            <div class="col-12">

                                <label>
                                    Tour Type Name *
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Adventure, Beach, Cultural"
                                    required
                                >

                                @error('name')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- ICON --}}
                            <div class="col-12">

                                <label>
                                    Icon (Font Awesome Class)
                                </label>

                                <input
                                    type="text"
                                    name="icon"
                                    class="form-control"
                                    value="{{ old('icon') }}"
                                    placeholder="e.g. fa-solid fa-mountain"
                                >

                                <small class="text-muted">
                                    Enter Font Awesome icon class.
                                </small>

                                @error('icon')

                                    <small class="text-danger d-block">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- SORT ORDER --}}
                            <div class="col-md-6">

                                <label>
                                    Sort Order
                                </label>

                                <input
                                    type="number"
                                    name="sort_order"
                                    class="form-control"
                                    value="{{ old('sort_order', 0) }}"
                                    placeholder="0"
                                >

                                @error('sort_order')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- STATUS --}}
                            <div class="col-md-6">

                                <label>
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-control"
                                >

                                    <option
                                        value="1"
                                        {{ old('status', 1) == 1 ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="0"
                                        {{ old('status') == 0 ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>


                    {{-- IMAGE --}}
                    <div class="modal-section">

                        <div class="modal-section-title">

                            <i class="fas fa-image"></i>

                            Image

                        </div>


                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept="image/*"
                        >


                        <small class="text-muted">
                            Recommended: JPG, PNG, WebP (Max: 2MB)
                        </small>


                        @error('image')

                            <small class="text-danger d-block">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    {{-- DESCRIPTION --}}
                    <div class="modal-section">

                        <div class="modal-section-title">

                            <i class="fas fa-align-left"></i>

                            Description

                        </div>


                        <div class="row g-3">

                            <div class="col-12">

                                <label>
                                    Short Description
                                </label>

                                <textarea
                                    name="short_description"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Brief description about this tour type..."
                                >{{ old('short_description') }}</textarea>

                                @error('short_description')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn-cancel"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn-submit"
                    >

                        <i class="fas fa-save"></i>

                        Save Tour Type

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =========================================================
     EDIT MODAL
========================================================= --}}

<div
    class="modal fade tour-modal"
    id="editModal"
    tabindex="-1"
    aria-labelledby="editModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">


            {{-- HEADER --}}
            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="editModalLabel"
                >

                    <i class="fas fa-pencil-alt"></i>

                    Edit Tour Type

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- EDIT FORM --}}
            <form
                id="editForm"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                {{-- IMPORTANT: UPDATE ROUTE IS PUT --}}
                @method('PUT')


                {{-- AJAX CONTENT --}}
                <div
                    class="modal-body"
                    id="editModalBody"
                >

                    <div class="text-center py-5 text-muted">

                        <div
                            class="spinner-border spinner-border-sm me-2"
                            role="status"
                        ></div>

                        Loading...

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn-cancel"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn-submit"
                    >

                        <i class="fas fa-save"></i>

                        Update Tour Type

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =========================================================
     VIEW MODAL
========================================================= --}}

<div
    class="modal fade tour-modal"
    id="viewModal"
    tabindex="-1"
    aria-labelledby="viewModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">


            {{-- HEADER --}}
            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="viewModalLabel"
                >

                    <i class="fas fa-eye"></i>

                    Tour Type Details

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- BODY --}}
            <div
                class="modal-body"
                id="viewModalBody"
            >

                <div class="text-center py-5 text-muted">

                    <div
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                    ></div>

                    Loading...

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-cancel"
                    data-bs-dismiss="modal"
                >
                    Close
                </button>

            </div>

        </div>

    </div>

</div>

@endsection



@push('scripts')

<script>

    /*
    |--------------------------------------------------------------------------
    | Laravel Route Base URL
    |--------------------------------------------------------------------------
    */

    const tourTypeBaseUrl =
        @json(url('/admin/tour-types'));


    /*
    |--------------------------------------------------------------------------
    | EDIT MODAL
    |--------------------------------------------------------------------------
    */

    function openEditModal(tourTypeId)
    {
        const modalEl =
            document.getElementById('editModal');

        const modal =
            bootstrap.Modal.getOrCreateInstance(modalEl);

        const body =
            document.getElementById('editModalBody');

        const form =
            document.getElementById('editForm');


        /*
        |--------------------------------------------------------------------------
        | Loading State
        |--------------------------------------------------------------------------
        */

        body.innerHTML = `
            <div class="text-center py-5 text-muted">

                <div
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                ></div>

                Loading...

            </div>
        `;


        /*
        |--------------------------------------------------------------------------
        | Show Modal
        |--------------------------------------------------------------------------
        */

        modal.show();


        /*
        |--------------------------------------------------------------------------
        | Fetch Tour Type
        |--------------------------------------------------------------------------
        */

        fetch(
            `${tourTypeBaseUrl}/${tourTypeId}/modal-data`,
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'
                }
            }
        )


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        .then(response => {

            if (!response.ok) {

                throw new Error(
                    `HTTP Error: ${response.status}`
                );
            }

            return response.json();
        })


        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        .then(data => {

            if (
                !data ||
                !data.tourType
            ) {

                throw new Error(
                    'Invalid tour type response.'
                );
            }


            const tourType =
                data.tourType;


            /*
            |--------------------------------------------------------------------------
            | Update Form Action
            |--------------------------------------------------------------------------
            */

            form.action =
                `${tourTypeBaseUrl}/${tourType.id}`;


            /*
            |--------------------------------------------------------------------------
            | Current Image
            |--------------------------------------------------------------------------
            */

            let currentImg = '';


            if (tourType.image) {

                currentImg = `
                    <div class="img-preview-wrap">

                        <img
                            src="/uploads/tour-types/${encodeURIComponent(tourType.image)}"
                            alt="Current image"
                        >

                        <small>
                            Current image — upload below to replace.
                        </small>

                    </div>
                `;
            }


            /*
            |--------------------------------------------------------------------------
            | Modal Form
            |--------------------------------------------------------------------------
            */

            body.innerHTML = `

                <div class="modal-section">

                    <div class="modal-section-title">

                        <i class="fas fa-info-circle"></i>

                        Basic Info

                    </div>


                    <div class="row g-3">


                        <div class="col-12">

                            <label>
                                Tour Type Name *
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="${escHtml(tourType.name)}"
                                required
                            >

                        </div>


                        <div class="col-12">

                            <label>
                                Icon (Font Awesome Class)
                            </label>

                            <input
                                type="text"
                                name="icon"
                                class="form-control"
                                value="${escHtml(tourType.icon ?? '')}"
                                placeholder="e.g. fa-solid fa-mountain"
                            >

                            <small class="text-muted">
                                Enter Font Awesome icon class.
                            </small>

                        </div>


                        <div class="col-md-6">

                            <label>
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                class="form-control"
                                value="${tourType.sort_order ?? 0}"
                            >

                        </div>


                        <div class="col-md-6">

                            <label>
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                            >

                                <option
                                    value="1"
                                    ${
                                        Number(tourType.status) === 1
                                            ? 'selected'
                                            : ''
                                    }
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    ${
                                        Number(tourType.status) === 0
                                            ? 'selected'
                                            : ''
                                    }
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <div class="modal-section">

                    <div class="modal-section-title">

                        <i class="fas fa-image"></i>

                        Image

                    </div>


                    ${currentImg}


                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        accept="image/*"
                    >


                    <small class="text-muted">
                        Recommended: JPG, PNG, WebP (Max: 2MB)
                    </small>

                </div>


                <div class="modal-section">

                    <div class="modal-section-title">

                        <i class="fas fa-align-left"></i>

                        Description

                    </div>


                    <div class="row g-3">

                        <div class="col-12">

                            <label>
                                Short Description
                            </label>

                            <textarea
                                name="short_description"
                                rows="3"
                                class="form-control"
                            >${escHtml(
                                tourType.short_description ?? ''
                            )}</textarea>

                        </div>

                    </div>

                </div>

            `;
        })


        /*
        |--------------------------------------------------------------------------
        | Error
        |--------------------------------------------------------------------------
        */

        .catch(error => {

            console.error(
                'Tour Type Edit Error:',
                error
            );


            body.innerHTML = `

                <div class="alert alert-danger m-0">

                    <i
                        class="fas fa-exclamation-triangle me-2"
                    ></i>

                    Failed to load tour type data.

                    <br>

                    <small>
                        ${escHtml(error.message)}
                    </small>

                </div>

            `;
        });
    }



    /*
    |--------------------------------------------------------------------------
    | VIEW MODAL
    |--------------------------------------------------------------------------
    */

    function openViewModal(tourTypeId)
    {
        const modalEl =
            document.getElementById('viewModal');

        const modal =
            bootstrap.Modal.getOrCreateInstance(modalEl);

        const body =
            document.getElementById('viewModalBody');


        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */

        body.innerHTML = `
            <div class="text-center py-5 text-muted">

                <div
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                ></div>

                Loading...

            </div>
        `;


        modal.show();


        /*
        |--------------------------------------------------------------------------
        | Fetch Data
        |--------------------------------------------------------------------------
        */

        fetch(
            `${tourTypeBaseUrl}/${tourTypeId}/modal-data`,
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'
                }
            }
        )


        .then(response => {

            if (!response.ok) {

                throw new Error(
                    `HTTP Error: ${response.status}`
                );
            }

            return response.json();
        })


        .then(data => {

            if (
                !data ||
                !data.tourType
            ) {

                throw new Error(
                    'Invalid tour type response.'
                );
            }


            const t =
                data.tourType;


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            const imgSrc = t.image

                ? `/uploads/tour-types/${encodeURIComponent(t.image)}`

                : `/contents/admin/images/no-image.png`;


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            const statusBadge =
                Number(t.status) === 1

                    ? `
                        <span class="badge bg-success">
                            Active
                        </span>
                      `

                    : `
                        <span class="badge bg-danger">
                            Inactive
                        </span>
                      `;


            /*
            |--------------------------------------------------------------------------
            | Icon
            |--------------------------------------------------------------------------
            */

            const iconDisplay = t.icon

                ? `
                    <i
                        class="${escHtml(t.icon)}"
                        style="
                            font-size:1.8rem;
                            color:#0f3460;
                        "
                    ></i>
                  `

                : `
                    <span class="text-muted">
                        No icon
                    </span>
                  `;


            /*
            |--------------------------------------------------------------------------
            | Description Section
            |--------------------------------------------------------------------------
            */

            const section =
                (label, content) => {

                    if (
                        content === null ||
                        content === undefined ||
                        String(content).trim() === ''
                    ) {
                        return '';
                    }


                    return `

                        <div class="view-desc-block">

                            <div class="view-desc-label">
                                ${escHtml(label)}
                            </div>

                            <div class="view-desc-content">
                                ${escHtml(content)}
                            </div>

                        </div>

                    `;
                };


            /*
            |--------------------------------------------------------------------------
            | View Content
            |--------------------------------------------------------------------------
            */

            body.innerHTML = `

                <div class="view-hero">

                    <img
                        src="${imgSrc}"
                        alt="${escHtml(t.name)}"
                    >

                    <div class="view-hero-overlay"></div>

                    <div class="view-hero-title">
                        ${escHtml(t.name)}
                    </div>

                </div>


                <div class="view-info-grid">


                    <div class="view-info-item">

                        <div class="view-info-label">
                            Icon
                        </div>

                        <div class="view-info-value">
                            ${iconDisplay}
                        </div>

                    </div>


                    <div class="view-info-item">

                        <div class="view-info-label">
                            Slug
                        </div>

                        <div
                            class="view-info-value"
                            style="
                                font-size:.8rem;
                                font-weight:400;
                            "
                        >
                            ${escHtml(t.slug)}
                        </div>

                    </div>


                    <div class="view-info-item">

                        <div class="view-info-label">
                            Sort Order
                        </div>

                        <div class="view-info-value">
                            ${t.sort_order ?? '—'}
                        </div>

                    </div>


                    <div class="view-info-item">

                        <div class="view-info-label">
                            Status
                        </div>

                        <div class="view-info-value">
                            ${statusBadge}
                        </div>

                    </div>

                </div>


                ${section(
                    'Short Description',
                    t.short_description
                )}

            `;
        })


        /*
        |--------------------------------------------------------------------------
        | Error
        |--------------------------------------------------------------------------
        */

        .catch(error => {

            console.error(
                'Tour Type View Error:',
                error
            );


            body.innerHTML = `

                <div class="alert alert-danger m-0">

                    <i
                        class="fas fa-exclamation-triangle me-2"
                    ></i>

                    Failed to load tour type data.

                    <br>

                    <small>
                        ${escHtml(error.message)}
                    </small>

                </div>

            `;
        });
    }



    /*
    |--------------------------------------------------------------------------
    | HTML ESCAPE
    |--------------------------------------------------------------------------
    */

    function escHtml(value)
    {
        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }


        return String(value)

            .replace(/&/g, '&amp;')

            .replace(/</g, '&lt;')

            .replace(/>/g, '&gt;')

            .replace(/"/g, '&quot;')

            .replace(/'/g, '&#039;');
    }



    /*
    |--------------------------------------------------------------------------
    | AUTO OPEN CREATE MODAL AFTER VALIDATION ERROR
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            @if($errors->any())

                const createModalEl =
                    document.getElementById('createModal');

                if (createModalEl) {

                    const createModal =
                        bootstrap.Modal.getOrCreateInstance(
                            createModalEl
                        );

                    createModal.show();
                }

            @endif

        }
    );

</script>

@endpush