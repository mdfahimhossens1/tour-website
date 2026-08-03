@extends('layouts.admin')

@section('title','Facilities')

@section('page')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4>
            <i class="fas fa-list-check text-primary"></i>
            Facilities
        </h4>

        <button
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#createModal">

            <i class="fas fa-plus"></i>

            Add Facility

        </button>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    <div class="card shadow">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                    <tr>

                        <th width="60">#</th>

                        <th>Icon</th>

                        <th>Name</th>

                        <th>Type</th>

                        <th>Status</th>

                        <th width="150">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($facilities as $facility)

                        <tr>

                            <td>

                                {{ $facilities->firstItem()+$loop->index }}

                            </td>

                            <td>

                                <i class="{{ $facility->icon }}"></i>

                            </td>

                            <td>

                                {{ $facility->name }}

                            </td>

                            <td>

                                @if($facility->type=='room')

                                    <span class="badge bg-info">

                                        Room

                                    </span>

                                @else

                                    <span class="badge bg-success">

                                        Resort

                                    </span>

                                @endif

                            </td>

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

                            <td>

                                <button
                                    class="btn btn-warning btn-sm editBtn"
                                    data-id="{{ $facility->id }}">

                                    <i class="fas fa-edit"></i>

                                </button>

                                <button
                                    class="btn btn-danger btn-sm deleteBtn"
                                    data-id="{{ $facility->id }}">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center">

                                No Data Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            {{ $facilities->links() }}

        </div>

    </div>

</div>

<div class="modal fade" id="createModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('admin.facilities.store') }}">

                @csrf

                <div class="modal-header">

                    <h5>Add Facility</h5>

                    <button
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Name</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Icon</label>

                        <input
                            id="icon"
                            type="text"
                            name="icon"
                            class="form-control"
                            placeholder="fas fa-wifi">

                    </div>

                    <div class="text-center mb-3">

                        <i
                            id="iconPreview"
                            class="fas fa-star fa-3x text-primary">

                        </i>

                    </div>

                    <div class="mb-3">

                        <label>Type</label>

                        <select
                            class="form-select"
                            name="type">

                            <option value="room">

                                Room

                            </option>

                            <option value="resort">

                                Resort

                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Status</label>

                        <select
                            class="form-select"
                            name="status">

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
                        class="btn btn-primary">

                        Save

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="modal fade" id="editModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                id="editForm">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5>Edit Facility</h5>

                    <button
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Name</label>

                        <input
                            type="text"
                            id="edit_name"
                            name="name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Icon</label>

                        <input
                            type="text"
                            id="edit_icon"
                            name="icon"
                            class="form-control">

                    </div>

                    <div class="text-center mb-3">

                        <i
                            id="editIconPreview"
                            class="fas fa-star fa-3x text-warning">
                        </i>

                    </div>

                    <div class="mb-3">

                        <label>Type</label>

                        <select
                            id="edit_type"
                            name="type"
                            class="form-select">

                            <option value="room">
                                Room
                            </option>

                            <option value="resort">
                                Resort
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Status</label>

                        <select
                            id="edit_status"
                            name="status"
                            class="form-select">

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
                        type="submit"
                        class="btn btn-warning">

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="modal fade" id="deleteModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                id="deleteForm">

                @csrf
                @method('DELETE')

                <div class="modal-body text-center">

                    <i
                        class="fas fa-trash fa-3x text-danger mb-3">
                    </i>

                    <h5>

                        Delete this Facility?

                    </h5>

                    <p>

                        This action cannot be undone.

                    </p>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Delete

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@push('scripts')
<script>

$(function(){

    /*
    ===========================
    ICON PREVIEW (CREATE)
    ===========================
    */

    $('#icon').on('keyup change', function(){

        let icon = $(this).val();

        if(icon == ''){
            icon = 'fas fa-star';
        }

        $('#iconPreview')
            .attr('class', icon + ' fa-3x text-primary');

    });

    /*
    ===========================
    ICON PREVIEW (EDIT)
    ===========================
    */

    $('#edit_icon').on('keyup change', function(){

        let icon = $(this).val();

        if(icon == ''){
            icon = 'fas fa-star';
        }

        $('#editIconPreview')
            .attr('class', icon + ' fa-3x text-warning');

    });

    /*
    ===========================
    EDIT
    ===========================
    */

    $('.editBtn').click(function(){

        let id = $(this).data('id');

        $.get('/admin/facilities/'+id+'/edit', function(res){

            $('#edit_name').val(res.name);

            $('#edit_icon').val(res.icon);

            $('#edit_type').val(res.type);

            $('#edit_status').val(res.status);

            $('#editIconPreview')
                .attr(
                    'class',
                    (res.icon ? res.icon : 'fas fa-star')
                    +' fa-3x text-warning'
                );

            $('#editForm').attr(
                'action',
                '/admin/facilities/'+id
            );

            $('#editModal').modal('show');

        });

    });

    /*
    ===========================
    DELETE
    ===========================
    */

    $('.deleteBtn').click(function(){

        let id = $(this).data('id');

        $('#deleteForm').attr(
            'action',
            '/admin/facilities/'+id
        );

        $('#deleteModal').modal('show');

    });

});

</script>
@endpush

@endsection