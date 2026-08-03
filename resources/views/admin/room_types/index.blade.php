@extends('layouts.admin')

@section('title','Room Types')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-bed text-primary"></i>
                Room Types
            </h4>

            <small class="text-muted">
                Manage Resort Room Types
            </small>
        </div>

        <button
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#createModal">

            <i class="fas fa-plus-circle"></i>

            Add Room Type

        </button>

    </div>

    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

    @endif

    {{-- Table --}}

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th width="70">
                                #
                            </th>

                            <th width="80">
                                Icon
                            </th>

                            <th>
                                Name
                            </th>

                            <th width="150">
                                Total Rooms
                            </th>

                            <th width="170">
                                Created
                            </th>

                            <th width="160">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($roomTypes as $type)

                        <tr>

                            <td>

                                {{ $roomTypes->firstItem() + $loop->index }}

                            </td>

                            <td>

                                @if($type->icon)

                                    <i class="{{ $type->icon }} fa-2x text-primary"></i>

                                @else

                                    <i class="fas fa-bed fa-2x text-secondary"></i>

                                @endif

                            </td>

                            <td>

                                <strong>

                                    {{ $type->name }}

                                </strong>

                            </td>

                            <td>

                                <span class="badge bg-info">

                                    {{ $type->rooms()->count() }}

                                    Rooms

                                </span>

                            </td>

                            <td>

                                {{ $type->created_at->format('d M Y') }}

                            </td>

                            <td>

                                <button

                                    class="btn btn-sm btn-warning editBtn"

                                    data-id="{{ $type->id }}">

                                    <i class="fas fa-edit"></i>

                                </button>

                                <button

                                    class="btn btn-sm btn-danger deleteBtn"

                                    data-id="{{ $type->id }}">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-5">

                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                                <br>

                                No Room Types Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $roomTypes->links() }}

            </div>

        </div>

    </div>

</div>

{{-- ====================================== --}}
{{-- CREATE MODAL --}}
{{-- ====================================== --}}

<div
class="modal fade"
id="createModal"
tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<form

action="{{ route('admin.room-types.store') }}"

method="POST">

@csrf

<div class="modal-header">

<h5 class="modal-title">

<i class="fas fa-plus-circle text-primary"></i>

Add Room Type

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

<div class="mb-3">

<label class="form-label">

Room Type Name

</label>

<input

type="text"

name="name"

class="form-control"

required>

</div>

<div class="mb-3">

<label class="form-label">

FontAwesome Icon

</label>

<input

type="text"

name="icon"

id="icon"

class="form-control"

placeholder="fas fa-bed">

<small class="text-muted">

Example:

fas fa-bed

fas fa-home

fas fa-hotel

</small>

</div>

<div class="text-center">

<i

id="iconPreview"

class="fas fa-bed fa-3x text-primary"></i>

</div>

</div>

<div class="modal-footer">

<button

class="btn btn-secondary"

data-bs-dismiss="modal"

type="button">

Cancel

</button>

<button

class="btn btn-primary"

type="submit">

<i class="fas fa-save"></i>

Save

</button>

</div>

</form>

</div>

</div>

</div>
{{-- ====================================== --}}
{{-- EDIT MODAL --}}
{{-- ====================================== --}}

<div class="modal fade" id="editModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                id="editForm">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fas fa-edit text-warning"></i>

                        Edit Room Type

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">

                            Room Type Name

                        </label>

                        <input
                            type="text"
                            name="name"
                            id="edit_name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            FontAwesome Icon

                        </label>

                        <input
                            type="text"
                            name="icon"
                            id="edit_icon"
                            class="form-control"
                            placeholder="fas fa-bed">

                        <small class="text-muted">

                            Example:

                            fas fa-bed

                            fas fa-home

                            fas fa-hotel

                        </small>

                    </div>

                    <div class="text-center">

                        <i
                            id="editIconPreview"
                            class="fas fa-bed fa-3x text-warning">

                        </i>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        type="button"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        class="btn btn-warning"
                        type="submit">

                        <i class="fas fa-save"></i>

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
{{-- ====================================== --}}
{{-- DELETE MODAL --}}
{{-- ====================================== --}}

<div
    class="modal fade"
    id="deleteModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                id="deleteForm">

                @csrf

                @method('DELETE')

                <div class="modal-body text-center py-4">

                    <i
                        class="fas fa-trash-alt fa-4x text-danger mb-3">
                    </i>

                    <h5>

                        Delete Room Type?

                    </h5>

                    <p class="text-muted">

                        This action cannot be undone.

                    </p>

                </div>

                <div class="modal-footer justify-content-center">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        class="btn btn-danger"
                        type="submit">

                        <i class="fas fa-trash"></i>

                        Delete

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@push('scripts')

<script>

$(document).ready(function(){

    /*
    ==========================================
    Live Icon Preview (Create)
    ==========================================
    */

    $('#icon').on('keyup change', function(){

        let icon = $(this).val();

        if(icon === ''){

            icon = 'fas fa-bed';

        }

        $('#iconPreview')
            .attr('class', icon + ' fa-3x text-primary');

    });


    /*
    ==========================================
    Live Icon Preview (Edit)
    ==========================================
    */

    $('#edit_icon').on('keyup change', function(){

        let icon = $(this).val();

        if(icon === ''){

            icon = 'fas fa-bed';

        }

        $('#editIconPreview')
            .attr('class', icon + ' fa-3x text-warning');

    });


    /*
    ==========================================
    Edit Button
    ==========================================
    */

    $('.editBtn').click(function(){

        let id = $(this).data('id');

        $.get('/admin/room-types/' + id + '/edit', function(data){

            $('#edit_name').val(data.name);

            $('#edit_icon').val(data.icon);

            let icon = data.icon ? data.icon : 'fas fa-bed';

            $('#editIconPreview')
                .attr('class', icon + ' fa-3x text-warning');

            $('#editForm').attr(
                'action',
                '/admin/room-types/' + id
            );

            $('#editModal').modal('show');

        });

    });


    /*
    ==========================================
    Delete Button
    ==========================================
    */

    $('.deleteBtn').click(function(){

        let id = $(this).data('id');

        $('#deleteForm').attr(
            'action',
            '/admin/room-types/' + id
        );

        $('#deleteModal').modal('show');

    });

});

</script>

@endpush
@endsection