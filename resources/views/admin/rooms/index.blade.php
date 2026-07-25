@extends('layouts.admin')

@section('title','Room Management')

@section('page')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>
            <i class="fas fa-bed text-primary"></i>
            Room Management
        </h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i>
            Add Room
        </button>
    </div>

    <!-- Room List Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">All Rooms</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Room</th>
                        <th>Resort</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($room->featured_image)
                            <img src="{{ asset('storage/'.$room->featured_image) }}" width="70" class="rounded">
                            @endif
                        </td>
                        <td>
                            <b>{{ $room->name }}</b>
                            <br>
                            <small>{{ Str::limit($room->description,40) }}</small>
                        </td>
                        <td>{{ $room->resort->name }}</td>
                        <td>{{ $room->roomType->name ?? '-' }}</td>
                        <td>৳ {{ number_format($room->price) }}</td>
                        <td>{{ $room->capacity }} Persons</td>
                        <td>
                            @if($room->status)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm btn-view"
                                data-id="{{ $room->id }}"
                                data-name="{{ $room->name }}"
                                data-resort_name="{{ $room->resort->name }}"
                                data-roomtype_name="{{ $room->roomType->name ?? 'N/A' }}"
                                data-price="{{ $room->price }}"
                                data-capacity="{{ $room->capacity }}"
                                data-beds="{{ $room->beds }}"
                                data-bathrooms="{{ $room->bathrooms }}"
                                data-size="{{ $room->size }}"
                                data-size_unit="{{ $room->size_unit }}"
                                data-description="{{ $room->description }}"
                                data-featured="{{ $room->featured_image }}"
                                data-status="{{ $room->status }}"
                                data-facilities_names='@json($room->facilities->pluck("name"))'>
                                <i class="fa fa-eye"></i>
                            </button>

                            <button class="btn btn-warning btn-sm btn-edit"
                                data-id="{{ $room->id }}"
                                data-resort="{{ $room->resort_id }}"
                                data-roomtype="{{ $room->room_type_id }}"
                                data-name="{{ $room->name }}"
                                data-description="{{ $room->description }}"
                                data-price="{{ $room->price }}"
                                data-capacity="{{ $room->capacity }}"
                                data-beds="{{ $room->beds }}"
                                data-bathrooms="{{ $room->bathrooms }}"
                                data-size="{{ $room->size }}"
                                data-size_unit="{{ $room->size_unit }}"
                                data-status="{{ $room->status }}"
                                data-featured="{{ $room->featured_image }}"
                                data-gallery='@json($room->galleryImages->pluck("image_path"))'
                                data-facilities='@json($room->facilities->pluck("id"))'>
                                <i class="fa fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-sm btn-delete"
                                data-id="{{ $room->id }}"
                                data-name="{{ $room->name }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $rooms->links() }}
        </div>
    </div>

</div>

<!-- ============================================ -->
<!-- CREATE MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-bed"></i>
                        Add New Room
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Resort -->
                            <div class="mb-3">
                                <label class="form-label">Resort <span class="text-danger">*</span></label>
                                <select name="resort_id" class="form-control" required>
                                    <option value="">Select Resort</option>
                                    @foreach($resorts as $resort)
                                    <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Room Type -->
                            <div class="mb-3">
                                <label class="form-label">Room Type</label>
                                <select name="room_type_id" class="form-control">
                                    <option value="">Select Type</option>
                                    @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Room Name -->
                            <div class="mb-3">
                                <label class="form-label">Room Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
                            </div>

                            <!-- Capacity -->
                            <div class="mb-3">
                                <label class="form-label">Capacity <span class="text-danger">*</span></label>
                                <input type="number" name="capacity" class="form-control" value="2" required>
                            </div>

                            <!-- Beds -->
                            <div class="mb-3">
                                <label class="form-label">Beds</label>
                                <input type="number" name="beds" class="form-control" value="1">
                            </div>

                            <!-- Bathrooms -->
                            <div class="mb-3">
                                <label class="form-label">Bathrooms</label>
                                <input type="number" name="bathrooms" class="form-control" value="1">
                            </div>

                            <!-- Size -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Size</label>
                                        <input type="number" step="0.01" name="size" class="form-control" placeholder="Size">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Unit</label>
                                        <select name="size_unit" class="form-control">
                                            <option value="sqft">Sqft</option>
                                            <option value="sqm">Sqm</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="summernote"></textarea>
                            </div>

                            <!-- Featured Image -->
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control">
                            </div>

                            <!-- Gallery Images -->
                            <div class="mb-3">
                                <label class="form-label">Gallery Images</label>
                                <input type="file" multiple name="images[]" class="form-control">
                                <small class="text-muted">You can select multiple images</small>
                            </div>

                            <!-- Facilities -->
                            <div class="mb-3">
                                <label class="form-label">Facilities</label>
                                <div class="row">
                                    @foreach($facilities as $facility)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="facilities[]" value="{{ $facility->id }}">
                                            <label class="form-check-label">{{ $facility->name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i>
                        Edit Room
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Resort -->
                            <div class="mb-3">
                                <label class="form-label">Resort <span class="text-danger">*</span></label>
                                <select id="edit_resort" name="resort_id" class="form-control" required>
                                    @foreach($resorts as $resort)
                                    <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Room Type -->
                            <div class="mb-3">
                                <label class="form-label">Room Type</label>
                                <select id="edit_room_type" name="room_type_id" class="form-control">
                                    @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Room Name -->
                            <div class="mb-3">
                                <label class="form-label">Room Name <span class="text-danger">*</span></label>
                                <input id="edit_name" type="text" name="name" class="form-control" required>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <input id="edit_price" type="number" step="0.01" name="price" class="form-control" required>
                            </div>

                            <!-- Capacity -->
                            <div class="mb-3">
                                <label class="form-label">Capacity <span class="text-danger">*</span></label>
                                <input id="edit_capacity" type="number" name="capacity" class="form-control" required>
                            </div>

                            <!-- Beds -->
                            <div class="mb-3">
                                <label class="form-label">Beds</label>
                                <input id="edit_beds" type="number" name="beds" class="form-control">
                            </div>

                            <!-- Bathrooms -->
                            <div class="mb-3">
                                <label class="form-label">Bathrooms</label>
                                <input id="edit_bathrooms" type="number" name="bathrooms" class="form-control">
                            </div>

                            <!-- Size -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Size</label>
                                        <input id="edit_size" type="number" step="0.01" name="size" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Unit</label>
                                        <select id="edit_size_unit" name="size_unit" class="form-control">
                                            <option value="sqft">Sqft</option>
                                            <option value="sqm">Sqm</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea id="edit_description" name="description" class="summernote"></textarea>
                            </div>

                            <!-- Featured Image -->
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control">
                                <div id="featured_preview" class="mt-2"></div>
                            </div>

                            <!-- Gallery -->
                            <div class="mb-3">
                                <label class="form-label">Gallery</label>
                                <input multiple type="file" name="images[]" class="form-control">
                                <small class="text-muted">Add more images (existing images will be kept)</small>
                                <div id="gallery_preview" class="row mt-2"></div>
                            </div>

                            <!-- Facilities -->
                            <div class="mb-3">
                                <label class="form-label">Facilities</label>
                                <div class="row">
                                    @foreach($facilities as $facility)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input facility_checkbox" name="facilities[]" value="{{ $facility->id }}">
                                            <label class="form-check-label">{{ $facility->name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select id="edit_status" name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- VIEW MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye"></i>
                    Room Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td id="view_name">-</td>
                            </tr>
                            <tr>
                                <th>Resort</th>
                                <td id="view_resort">-</td>
                            </tr>
                            <tr>
                                <th>Room Type</th>
                                <td id="view_type">-</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td id="view_price">-</td>
                            </tr>
                            <tr>
                                <th>Capacity</th>
                                <td id="view_capacity">-</td>
                            </tr>
                            <tr>
                                <th>Beds</th>
                                <td id="view_beds">-</td>
                            </tr>
                            <tr>
                                <th>Bathrooms</th>
                                <td id="view_bathrooms">-</td>
                            </tr>
                            <tr>
                                <th>Size</th>
                                <td id="view_size">-</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="view_status">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold">Featured Image</label>
                            <div id="view_image" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Facilities</label>
                            <div id="view_facilities" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Description</label>
                            <div id="view_description" class="mt-2" style="max-height:200px;overflow-y:auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- DELETE MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash"></i>
                    Delete Room
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete_name"></strong>?</p>
                <p class="text-danger">This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- ============================================ -->
<!-- JAVASCRIPT -->
<!-- ============================================ -->
@push('scripts')
<script>
$(document).ready(function() {
    
    // ============================================
    // 1. SUMMERNOTE INITIALIZATION
    // ============================================
    $('.summernote').summernote({
        height: 180,
        placeholder: 'Write room description...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // ============================================
    // 2. CREATE MODAL - Reset on close
    // ============================================
    $('#createModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.summernote').summernote('code', '');
    });

    // ============================================
    // 3. EDIT BUTTON - Load data
    // ============================================
    $(document).on('click', '.btn-edit', function() {
        let room = $(this).data();
        
        // Set form action
        $('#editForm').attr('action', '/admin/rooms/' + room.id);
        
        // Fill basic fields
        $('#edit_resort').val(room.resort);
        $('#edit_room_type').val(room.roomtype || '');
        $('#edit_name').val(room.name);
        $('#edit_price').val(room.price);
        $('#edit_capacity').val(room.capacity);
        $('#edit_beds').val(room.beds);
        $('#edit_bathrooms').val(room.bathrooms);
        $('#edit_size').val(room.size);
        $('#edit_size_unit').val(room.size_unit);
        $('#edit_status').val(room.status);
        
        // Set description with Summernote
        $('#edit_description').summernote('code', room.description || '');
        
        // Featured Image Preview
        if (room.featured) {
            $('#featured_preview').html(
                '<img src="/storage/' + room.featured + 
                '" class="img-thumbnail" style="max-height:150px;">'
            );
        } else {
            $('#featured_preview').html('<p class="text-muted">No featured image</p>');
        }
        
        // Gallery Images Preview
        if (room.gallery && room.gallery.length > 0) {
            let galleryHtml = '<div class="row">';
            room.gallery.forEach(function(image) {
                galleryHtml += `
                    <div class="col-md-3 mb-2">
                        <img src="/storage/${image}" 
                             class="img-thumbnail" 
                             style="height:100px;width:100%;object-fit:cover;">
                    </div>
                `;
            });
            galleryHtml += '</div>';
            $('#gallery_preview').html(galleryHtml);
        } else {
            $('#gallery_preview').html('<p class="text-muted">No gallery images</p>');
        }
        
        // Facilities - Auto Check
        $('.facility_checkbox').prop('checked', false);
        if (room.facilities) {
            room.facilities.forEach(function(id) {
                $('.facility_checkbox[value="' + id + '"]').prop('checked', true);
            });
        }
        
        // Show modal
        $('#editModal').modal('show');
    });

    // ============================================
    // 4. VIEW BUTTON - Show details
    // ============================================
    $(document).on('click', '.btn-view', function() {
        let room = $(this).data();
        
        $('#view_name').text(room.name || 'N/A');
        $('#view_resort').text(room.resort_name || 'N/A');
        $('#view_type').text(room.roomtype_name || 'N/A');
        $('#view_price').text('৳ ' + (room.price ? Number(room.price).toLocaleString() : '0'));
        $('#view_capacity').text(room.capacity ? room.capacity + ' Persons' : 'N/A');
        $('#view_beds').text(room.beds ? room.beds + ' Beds' : 'N/A');
        $('#view_bathrooms').text(room.bathrooms ? room.bathrooms + ' Bathrooms' : 'N/A');
        $('#view_size').text(room.size ? room.size + ' ' + (room.size_unit || 'sqft') : 'N/A');
        
        // Description
        $('#view_description').html(room.description || 'No description available');
        
        // Featured Image
        if (room.featured) {
            $('#view_image').html(
                '<img src="/storage/' + room.featured + 
                '" class="img-fluid rounded" style="max-height:400px;">'
            );
        } else {
            $('#view_image').html('<p class="text-muted">No featured image</p>');
        }
        
        // Status Badge
        let statusBadge = room.status == 1 ? 
            '<span class="badge bg-success">Active</span>' : 
            '<span class="badge bg-danger">Inactive</span>';
        $('#view_status').html(statusBadge);
        
        // Facilities
        if (room.facilities_names && room.facilities_names.length > 0) {
            let facilitiesHtml = '';
            room.facilities_names.forEach(function(name) {
                facilitiesHtml += `<span class="badge bg-info me-1">${name}</span>`;
            });
            $('#view_facilities').html(facilitiesHtml);
        } else {
            $('#view_facilities').html('<span class="text-muted">No facilities</span>');
        }
        
        // Show modal
        $('#viewModal').modal('show');
    });

    // ============================================
    // 5. DELETE BUTTON
    // ============================================
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        
        $('#delete_name').text(name);
        $('#deleteForm').attr('action', '/admin/rooms/' + id);
        $('#deleteModal').modal('show');
    });

    // ============================================
    // 6. EDIT MODAL - Reset preview on close
    // ============================================
    $('#editModal').on('hidden.bs.modal', function() {
        $('#featured_preview').html('');
        $('#gallery_preview').html('');
        $('.facility_checkbox').prop('checked', false);
    });

});
</script>
@endpush