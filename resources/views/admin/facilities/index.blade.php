@extends('layouts.admin')

@section('title', 'Facilities')

@section('page')

<div class="container-fluid">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="mb-0">

            <i class="fas fa-list-check text-primary"></i>

            Facilities

        </h4>


        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#createModal"
        >

            <i class="fas fa-plus"></i>

            Add Facility

        </button>

    </div>


    {{-- =====================================================
        SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
        VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
        FACILITIES TABLE
    ====================================================== --}}

    <div class="card shadow">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th width="80">
                                Icon
                            </th>

                            <th>
                                Name
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="150">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($facilities as $facility)

                            <tr>

                                {{-- Number --}}

                                <td>

                                    {{ $facilities->firstItem() + $loop->index }}

                                </td>


                                {{-- Icon --}}

                                <td class="text-center">

                                    @if($facility->icon)

                                        <i
                                            class="{{ $facility->icon }}"
                                            style="font-size:22px;"
                                        ></i>

                                    @else

                                        <i
                                            class="fas fa-circle-question text-muted"
                                            style="font-size:22px;"
                                        ></i>

                                    @endif

                                </td>


                                {{-- Name --}}

                                <td>

                                    <strong>
                                        {{ $facility->name }}
                                    </strong>

                                </td>


                                {{-- Type --}}

                                <td>

                                    @if($facility->type === 'room')

                                        <span class="badge bg-info">

                                            Room

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            Resort

                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}

                                <td>

                                    @if($facility->status)

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}

                                <td>

                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm editBtn"
                                        data-id="{{ $facility->id }}"
                                    >

                                        <i class="fas fa-edit"></i>

                                    </button>


                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $facility->id }}"
                                    >

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-4"
                                >

                                    No facilities found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            <div class="mt-3">

                {{ $facilities->links() }}

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    CREATE MODAL
========================================================== --}}

<div
    class="modal fade"
    id="createModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('admin.facilities.store') }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">

                        Add Facility

                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    {{-- Name --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="e.g. Swimming Pool"
                            required
                        >

                    </div>


                    {{-- Icon --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Icon
                        </label>

                        <input
                            id="icon"
                            type="text"
                            name="icon"
                            class="form-control"
                            placeholder="fas fa-swimming-pool"
                        >

                        <small class="text-muted">

                            Example:
                            <code>fas fa-wifi</code>

                        </small>

                    </div>


                    {{-- Icon Preview --}}

                    <div class="text-center mb-3">

                        <i
                            id="iconPreview"
                            class="fas fa-star fa-3x text-primary"
                        ></i>

                    </div>


                    {{-- Type --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Type
                        </label>

                        <select
                            class="form-select"
                            name="type"
                            required
                        >

                            <option value="resort">

                                Resort

                            </option>

                            <option value="room">

                                Room

                            </option>

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            class="form-select"
                            name="status"
                            required
                        >

                            <option value="1">

                                Active

                            </option>

                            <option value="0">

                                Inactive

                            </option>

                        </select>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-save me-1"></i>

                        Save Facility

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
    EDIT MODAL
========================================================== --}}

<div
    class="modal fade"
    id="editModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                id="editForm"
            >

                @csrf

                @method('PUT')


                <div class="modal-header">

                    <h5 class="modal-title">

                        Edit Facility

                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    {{-- Name --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            id="edit_name"
                            name="name"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- Icon --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Icon
                        </label>

                        <input
                            type="text"
                            id="edit_icon"
                            name="icon"
                            class="form-control"
                            placeholder="fas fa-wifi"
                        >

                    </div>


                    {{-- Icon Preview --}}

                    <div class="text-center mb-3">

                        <i
                            id="editIconPreview"
                            class="fas fa-star fa-3x text-warning"
                        ></i>

                    </div>


                    {{-- Type --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Type
                        </label>

                        <select
                            id="edit_type"
                            name="type"
                            class="form-select"
                            required
                        >

                            <option value="resort">
                                Resort
                            </option>

                            <option value="room">
                                Room
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            id="edit_status"
                            name="status"
                            class="form-select"
                            required
                        >

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="btn btn-warning"
                    >

                        <i class="fas fa-save me-1"></i>

                        Update Facility

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
    DELETE MODAL
========================================================== --}}

<div
    class="modal fade"
    id="deleteModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-sm">

        <div class="modal-content">

            <form
                method="POST"
                id="deleteForm"
            >

                @csrf

                @method('DELETE')


                <div class="modal-body text-center py-4">

                    <i
                        class="fas fa-trash fa-3x text-danger mb-3"
                    ></i>


                    <h5>
                        Delete Facility?
                    </h5>


                    <p class="text-muted mb-0">

                        This action cannot be undone.

                    </p>

                </div>


                <div class="modal-footer justify-content-center">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="btn btn-danger"
                    >

                        Delete

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('scripts')

<script>

$(function () {

    /*
    |--------------------------------------------------------------------------
    | CREATE ICON PREVIEW
    |--------------------------------------------------------------------------
    */

    $('#icon').on(
        'keyup change',
        function () {

            let icon = $(this).val().trim();

            if (icon === '') {

                icon = 'fas fa-star';

            }

            $('#iconPreview').attr(
                'class',
                icon + ' fa-3x text-primary'
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT ICON PREVIEW
    |--------------------------------------------------------------------------
    */

    $('#edit_icon').on(
        'keyup change',
        function () {

            let icon = $(this).val().trim();

            if (icon === '') {

                icon = 'fas fa-star';

            }

            $('#editIconPreview').attr(
                'class',
                icon + ' fa-3x text-warning'
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT FACILITY
    |--------------------------------------------------------------------------
    */

    $('.editBtn').on(
        'click',
        function () {

            let id = $(this).data('id');


            $.ajax({

                url: '/admin/facilities/' + id + '/edit',

                type: 'GET',

                success: function (res) {

                    $('#edit_name').val(
                        res.name
                    );


                    $('#edit_icon').val(
                        res.icon
                    );


                    $('#edit_type').val(
                        res.type
                    );


                    $('#edit_status').val(
                        res.status ? '1' : '0'
                    );


                    let icon =
                        res.icon
                        ? res.icon
                        : 'fas fa-star';


                    $('#editIconPreview').attr(
                        'class',
                        icon
                        + ' fa-3x text-warning'
                    );


                    $('#editForm').attr(
                        'action',
                        '/admin/facilities/' + id
                    );


                    $('#editModal').modal(
                        'show'
                    );

                },

                error: function () {

                    alert(
                        'Unable to load facility information.'
                    );

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE FACILITY
    |--------------------------------------------------------------------------
    */

    $('.deleteBtn').on(
        'click',
        function () {

            let id = $(this).data('id');


            $('#deleteForm').attr(
                'action',
                '/admin/facilities/' + id
            );


            $('#deleteModal').modal(
                'show'
            );

        }
    );

});

</script>

@endpush

@endsection