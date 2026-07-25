@extends('layouts.admin')
@section('title', 'Manage Resorts')
@section('page')

<style>
    /* Custom Styles */
    .modal-header {
        border-bottom: none;
    }
    
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
    }
    
    .btn-group .btn:hover {
        transform: scale(1.05);
        transition: all 0.2s;
    }
    
    .btn-view {
        background: #17a2b8;
        color: white;
    }
    
    .btn-view:hover {
        background: #138496;
        color: white;
    }
    
    .btn-edit {
        background: #ffc107;
        color: #212529;
    }
    
    .btn-edit:hover {
        background: #e0a800;
        color: #212529;
    }
    
    .btn-delete {
        background: #dc3545;
        color: white;
    }
    
    .btn-delete:hover {
        background: #c82333;
        color: white;
    }
    
    /* Image Preview */
    .image-preview {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #ddd;
    }
    
    /* View Modal Styles */
    #view_description {
        background: #f8f9fa;
        max-height: 150px;
        overflow-y: auto;
    }
    
    #view_map iframe {
        width: 100%;
        height: 200px;
        border: none;
        border-radius: 5px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }
        
        .table-responsive {
            font-size: 14px;
        }
        
        .btn-group .btn {
            padding: 0.25rem 0.4rem;
        }
    }
    
    /* Summernote override */
    .note-editor {
        border-radius: 8px;
    }
    
    .note-editor .note-toolbar {
        background: #f8f9fa;
        border-radius: 8px 8px 0 0;
    }
</style>

<div class="container-fluid">
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-hotel"></i> Manage Resorts</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add New Resort
        </button>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ============================================ -->
    <!-- CREATE MODAL -->
    <!-- ============================================ -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-hotel"></i> Add New Resort
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.resorts.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <!-- Vendor -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Vendor <span class="text-danger">*</span></label>
                                    <select name="vendor_id" class="form-select" required>
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->business_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Destination -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Destination <span class="text-danger">*</span></label>
                                    <select name="destination_id" class="form-select" required>
                                        <option value="">Select Destination</option>
                                        @foreach($destinations as $destination)
                                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Resort Name -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Resort Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter resort name" required>
                                </div>

                                <!-- Division -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Division <span class="text-danger">*</span></label>
                                    <input type="text" name="division" class="form-control" placeholder="e.g., Dhaka" required>
                                </div>

                                <!-- District -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">District <span class="text-danger">*</span></label>
                                    <input type="text" name="district" class="form-control" placeholder="e.g., Cox's Bazar" required>
                                </div>

                                <!-- Area -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Area</label>
                                    <input type="text" name="area" class="form-control" placeholder="e.g., Kolatoli">
                                </div>

                                <!-- Address -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="Full address" required></textarea>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <!-- Short Description -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="2" placeholder="Brief description (max 200 chars)"></textarea>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control summernote" rows="4" placeholder="Full description"></textarea>
                                </div>

                                <!-- Google Map -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Google Map Embed</label>
                                    <textarea name="google_map" class="form-control" rows="2" placeholder="Paste Google Map iframe code"></textarea>
                                </div>

                                <!-- Check In/Out -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Check In</label>
                                            <input type="time" name="check_in" class="form-control" value="14:00">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Check Out</label>
                                            <input type="time" name="check_out" class="form-control" value="12:00">
                                        </div>
                                    </div>
                                </div>

                                <!-- Images -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Featured Image</label>
                                    <input type="file" name="featured_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Max size: 4MB (JPG, PNG, WEBP)</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cover Image</label>
                                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Max size: 4MB (JPG, PNG, WEBP)</small>
                                </div>

                                <!-- SEO -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control" placeholder="SEO Title">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" rows="2" placeholder="SEO Description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Resort
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- EDIT MODAL -->
    <!-- ============================================ -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-edit"></i> Edit Resort
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Vendor <span class="text-danger">*</span></label>
                                    <select name="vendor_id" class="form-select" id="edit_vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->business_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Destination <span class="text-danger">*</span></label>
                                    <select name="destination_id" class="form-select" id="edit_destination_id" required>
                                        <option value="">Select Destination</option>
                                        @foreach($destinations as $destination)
                                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Resort Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" id="edit_name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Division <span class="text-danger">*</span></label>
                                    <input type="text" name="division" class="form-control" id="edit_division" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">District <span class="text-danger">*</span></label>
                                    <input type="text" name="district" class="form-control" id="edit_district" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Area</label>
                                    <input type="text" name="area" class="form-control" id="edit_area">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" id="edit_address" rows="2" required></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Short Description</label>
                                    <textarea name="short_description" class="form-control" id="edit_short_description" rows="2"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control summernote" id="edit_description" rows="4"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Google Map Embed</label>
                                    <textarea name="google_map" class="form-control" id="edit_google_map" rows="2"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Check In</label>
                                            <input type="time" name="check_in" class="form-control" id="edit_check_in">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Check Out</label>
                                            <input type="time" name="check_out" class="form-control" id="edit_check_out">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Featured Image</label>
                                    <input type="file" name="featured_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                    <div id="edit_featured_preview" class="mt-2"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cover Image</label>
                                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                    <div id="edit_cover_preview" class="mt-2"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control" id="edit_meta_title">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" id="edit_meta_description" rows="2"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update Resort
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- VIEW MODAL -->
    <!-- ============================================ -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <h5 class="modal-title">
                        <i class="fas fa-eye"></i> Resort Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Resort Name:</label>
                                <p id="view_name" class="border-bottom pb-1"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Vendor:</label>
                                <p id="view_vendor" class="border-bottom pb-1"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Destination:</label>
                                <p id="view_destination" class="border-bottom pb-1"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Location:</label>
                                <p id="view_location" class="border-bottom pb-1"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Address:</label>
                                <p id="view_address" class="border-bottom pb-1"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Check In:</label>
                                <p id="view_check_in" class="border-bottom pb-1"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Check Out:</label>
                                <p id="view_check_out" class="border-bottom pb-1"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Short Description:</label>
                                <p id="view_short_description" class="border-bottom pb-1"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Description:</label>
                                <div id="view_description" class="border p-2 rounded bg-light"></div>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Images:</label>
                                <div id="view_images" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="fw-bold text-primary">Google Map:</label>
                                <div id="view_map" class="border p-2 rounded"></div>
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
                <div class="modal-header" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-trash-alt"></i> Delete Resort
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 48px;"></i>
                    <h4 class="mt-3">Are you sure?</h4>
                    <p>You want to delete <strong id="delete_name" class="text-danger"></strong>?</p>
                    <p class="text-muted">This action cannot be undone! All associated data will be deleted.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- DATA TABLE -->
    <!-- ============================================ -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-list"></i> All Resorts
            <span class="badge bg-light text-dark ms-2">{{ $resorts->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="resortTable">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="70">Image</th>
                            <th>Name</th>
                            <th>Vendor</th>
                            <th>Destination</th>
                            <th>Location</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resorts as $resort)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($resort->featured_image)
                                        <img src="{{ asset('storage/' . $resort->featured_image) }}" 
                                             alt="{{ $resort->name }}" 
                                             width="50" 
                                             height="50" 
                                             class="rounded"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $resort->name }}</strong>
                                    @if($resort->short_description)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($resort->short_description, 40) }}</small>
                                    @endif
                                </td>
                                <td>{{ $resort->vendor->business_name ?? 'N/A' }}</td>
                                <td>{{ $resort->destination->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $resort->division }}
                                    @if($resort->district)
                                        , {{ $resort->district }}
                                    @endif
                                    @if($resort->area)
                                        , {{ $resort->area }}
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info btn-view" 
                                                data-id="{{ $resort->id }}"
                                                data-name="{{ $resort->name }}"
                                                data-vendor="{{ $resort->vendor->business_name ?? 'N/A' }}"
                                                data-destination="{{ $resort->destination->name ?? 'N/A' }}"
                                                data-division="{{ $resort->division }}"
                                                data-district="{{ $resort->district }}"
                                                data-area="{{ $resort->area }}"
                                                data-address="{{ $resort->address }}"
                                                data-check_in="{{ $resort->check_in }}"
                                                data-check_out="{{ $resort->check_out }}"
                                                data-short_description="{{ $resort->short_description }}"
                                                data-description="{{ $resort->description }}"
                                                data-google_map="{{ $resort->google_map }}"
                                                data-featured_image="{{ $resort->featured_image }}"
                                                data-cover_image="{{ $resort->cover_image }}"
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <button class="btn btn-warning btn-edit" 
                                                data-id="{{ $resort->id }}"
                                                data-name="{{ $resort->name }}"
                                                data-vendor_id="{{ $resort->vendor_id }}"
                                                data-destination_id="{{ $resort->destination_id }}"
                                                data-division="{{ $resort->division }}"
                                                data-district="{{ $resort->district }}"
                                                data-area="{{ $resort->area }}"
                                                data-address="{{ $resort->address }}"
                                                data-check_in="{{ $resort->check_in }}"
                                                data-check_out="{{ $resort->check_out }}"
                                                data-short_description="{{ $resort->short_description }}"
                                                data-description="{{ $resort->description }}"
                                                data-google_map="{{ $resort->google_map }}"
                                                data-meta_title="{{ $resort->meta_title }}"
                                                data-meta_description="{{ $resort->meta_description }}"
                                                data-featured_image="{{ $resort->featured_image }}"
                                                data-cover_image="{{ $resort->cover_image }}"
                                                title="Edit Resort">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <button class="btn btn-danger btn-delete" 
                                                data-id="{{ $resort->id }}"
                                                data-name="{{ $resort->name }}"
                                                title="Delete Resort">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-hotel fa-3x text-muted mb-3 d-block"></i>
                                    <h5>No Resorts Found</h5>
                                    <p class="text-muted">Click "Add New Resort" to create your first resort.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($resorts->hasPages())
                <div class="d-flex justify-content-end mt-3">
                    {{ $resorts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<script>
$(document).ready(function() {
    // Initialize Summernote for all textareas with class 'summernote'
    $('.summernote').summernote({
        height: 150,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });

    // ============================================
    // VIEW BUTTON
    // ============================================
    $('.btn-view').click(function() {
        const data = $(this).data();
        
        $('#view_name').text(data.name || 'N/A');
        $('#view_vendor').text(data.vendor || 'N/A');
        $('#view_destination').text(data.destination || 'N/A');
        $('#view_location').text(
            [data.division, data.district, data.area].filter(Boolean).join(', ') || 'N/A'
        );
        $('#view_address').text(data.address || 'N/A');
        $('#view_check_in').text(data.check_in || 'N/A');
        $('#view_check_out').text(data.check_out || 'N/A');
        $('#view_short_description').text(data.short_description || 'N/A');
        $('#view_description').html(data.description || 'N/A');
        
        // Images
        let imagesHtml = '';
        if (data.featured_image) {
            imagesHtml += `
                <div class="d-inline-block me-2 text-center">
                    <img src="{{ asset('storage') }}/${data.featured_image}" 
                         class="image-preview" 
                         alt="Featured">
                    <br><small class="text-muted">Featured</small>
                </div>
            `;
        }
        if (data.cover_image) {
            imagesHtml += `
                <div class="d-inline-block me-2 text-center">
                    <img src="{{ asset('storage') }}/${data.cover_image}" 
                         class="image-preview" 
                         alt="Cover">
                    <br><small class="text-muted">Cover</small>
                </div>
            `;
        }
        $('#view_images').html(imagesHtml || '<p class="text-muted">No images</p>');
        
        // Google Map
        if (data.google_map) {
            $('#view_map').html(data.google_map);
        } else {
            $('#view_map').html('<p class="text-muted">No map available</p>');
        }
        
        $('#viewModal').modal('show');
    });

    // ============================================
    // EDIT BUTTON
    // ============================================
    $('.btn-edit').click(function() {
        const data = $(this).data();
        const id = data.id;
        
        // Set form action
        $('#editForm').attr('action', `{{ url('admin/resorts/update') }}/${id}`);
        
        // Populate fields
        $('#edit_name').val(data.name || '');
        $('#edit_vendor_id').val(data.vendor_id || '');
        $('#edit_destination_id').val(data.destination_id || '');
        $('#edit_division').val(data.division || '');
        $('#edit_district').val(data.district || '');
        $('#edit_area').val(data.area || '');
        $('#edit_address').val(data.address || '');
        $('#edit_check_in').val(data.check_in || '');
        $('#edit_check_out').val(data.check_out || '');
        $('#edit_short_description').val(data.short_description || '');
        $('#edit_google_map').val(data.google_map || '');
        $('#edit_meta_title').val(data.meta_title || '');
        $('#edit_meta_description').val(data.meta_description || '');
        
        // Set description in Summernote
        $('#edit_description').summernote('code', data.description || '');
        
        // Image previews
        if (data.featured_image) {
            $('#edit_featured_preview').html(`
                <img src="{{ asset('storage') }}/${data.featured_image}" 
                     class="image-preview" 
                     alt="Current Featured">
                <br><small class="text-muted">Current featured image</small>
            `);
        } else {
            $('#edit_featured_preview').html('<small class="text-muted">No featured image</small>');
        }
        
        if (data.cover_image) {
            $('#edit_cover_preview').html(`
                <img src="{{ asset('storage') }}/${data.cover_image}" 
                     class="image-preview" 
                     alt="Current Cover">
                <br><small class="text-muted">Current cover image</small>
            `);
        } else {
            $('#edit_cover_preview').html('<small class="text-muted">No cover image</small>');
        }
        
        $('#editModal').modal('show');
    });

    // ============================================
    // DELETE BUTTON
    // ============================================
    $('.btn-delete').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        $('#delete_name').text(name);
        $('#deleteForm').attr('action', `{{ url('admin/resorts/delete') }}/${id}`);
        $('#deleteModal').modal('show');
    });
});
</script>
@endpush