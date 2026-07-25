@extends('layouts.admin')
@section('title', 'Room Price Management')
@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fas fa-money-bill-wave text-primary"></i>
            Room Price Management
        </h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i>
            Add Room Price
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

    {{-- Room Price Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">#</th>
                            <th>Resort</th>
                            <th>Room</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Date Range</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomPrices as $price)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="fw-bold">{{ $price->room->resort->name }}</span>
                                </td>
                                <td>{{ $price->room->name }}</td>
                                <td>
                                    @php
                                        $typeColors = [
                                            'normal' => 'bg-secondary',
                                            'weekend' => 'bg-info',
                                            'holiday' => 'bg-warning',
                                            'festival' => 'bg-danger',
                                            'seasonal' => 'bg-success'
                                        ];
                                    @endphp
                                    <span class="badge {{ $typeColors[$price->type] ?? 'bg-secondary' }}">
                                        {{ ucfirst($price->type) }}
                                    </span>
                                </td>
                                <td>
                                    <strong>৳ {{ number_format($price->price, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $price->from_date->format('d M Y') }}
                                    </span>
                                    <i class="fas fa-arrow-right text-muted mx-1"></i>
                                    <span class="badge bg-light text-dark">
                                        {{ $price->to_date->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info viewBtn" data-id="{{ $price->id }}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning editBtn" data-id="{{ $price->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $price->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted d-block mb-2"></i>
                                    <span class="text-muted">No room prices found</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $roomPrices->links() }}
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
            <form action="{{ route('admin.room-prices.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-primary"></i>
                        Add Room Price
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Resort <span class="text-danger">*</span></label>
                            <select name="resort_id" class="form-select" id="create_resort" required>
                                <option value="">Select Resort</option>
                                @foreach($resorts as $resort)
                                    <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Room <span class="text-danger">*</span></label>
                            <select name="room_id" id="create_room" class="form-select" required>
                                <option value="">Select Room</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">From Date <span class="text-danger">*</span></label>
                            <input type="date" name="from_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">To Date <span class="text-danger">*</span></label>
                            <input type="date" name="to_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="normal">Normal</option>
                                <option value="weekend">Weekend</option>
                                <option value="holiday">Holiday</option>
                                <option value="festival">Festival</option>
                                <option value="seasonal">Seasonal</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Room Price
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
                        Edit Room Price
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Resort <span class="text-danger">*</span></label>
                            <select name="resort_id" id="edit_resort" class="form-select" required>
                                @foreach($resorts as $resort)
                                    <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Room <span class="text-danger">*</span></label>
                            <select name="room_id" id="edit_room" class="form-select" required>
                                <option value="">Select Room</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">From Date <span class="text-danger">*</span></label>
                            <input type="date" name="from_date" id="edit_from_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">To Date <span class="text-danger">*</span></label>
                            <input type="date" name="to_date" id="edit_to_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                            <select name="type" id="edit_type" class="form-select" required>
                                <option value="normal">Normal</option>
                                <option value="weekend">Weekend</option>
                                <option value="holiday">Holiday</option>
                                <option value="festival">Festival</option>
                                <option value="seasonal">Seasonal</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i>
                        Update Room Price
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
                    Room Price Details
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
                            <th>Price</th>
                            <td id="v_price" class="text-success fw-bold"></td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td id="v_type"></td>
                        </tr>
                        <tr>
                            <th>From Date</th>
                            <td id="v_from_date"></td>
                        </tr>
                        <tr>
                            <th>To Date</th>
                            <td id="v_to_date"></td>
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
                    <p class="text-muted">You want to delete this room price? This action cannot be undone.</p>
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

        $.get('/admin/room-prices/get-rooms/' + resortId, function(data) {
            let html = '<option value="">Select Room</option>';
            $.each(data, function(i, room) {
                html += `<option value="${room.id}">${room.name}</option>`;
            });
            roomSelect.html(html);
        }).fail(function() {
            roomSelect.html('<option value="">Error loading rooms</option>');
        });
    });

    /**
     * ======================================
     * Edit Modal - Load Room Price Data
     * ======================================
     */
    $('.editBtn').click(function() {
        let id = $(this).data('id');

        $.get('/admin/room-prices/' + id, function(response) {
            let data = response.data;

            // Set form action
            $('#editForm').attr('action', '/admin/room-prices/' + id);

            // Set resort and trigger change to load rooms
            $('#edit_resort').val(data.room.resort_id).trigger('change');

            // Wait for rooms to load then set selected room
            setTimeout(function() {
                $('#edit_room').val(data.room_id);
            }, 300);

            // Set other fields
            $('#edit_price').val(data.price);
            $('#edit_from_date').val(data.from_date);
            $('#edit_to_date').val(data.to_date);
            $('#edit_type').val(data.type);

            // Show modal
            $('#editModal').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load room price data.'
            });
        });
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

        $.get('/admin/room-prices/get-rooms/' + resortId, function(data) {
            let html = '<option value="">Select Room</option>';
            $.each(data, function(i, room) {
                html += `<option value="${room.id}">${room.name}</option>`;
            });
            roomSelect.html(html);
        }).fail(function() {
            roomSelect.html('<option value="">Error loading rooms</option>');
        });
    });

    /**
     * ======================================
     * View Modal - Show Room Price Details
     * ======================================
     */
    $('.viewBtn').click(function() {
        let id = $(this).data('id');

        $.get('/admin/room-prices/' + id, function(response) {
            let data = response.data;

            $('#v_resort').text(data.room.resort.name);
            $('#v_room').text(data.room.name);
            $('#v_price').text('৳ ' + parseFloat(data.price).toFixed(2));
            
            // Type with badge
            let typeColors = {
                'normal': 'badge bg-secondary',
                'weekend': 'badge bg-info',
                'holiday': 'badge bg-warning',
                'festival': 'badge bg-danger',
                'seasonal': 'badge bg-success'
            };
            $('#v_type').html(`<span class="${typeColors[data.type] || 'badge bg-secondary'}">${data.type.charAt(0).toUpperCase() + data.type.slice(1)}</span>`);
            
            $('#v_from_date').text(new Date(data.from_date).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }));
            $('#v_to_date').text(new Date(data.to_date).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }));

            $('#viewModal').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load room price details.'
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
        let url = '/admin/room-prices/' + id;
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });

    /**
     * ======================================
     * SweetAlert for Delete Confirmation
     * (Alternative to Bootstrap Modal)
     * ======================================
     */
    // You can also use SweetAlert directly
    // $('.deleteBtn').click(function() {
    //     let id = $(this).data('id');
    //     let url = '/admin/room-prices/' + id;
    //     
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You want to delete this room price?",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#d33',
    //         cancelButtonColor: '#6c757d',
    //         confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: url,
    //                 type: 'DELETE',
    //                 data: {
    //                     _token: '{{ csrf_token() }}'
    //                 },
    //                 success: function() {
    //                     Swal.fire(
    //                         'Deleted!',
    //                         'Room price has been deleted.',
    //                         'success'
    //                     ).then(() => {
    //                         location.reload();
    //                     });
    //                 },
    //                 error: function() {
    //                     Swal.fire(
    //                         'Error!',
    //                         'Failed to delete room price.',
    //                         'error'
    //                     );
    //                 }
    //             });
    //         }
    //     });
    // });

    /**
     * ======================================
     * Form Validation - Prevent date conflicts
     * ======================================
     */
    $('#createForm, #editForm').on('submit', function(e) {
        let fromDate = $(this).find('[name="from_date"]').val();
        let toDate = $(this).find('[name="to_date"]').val();

        if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date Range',
                text: 'From date must be earlier than or equal to To date.'
            });
        }
    });

});
</script>
@endpush