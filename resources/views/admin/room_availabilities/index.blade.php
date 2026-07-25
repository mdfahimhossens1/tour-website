@extends('layouts.admin')

@section('title', 'Room Availability Management')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fas fa-calendar-check text-primary"></i>
            Room Availability Management
        </h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i>
            Add Availability
        </button>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Availability Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">#</th>
                            <th>Resort</th>
                            <th>Room</th>
                            <th>Date</th>
                            <th>Price (৳)</th>
                            <th>Total</th>
                            <th>Available</th>
                            <th>Status</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomAvailabilities as $availability)
                            <tr>
                                <td>{{ $roomAvailabilities->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-bold">{{ $availability->room->resort->name ?? '-' }}</span>
                                </td>
                                <td>{{ $availability->room->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $availability->date->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <strong>৳ {{ number_format($availability->price, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $availability->total_rooms }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $availability->available_rooms }}</span>
                                </td>
                                <td>
                                    @if($availability->is_closed)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Closed
                                        </span>
                                    @elseif($availability->is_sold_out)
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-exclamation-triangle"></i> Sold Out
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Available
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info viewBtn" data-id="{{ $availability->id }}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning editBtn" data-id="{{ $availability->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $availability->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-2x text-muted d-block mb-2"></i>
                                    <span class="text-muted">No availability records found</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $roomAvailabilities->links() }}
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- CREATE MODAL --}}
{{-- ============================================ --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.room-availabilities.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-primary"></i>
                        Add Room Availability
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Resort --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Resort <span class="text-danger">*</span></label>
                            <select id="create_resort" class="form-select" required>
                                <option value="">Select Resort</option>
                                @foreach($resorts as $resort)
                                    <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Room --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Room <span class="text-danger">*</span></label>
                            <select name="room_id" id="create_room" class="form-select" required>
                                <option value="">Select Room</option>
                            </select>
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        {{-- Price --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price" required>
                        </div>

                        {{-- Total Rooms --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Total Rooms <span class="text-danger">*</span></label>
                            <input type="number" name="total_rooms" id="create_total_rooms" class="form-control" min="0" required>
                        </div>

                        {{-- Available Rooms --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Available Rooms <span class="text-danger">*</span></label>
                            <input type="number" name="available_rooms" id="create_available_rooms" class="form-control" min="0" required>
                        </div>

                        {{-- Status Checkboxes --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div class="d-flex gap-3 pt-2">
                                <div class="form-check">
                                    <input type="checkbox" name="is_closed" id="create_closed" value="1" class="form-check-input">
                                    <label class="form-check-label text-danger" for="create_closed">
                                        <i class="fas fa-times-circle"></i> Closed
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="is_sold_out" id="create_sold_out" value="1" class="form-check-input">
                                    <label class="form-check-label text-warning" for="create_sold_out">
                                        <i class="fas fa-exclamation-triangle"></i> Sold Out
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- EDIT MODAL --}}
{{-- ============================================ --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit text-warning"></i>
                        Edit Room Availability
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Resort --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Resort <span class="text-danger">*</span></label>
                            <select id="edit_resort" class="form-select" required>
                                @foreach($resorts as $resort)
                                    <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Room --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Room <span class="text-danger">*</span></label>
                            <select name="room_id" id="edit_room" class="form-select" required>
                                <option value="">Select Room</option>
                            </select>
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="edit_date" class="form-control" required>
                        </div>

                        {{-- Price --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>

                        {{-- Total Rooms --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Total Rooms <span class="text-danger">*</span></label>
                            <input type="number" name="total_rooms" id="edit_total_rooms" class="form-control" min="0" required>
                        </div>

                        {{-- Available Rooms --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Available Rooms <span class="text-danger">*</span></label>
                            <input type="number" name="available_rooms" id="edit_available_rooms" class="form-control" min="0" required>
                        </div>

                        {{-- Status Checkboxes --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div class="d-flex gap-3 pt-2">
                                <div class="form-check">
                                    <input type="checkbox" name="is_closed" id="edit_closed" value="1" class="form-check-input">
                                    <label class="form-check-label text-danger" for="edit_closed">
                                        <i class="fas fa-times-circle"></i> Closed
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="is_sold_out" id="edit_sold_out" value="1" class="form-check-input">
                                    <label class="form-check-label text-warning" for="edit_sold_out">
                                        <i class="fas fa-exclamation-triangle"></i> Sold Out
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i>
                        Update Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- VIEW MODAL --}}
{{-- ============================================ --}}
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle text-info"></i>
                    Availability Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%;">Resort</th>
                            <td id="v_resort" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <th>Room</th>
                            <td id="v_room"></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td id="v_date"></td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td id="v_price" class="text-success fw-bold"></td>
                        </tr>
                        <tr>
                            <th>Total Rooms</th>
                            <td id="v_total_rooms"></td>
                        </tr>
                        <tr>
                            <th>Available Rooms</th>
                            <td id="v_available_rooms"></td>
                        </tr>
                        <tr>
                            <th>Closed</th>
                            <td id="v_closed"></td>
                        </tr>
                        <tr>
                            <th>Sold Out</th>
                            <td id="v_sold_out"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- DELETE MODAL --}}
{{-- ============================================ --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <h5 class="text-dark">Are you sure?</h5>
                    <p class="text-muted">You want to delete this availability record? This action cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- ============================================ --}}
{{-- JAVASCRIPT --}}
{{-- ============================================ --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {

    /**
     * ======================================
     * Create Modal - Load Rooms by Resort
     * ======================================
     */
    $('#create_resort').change(function() {
        let resortId = $(this).val();
        let roomSelect = $('#create_room');

        if (!resortId) {
            roomSelect.html('<option value="">Select Room</option>');
            return;
        }

        roomSelect.html('<option>Loading...</option>');

        $.get('/admin/room-availabilities/get-rooms/' + resortId, function(response) {
            let html = '<option value="">Select Room</option>';
            
            if (response.data && response.data.length > 0) {
                $.each(response.data, function(i, room) {
                    html += `<option value="${room.id}">${room.name} (Total: ${room.total_rooms})</option>`;
                });
            }
            
            roomSelect.html(html);
        }).fail(function() {
            roomSelect.html('<option value="">Error loading rooms</option>');
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load rooms. Please try again.'
            });
        });
    });

    /**
     * ======================================
     * Create Form - Auto-set Total Rooms
     * ======================================
     */
    $('#create_room').change(function() {
        let roomId = $(this).val();
        let resortId = $('#create_resort').val();

        if (roomId && resortId) {
            $.get('/admin/room-availabilities/get-rooms/' + resortId, function(response) {
                let room = response.data.find(r => r.id == roomId);
                if (room) {
                    $('#create_total_rooms').val(room.total_rooms);
                    $('#create_available_rooms').val(room.total_rooms);
                }
            });
        }
    });

    /**
     * ======================================
     * Edit Modal - Load Rooms by Resort
     * ======================================
     */
    $('#edit_resort').change(function() {
        let resortId = $(this).val();
        let roomSelect = $('#edit_room');

        if (!resortId) {
            roomSelect.html('<option value="">Select Room</option>');
            return;
        }

        roomSelect.html('<option>Loading...</option>');

        $.get('/admin/room-availabilities/get-rooms/' + resortId, function(response) {
            let html = '<option value="">Select Room</option>';
            
            if (response.data && response.data.length > 0) {
                $.each(response.data, function(i, room) {
                    html += `<option value="${room.id}">${room.name} (Total: ${room.total_rooms})</option>`;
                });
            }
            
            roomSelect.html(html);
        }).fail(function() {
            roomSelect.html('<option value="">Error loading rooms</option>');
        });
    });

    /**
     * ======================================
     * View Modal - Show Details
     * ======================================
     */
    $('.viewBtn').click(function() {
        let id = $(this).data('id');

        $.get('/admin/room-availabilities/' + id, function(response) {
            let data = response.data;

            $('#v_resort').text(data.room.resort.name);
            $('#v_room').text(data.room.name);
            $('#v_date').text(new Date(data.date).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }));
            $('#v_price').text('৳ ' + parseFloat(data.price).toFixed(2));
            $('#v_total_rooms').text(data.total_rooms);
            $('#v_available_rooms').text(data.available_rooms);
            $('#v_closed').html(data.is_closed ? 
                '<span class="badge bg-danger">Yes</span>' : 
                '<span class="badge bg-success">No</span>');
            $('#v_sold_out').html(data.is_sold_out ? 
                '<span class="badge bg-warning">Yes</span>' : 
                '<span class="badge bg-success">No</span>');

            $('#viewModal').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load availability details.'
            });
        });
    });

    /**
     * ======================================
     * Edit Modal - Load Data
     * ======================================
     */
    $('.editBtn').click(function() {
        let id = $(this).data('id');

        $.get('/admin/room-availabilities/' + id, function(response) {
            let data = response.data;

            // Set form action
            $('#editForm').attr('action', '/admin/room-availabilities/' + id);

            // Set resort and trigger change to load rooms
            $('#edit_resort').val(data.room.resort_id).trigger('change');

            // Wait for rooms to load then set selected room
            setTimeout(function() {
                $('#edit_room').val(data.room_id);
            }, 300);

            // Set other fields
            $('#edit_date').val(data.date);
            $('#edit_price').val(data.price);
            $('#edit_total_rooms').val(data.total_rooms);
            $('#edit_available_rooms').val(data.available_rooms);
            $('#edit_closed').prop('checked', data.is_closed == 1);
            $('#edit_sold_out').prop('checked', data.is_sold_out == 1);

            $('#editModal').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load availability data.'
            });
        });
    });

    /**
     * ======================================
     * Delete Modal
     * ======================================
     */
    $('.deleteBtn').click(function() {
        let id = $(this).data('id');
        let url = '/admin/room-availabilities/' + id;
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });

    /**
     * ======================================
     * Form Validation
     * ======================================
     */
    $('#createForm, #editForm').on('submit', function(e) {
        let totalRooms = parseInt($(this).find('[name="total_rooms"]').val()) || 0;
        let availableRooms = parseInt($(this).find('[name="available_rooms"]').val()) || 0;

        if (availableRooms > totalRooms) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Data',
                text: 'Available rooms cannot be greater than total rooms.'
            });
            return false;
        }

        let isClosed = $(this).find('[name="is_closed"]').is(':checked');
        let isSoldOut = $(this).find('[name="is_sold_out"]').is(':checked');

        if (isClosed && isSoldOut) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Status',
                text: 'Room cannot be both Closed and Sold Out at the same time.'
            });
            return false;
        }

        if (isClosed && availableRooms > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Status',
                text: 'When room is closed, available rooms must be 0.'
            });
            return false;
        }

        if (isSoldOut && availableRooms > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Status',
                text: 'When room is sold out, available rooms must be 0.'
            });
            return false;
        }
    });

    /**
     * ======================================
     * Close/Sold Out Auto-set Available
     * ======================================
     */
    $('#create_closed, #create_sold_out, #edit_closed, #edit_sold_out').change(function() {
        let form = $(this).closest('form');
        let isClosed = form.find('[name="is_closed"]').is(':checked');
        let isSoldOut = form.find('[name="is_sold_out"]').is(':checked');
        let availableField = form.find('[name="available_rooms"]');

        if (isClosed || isSoldOut) {
            availableField.val(0);
            availableField.prop('readonly', true);
        } else {
            let totalField = form.find('[name="total_rooms"]');
            if (totalField.val()) {
                availableField.val(totalField.val());
            }
            availableField.prop('readonly', false);
        }
    });

});
</script>
@endpush