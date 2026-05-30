@extends('layouts.admin')
@section('title', 'All Tour Packages')
@section('page')

<style>
    /* ── Modal Backdrop ── */
    .modal-backdrop.show { backdrop-filter: blur(4px); background: rgba(0,0,0,.55); }

    /* ── Modal Shell ── */
    .tour-modal .modal-dialog { max-width: 860px; margin: 1.5rem auto; }
    .tour-modal .modal-content {
        border: none;
        border-radius: 18px;
        box-shadow: 0 32px 80px rgba(0,0,0,.22);
        overflow: hidden;
    }

    /* ── Modal Header ── */
    .tour-modal .modal-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
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
        background: rgba(255,255,255,.12);
        width: 34px; height: 34px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem;
        flex-shrink: 0;
    }
    .tour-modal .btn-close { filter: invert(1) grayscale(1); opacity: .7; }
    .tour-modal .btn-close:hover { opacity: 1; }

    /* ── Modal Body ── */
    .tour-modal .modal-body {
        padding: 1.8rem;
        background: #f8f9fd;
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }
    .tour-modal .modal-body::-webkit-scrollbar { width: 5px; }
    .tour-modal .modal-body::-webkit-scrollbar-thumb { background: #cdd0e8; border-radius: 99px; }

    /* ── Modal Footer ── */
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

    /* ── Form Elements ── */
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
        transition: border-color .2s, box-shadow .2s;
        width: 100%;
    }
    .tour-modal .form-control:focus,
    .tour-modal .form-select:focus,
    .tour-modal select.form-control:focus {
        border-color: #0f3460;
        box-shadow: 0 0 0 3px rgba(15,52,96,.1);
        outline: none;
    }
    .tour-modal textarea.form-control { resize: vertical; }

    /* ── Section Dividers inside Modal ── */
    .modal-section {
        background: #fff;
        border: 1.5px solid #e3e5ef;
        border-radius: 14px;
        padding: 1.2rem 1.4rem;
        margin-bottom: 1.1rem;
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
        background: linear-gradient(to right, #e3e5ef, transparent);
        margin-left: .4rem;
    }

    /* ── Image Preview ── */
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
        width: 70px; height: 55px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e3e5ef;
    }
    .img-preview-wrap small { color: #7a7f9a; font-size: .78rem; }

    /* ── Badge Chips in Table ── */
    .badge-status, .badge-featured {
        padding: .35em .7em;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .3px;
    }

    /* ── Table Refinements ── */
    #toursTable thead th {
        background: #1a1a2e;
        color: #c9cfe8;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .7px;
        font-weight: 600;
        border: none;
        padding: .9rem .85rem;
    }
    #toursTable tbody tr {
        transition: background .15s;
        vertical-align: middle;
    }
    #toursTable tbody tr:hover { background: #f0f3ff; }
    #toursTable td { border-color: #eceef8; padding: .75rem .85rem; font-size: .88rem; color: #2d3050; }

    .tour-thumb {
        width: 54px; height: 44px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e3e5ef;
        display: block;
    }

    /* ── Action Buttons ── */
    .btn-act {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: .76rem;
        transition: transform .15s, box-shadow .15s;
        text-decoration: none;
        cursor: pointer;
        flex-shrink: 0;
    }
    .btn-act:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
    .btn-act-view  { background: #e8f4fd; color: #1a73e8; }
    .btn-act-edit  { background: #fff3e0; color: #f57c00; }
    .btn-act-del   { background: #fdecea; color: #d32f2f; }

    /* ── Actions column: keep buttons in one row ── */
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

    /* ── Add Tour Btn ── */
    .btn-add-tour {
        background: linear-gradient(135deg, #0f3460, #1a73e8);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .45rem 1.1rem;
        font-size: .85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: opacity .2s, transform .2s;
        cursor: pointer;
    }
    .btn-add-tour:hover { opacity: .9; transform: translateY(-1px); color: #fff; }

    /* ── Submit Btn ── */
    .btn-submit {
        background: linear-gradient(135deg, #0f3460, #1a73e8);
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
    .btn-submit:hover { opacity: .88; color: #fff; }

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
    .btn-cancel:hover { background: #d0d3e8; }

    /* ── View Modal Specific ── */
    .view-hero {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    .view-hero img { width: 100%; height: 220px; object-fit: cover; display: block; }
    .view-hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.6) 0%, transparent 60%);
    }
    .view-hero-title {
        position: absolute;
        bottom: 1rem; left: 1rem; right: 1rem;
        color: #fff;
        font-size: 1.15rem;
        font-weight: 700;
    }
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
    .view-info-value { font-size: .95rem; font-weight: 600; color: #2d3050; }
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
    .view-desc-content { font-size: .88rem; color: #4a4f6a; line-height: 1.6; }
</style>

{{-- INDEX CARD --}}
<div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">

    <div class="card-header d-flex justify-content-between align-items-center py-3 px-4"
         style="background:#fff; border-bottom: 1.5px solid #eceef8;">
        <div>
            <h5 class="mb-0 fw-bold" style="color:#1a1a2e;">Tour Packages</h5>
            <small class="text-muted">Manage all tour packages</small>
        </div>
        <button class="btn-add-tour" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Tour
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" id="toursTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Destination</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th style="min-width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tours as $tour)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            @if($tour->featured_image)
                                <img src="{{ asset('uploads/tours/'.$tour->featured_image) }}" class="tour-thumb" alt="{{ $tour->title }}">
                            @else
                                <img src="{{ asset('contents/admin/images/no-image.png') }}" class="tour-thumb" alt="No Image">
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $tour->title }}</td>
                        <td>{{ $tour->destination->name ?? 'N/A' }}</td>
                        <td class="fw-semibold" style="color:#0f3460;">৳ {{ number_format($tour->price, 2) }}</td>
                        <td>
                            @if($tour->status == 1)
                                <span class="badge badge-status bg-success">Active</span>
                            @else
                                <span class="badge badge-status bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($tour->is_featured == 1)
                                <span class="badge badge-featured bg-primary">Featured</span>
                            @else
                                <span class="badge badge-featured" style="background:#e3e5ef;color:#7a7f9a;">No</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-wrap">
                                {{-- View --}}
                                <button class="btn-act btn-act-view" title="View"
                                        onclick="openViewModal({{ $tour->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- Edit --}}
                                <button class="btn-act btn-act-edit" title="Edit"
                                        onclick="openEditModal({{ $tour->id }})">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.tours.delete', $tour->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this tour?')">
                                    @csrf
                                    <button type="submit" class="btn-act btn-act-del" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-2x mb-2 d-block opacity-25"></i>
                            No tour packages found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- ══════════════════ CREATE MODAL ══════════════════ --}}
<div class="modal fade tour-modal" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    <i class="fas fa-plus"></i> Add New Tour Package
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('admin.tours.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <div class="modal-section">
                        <div class="modal-section-title"><i class="fas fa-info-circle"></i> Basic Info</div>
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label>Tour Title *</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Cox's Bazar Beach Tour">
                                @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>Destination *</label>
                                <select name="destination_id" class="form-control">
                                    <option value="">Select Destination</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                            {{ $destination->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>Price *</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" placeholder="0.00">
                                @error('price')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="col-md-6">
                                <label>Discount Price</label>
                                <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ old('discount_price') }}" placeholder="0.00">
                            </div>

                            <div class="col-md-6">
                                <label>Duration</label>
                                <input type="text" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="3 Days 2 Nights">
                            </div>

                            <div class="col-md-6">
                                <label>Location</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. Chittagong, BD">
                            </div>

                            <div class="col-md-4">
                                <label>Max Seat</label>
                                <input type="number" name="max_seat" class="form-control" value="{{ old('max_seat') }}" placeholder="20">
                            </div>

                            <div class="col-md-4">
                                <label>Featured?</label>
                                <select name="is_featured" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title"><i class="fas fa-image"></i> Featured Image</div>
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                        @error('featured_image')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title"><i class="fas fa-align-left"></i> Descriptions</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label>Short Description</label>
                                <textarea name="short_description" rows="2" class="form-control" placeholder="Brief summary...">{{ old('short_description') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label>Full Description</label>
                                <textarea name="description" rows="4" class="form-control" placeholder="Detailed description...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title"><i class="fas fa-list-check"></i> Included & Excluded</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Included</label>
                                <textarea name="included" rows="4" class="form-control" placeholder="What's included...">{{ old('included') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Excluded</label>
                                <textarea name="excluded" rows="4" class="form-control" placeholder="What's not included...">{{ old('excluded') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title"><i class="fas fa-map-marked-alt"></i> Tour Plan & Map</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label>Tour Plan</label>
                                <textarea name="tour_plan" rows="4" class="form-control" placeholder="Day-by-day itinerary...">{{ old('tour_plan') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label>Google Map iframe</label>
                                <textarea name="map_iframe" rows="2" class="form-control" placeholder="Paste iframe embed code...">{{ old('map_iframe') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>{{-- end modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Save Tour Package
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


{{-- ══════════════════ EDIT MODAL ══════════════════ --}}
<div class="modal fade tour-modal" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-pencil-alt"></i> Edit Tour Package
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="modal-body" id="editModalBody">
                    <div class="text-center py-5 text-muted">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        Loading...
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Update Tour Package
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


{{-- ══════════════════ VIEW MODAL ══════════════════ --}}
<div class="modal fade tour-modal" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye"></i> Tour Package Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="viewModalBody">
                <div class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Loading...
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
// ── Edit Modal ──────────────────────────────────────────────────────────────
function openEditModal(tourId) {
    const modalEl = document.getElementById('editModal');
    const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
    const body    = document.getElementById('editModalBody');
    const form    = document.getElementById('editForm');

    body.innerHTML = `<div class="text-center py-5 text-muted">
        <div class="spinner-border spinner-border-sm me-2" role="status"></div> Loading...
    </div>`;

    modal.show();

    fetch(`/admin/tours/${tourId}/modal-data`)
        .then(r => r.json())
        .then(data => {
            const tour         = data.tour;
            const destinations = data.destinations;

            form.action = `/admin/tours/${tour.id}/update`;

            const destOptions = destinations.map(d =>
                `<option value="${d.id}" ${d.id == tour.destination_id ? 'selected' : ''}>${escHtml(d.name)}</option>`
            ).join('');

            const currentImg = tour.featured_image
                ? `<div class="img-preview-wrap">
                     <img src="/uploads/tours/${tour.featured_image}" alt="current">
                     <small>Current image — upload below to replace</small>
                   </div>`
                : '';

            body.innerHTML = `
            <div class="modal-section">
                <div class="modal-section-title"><i class="fas fa-info-circle"></i> Basic Info</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Tour Title *</label>
                        <input type="text" name="title" class="form-control" value="${escHtml(tour.title)}">
                    </div>
                    <div class="col-md-6">
                        <label>Destination *</label>
                        <select name="destination_id" class="form-control">
                            <option value="">Select Destination</option>
                            ${destOptions}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Price *</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="${tour.price}">
                    </div>
                    <div class="col-md-6">
                        <label>Discount Price</label>
                        <input type="number" step="0.01" name="discount_price" class="form-control" value="${tour.discount_price ?? ''}">
                    </div>
                    <div class="col-md-6">
                        <label>Duration</label>
                        <input type="text" name="duration" class="form-control" value="${escHtml(tour.duration ?? '')}">
                    </div>
                    <div class="col-md-6">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" value="${escHtml(tour.location ?? '')}">
                    </div>
                    <div class="col-md-4">
                        <label>Max Seat</label>
                        <input type="number" name="max_seat" class="form-control" value="${tour.max_seat ?? ''}">
                    </div>
                    <div class="col-md-4">
                        <label>Featured?</label>
                        <select name="is_featured" class="form-control">
                            <option value="1" ${tour.is_featured == 1 ? 'selected' : ''}>Yes</option>
                            <option value="0" ${tour.is_featured == 0 ? 'selected' : ''}>No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" ${tour.status == 1 ? 'selected' : ''}>Active</option>
                            <option value="0" ${tour.status == 0 ? 'selected' : ''}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <div class="modal-section-title"><i class="fas fa-image"></i> Featured Image</div>
                ${currentImg}
                <input type="file" name="featured_image" class="form-control" accept="image/*">
            </div>

            <div class="modal-section">
                <div class="modal-section-title"><i class="fas fa-align-left"></i> Descriptions</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label>Short Description</label>
                        <textarea name="short_description" rows="2" class="form-control">${escHtml(tour.short_description ?? '')}</textarea>
                    </div>
                    <div class="col-12">
                        <label>Full Description</label>
                        <textarea name="description" rows="4" class="form-control">${escHtml(tour.description ?? '')}</textarea>
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <div class="modal-section-title"><i class="fas fa-list-check"></i> Included & Excluded</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Included</label>
                        <textarea name="included" rows="4" class="form-control">${escHtml(tour.included ?? '')}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Excluded</label>
                        <textarea name="excluded" rows="4" class="form-control">${escHtml(tour.excluded ?? '')}</textarea>
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <div class="modal-section-title"><i class="fas fa-map-marked-alt"></i> Tour Plan & Map</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label>Tour Plan</label>
                        <textarea name="tour_plan" rows="4" class="form-control">${escHtml(tour.tour_plan ?? '')}</textarea>
                    </div>
                    <div class="col-12">
                        <label>Google Map iframe</label>
                        <textarea name="map_iframe" rows="2" class="form-control">${escHtml(tour.map_iframe ?? '')}</textarea>
                    </div>
                </div>
            </div>`;
        })
        .catch(() => {
            body.innerHTML = `<div class="alert alert-danger m-0">Failed to load tour data. Please try again.</div>`;
        });
}


// ── View Modal ──────────────────────────────────────────────────────────────
function openViewModal(tourId) {
    const modalEl = document.getElementById('viewModal');
    const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
    const body    = document.getElementById('viewModalBody');

    body.innerHTML = `<div class="text-center py-5 text-muted">
        <div class="spinner-border spinner-border-sm me-2" role="status"></div> Loading...
    </div>`;

    modal.show();

    fetch(`/admin/tours/${tourId}/modal-data`)
        .then(r => r.json())
        .then(data => {
            const t = data.tour;

            const imgSrc = t.featured_image
                ? `/uploads/tours/${t.featured_image}`
                : `/contents/admin/images/no-image.png`;

            const statusBadge   = t.status == 1
                ? `<span class="badge bg-success">Active</span>`
                : `<span class="badge bg-danger">Inactive</span>`;

            const featuredBadge = t.is_featured == 1
                ? `<span class="badge bg-primary">Featured</span>`
                : `<span class="badge bg-secondary">No</span>`;

            const section = (label, content) => content
                ? `<div class="view-desc-block">
                     <div class="view-desc-label">${label}</div>
                     <div class="view-desc-content">${escHtml(content)}</div>
                   </div>`
                : '';

            body.innerHTML = `
            <div class="view-hero">
                <img src="${imgSrc}" alt="${escHtml(t.title)}">
                <div class="view-hero-overlay"></div>
                <div class="view-hero-title">${escHtml(t.title)}</div>
            </div>

            <div class="view-info-grid">
                <div class="view-info-item">
                    <div class="view-info-label">Destination</div>
                    <div class="view-info-value">${escHtml(t.destination_name ?? 'N/A')}</div>
                </div>
                <div class="view-info-item">
                    <div class="view-info-label">Price</div>
                    <div class="view-info-value" style="color:#0f3460;">৳ ${Number(t.price).toLocaleString('en', {minimumFractionDigits:2})}</div>
                </div>
                <div class="view-info-item">
                    <div class="view-info-label">Duration</div>
                    <div class="view-info-value">${escHtml(t.duration ?? '—')}</div>
                </div>
                <div class="view-info-item">
                    <div class="view-info-label">Location</div>
                    <div class="view-info-value">${escHtml(t.location ?? '—')}</div>
                </div>
                <div class="view-info-item">
                    <div class="view-info-label">Max Seat</div>
                    <div class="view-info-value">${t.max_seat ?? '—'}</div>
                </div>
                <div class="view-info-item">
                    <div class="view-info-label">Status / Featured</div>
                    <div class="view-info-value d-flex gap-1 flex-wrap">${statusBadge} ${featuredBadge}</div>
                </div>
            </div>

            ${section('Short Description', t.short_description)}
            ${section('Description', t.description)}
            ${section('Included', t.included)}
            ${section('Excluded', t.excluded)}
            ${section('Tour Plan', t.tour_plan)}`;
        })
        .catch(() => {
            body.innerHTML = `<div class="alert alert-danger m-0">Failed to load tour data. Please try again.</div>`;
        });
}


// ── HTML escape helper ──────────────────────────────────────────────────────
function escHtml(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>
@endpush