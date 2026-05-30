@extends('layouts.admin')
@section('title', 'All Destinations')
@section('page')

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
    :root {
        --ink:       #0f1117;
        --surface:   #ffffff;
        --muted:     #f4f5f8;
        --border:    #e3e6ef;
        --accent:    #2563eb;
        --accent-lt: #eff4ff;
        --danger:    #e53e3e;
        --success:   #16a34a;
        --radius:    14px;
        --shadow:    0 4px 24px rgba(15,17,23,.07);
    }

    body { font-family: 'DM Sans', sans-serif; background: #f7f8fc; }

    .page-hero {
        background: var(--ink);
        border-radius: var(--radius);
        padding: 28px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .page-hero h4 { font-weight: 800; font-size: 1.35rem; color: #fff; margin: 0; letter-spacing: -.02em; }
    .page-hero p  { color: #8b93a8; font-size: .84rem; margin: 4px 0 0; }

    .btn-hero {
        background: var(--accent); color: #fff; border: none;
        border-radius: 10px; padding: 10px 20px;
        font-family: 'Syne', sans-serif; font-weight: 600; font-size: .85rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
        transition: background .2s, transform .15s;
    }
    .btn-hero:hover { background: #1d4ed8; transform: translateY(-1px); }

    .dest-card {
        background: var(--surface); border-radius: var(--radius);
        box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden;
    }

    .dest-table { width: 100%; border-collapse: collapse; }
    .dest-table thead tr { background: var(--muted); border-bottom: 2px solid var(--border); }
    .dest-table th {
        font-family: 'Syne', sans-serif; font-size: .78rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; color: #6b7280;
        padding: 14px 20px; text-align: left;
    }
    .dest-table td {
        padding: 14px 20px; border-bottom: 1px solid var(--border);
        font-size: .875rem; color: var(--ink); vertical-align: middle;
    }
    .dest-table tbody tr:last-child td { border-bottom: none; }
    .dest-table tbody tr { transition: background .15s; }
    .dest-table tbody tr:hover { background: #fafbff; }

    .dest-img { width: 62px; height: 52px; object-fit: cover; border-radius: 9px; border: 1px solid var(--border); }
    .dest-name { font-weight: 500; }
    .dest-slug { color: #6b7280; font-size: .82rem; font-family: 'Courier New', monospace; }

    .badge-active   { background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 20px; font-size: .76rem; font-weight: 600; }
    .badge-inactive { background: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 20px; font-size: .76rem; font-weight: 600; }

    .action-btns { display: flex; gap: 8px; }
    .btn-icon {
        width: 34px; height: 34px; border-radius: 9px; border: 1px solid var(--border);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .8rem; cursor: pointer; transition: all .18s;
        background: var(--surface); text-decoration: none;
    }
    .btn-icon.view   { color: var(--accent); }
    .btn-icon.edit   { color: #d97706; }
    .btn-icon.delete { color: var(--danger); }
    .btn-icon:hover  { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }

    /* ── MODAL ── */
    .modal-backdrop-custom {
        position: fixed; inset: 0;
        background: rgba(10,12,20,.55);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity .25s;
    }
    .modal-backdrop-custom.open { opacity: 1; pointer-events: all; }

    .modal-box {
        background: var(--surface); border-radius: 18px;
        box-shadow: 0 24px 60px rgba(0,0,0,.22);
        width: 90%; max-width: 540px;
        transform: translateY(24px) scale(.97);
        transition: transform .28s cubic-bezier(.34,1.56,.64,1);
        overflow: hidden;
        max-height: 90vh;
        display: flex; flex-direction: column;
    }
    .modal-backdrop-custom.open .modal-box { transform: translateY(0) scale(1); }
    .modal-box.wide { max-width: 720px; }

    .modal-head {
        padding: 20px 26px 16px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        flex-shrink: 0;
    }
    .modal-head h5 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.05rem; margin: 0; color: var(--ink); }
    .modal-close {
        width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border);
        background: var(--muted); cursor: pointer; font-size: .9rem; color: #6b7280;
        display: flex; align-items: center; justify-content: center; transition: background .15s;
    }
    .modal-close:hover { background: #e5e7eb; }

    .modal-body { padding: 22px 26px; overflow-y: auto; flex: 1; }
    .modal-footer {
        padding: 14px 26px 20px; display: flex; gap: 10px;
        justify-content: flex-end; flex-shrink: 0;
        border-top: 1px solid var(--border); background: var(--muted);
    }

    /* ── FORM ── */
    .form-group { margin-bottom: 16px; }
    .form-group label {
        display: block; font-size: .8rem; font-weight: 700; color: #374151;
        margin-bottom: 6px; font-family: 'Syne', sans-serif; letter-spacing: .03em;
        text-transform: uppercase;
    }
    .form-group label .req { color: var(--danger); margin-left: 3px; }
    .form-control-custom {
        width: 100%; padding: 10px 14px; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: .875rem; font-family: 'DM Sans', sans-serif;
        color: var(--ink); background: var(--surface);
        transition: border .18s, box-shadow .18s; outline: none; box-sizing: border-box;
    }
    .form-control-custom:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
    textarea.form-control-custom { resize: vertical; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    /* FILE UPLOAD */
    .file-upload-area {
        border: 2px dashed var(--border); border-radius: 12px; padding: 18px;
        text-align: center; cursor: pointer;
        transition: border-color .2s, background .2s; position: relative;
    }
    .file-upload-area:hover { border-color: var(--accent); background: var(--accent-lt); }
    .file-upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .file-upload-area i { font-size: 1.4rem; color: #9ca3af; display: block; margin-bottom: 5px; }
    .file-upload-area span { font-size: .8rem; color: #6b7280; }

    /* CURRENT IMAGE BADGE */
    .current-img-wrap {
        display: flex; align-items: center; gap: 12px;
        background: var(--muted); border-radius: 10px; padding: 10px 14px;
        margin-bottom: 10px; border: 1px solid var(--border);
    }
    .current-img-wrap img { width: 56px; height: 44px; object-fit: cover; border-radius: 7px; border: 1px solid var(--border); }
    .current-img-wrap span { font-size: .8rem; color: #6b7280; }
    .current-img-wrap strong { font-size: .82rem; color: var(--ink); display: block; }

    .preview-new { display: none; width: 100px; height: 78px; object-fit: cover; border-radius: 9px; border: 1px solid var(--border); margin-top: 10px; }

    /* BTNS */
    .btn-primary-custom {
        background: var(--accent); color: #fff; border: none;
        padding: 10px 22px; border-radius: 10px;
        font-family: 'Syne', sans-serif; font-weight: 600; font-size: .85rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
        transition: background .2s, transform .15s;
    }
    .btn-primary-custom:hover { background: #1d4ed8; transform: translateY(-1px); }

    .btn-warning-custom {
        background: #d97706; color: #fff; border: none;
        padding: 10px 22px; border-radius: 10px;
        font-family: 'Syne', sans-serif; font-weight: 600; font-size: .85rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
        transition: background .2s, transform .15s;
    }
    .btn-warning-custom:hover { background: #b45309; transform: translateY(-1px); }

    .btn-ghost {
        background: var(--surface); color: #374151; border: 1px solid var(--border);
        padding: 10px 20px; border-radius: 10px;
        font-family: 'Syne', sans-serif; font-weight: 600; font-size: .85rem;
        cursor: pointer; transition: background .15s;
    }
    .btn-ghost:hover { background: #e5e7eb; }

    .btn-danger-custom {
        background: var(--danger); color: #fff; border: none;
        padding: 10px 22px; border-radius: 10px;
        font-family: 'Syne', sans-serif; font-weight: 600; font-size: .85rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
        transition: background .2s;
    }
    .btn-danger-custom:hover { background: #c53030; }

    /* VIEW MODAL */
    .view-img { width: 100%; max-height: 220px; object-fit: cover; border-radius: 12px; margin-bottom: 16px; border: 1px solid var(--border); }
    .view-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .view-meta-item label { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #9ca3af; font-family: 'Syne', sans-serif; }
    .view-meta-item p { font-size: .9rem; color: var(--ink); margin: 4px 0 0; font-weight: 500; }
    .view-desc { margin-top: 14px; }
    .view-desc label { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #9ca3af; font-family: 'Syne', sans-serif; }
    .view-desc p { font-size: .875rem; color: #4b5563; margin-top: 6px; line-height: 1.6; }

    /* DELETE MODAL */
    .delete-icon { width: 60px; height: 60px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 1.4rem; color: var(--danger); }
    .delete-modal-body { text-align: center; padding: 28px 28px 6px; }
    .delete-modal-body h5 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.05rem; color: var(--ink); margin: 0 0 8px; }
    .delete-modal-body p { color: #6b7280; font-size: .875rem; margin: 0; }

    .alert-success-custom {
        background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d;
        padding: 12px 18px; border-radius: 10px; font-size: .875rem;
        margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
    }

    .empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 12px; }
    .empty-state p { font-size: .9rem; }

    .error-msg { color: var(--danger); font-size: .76rem; margin-top: 4px; display: block; }
</style>

<!-- PAGE HERO -->
<div class="page-hero">
    <div>
        <h4><i class="fas fa-map-marker-alt" style="color:#60a5fa;margin-right:10px;"></i>Destinations</h4>
        <p>Manage all travel destinations</p>
    </div>
    <button class="btn-hero" onclick="openAddModal()">
        <i class="fas fa-plus"></i> Add Destination
    </button>
</div>

<!-- MAIN CARD -->
<div class="dest-card">
    <div class="card-body" style="padding:24px;">

        @if(session('success'))
            <div class="alert-success-custom">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="dest-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $key => $destination)
                    <tr>
                        <td style="color:#9ca3af;font-size:.8rem;font-weight:600;">{{ str_pad($key+1,2,'0',STR_PAD_LEFT) }}</td>
                        <td>
                            @if($destination->image)
                                <img src="{{ asset('uploads/destinations/'.$destination->image) }}" class="dest-img">
                            @else
                                <img src="{{ asset('contents/admin/images/no-image.png') }}" class="dest-img">
                            @endif
                        </td>
                        <td><span class="dest-name">{{ $destination->name }}</span></td>
                        <td><span class="dest-slug">{{ $destination->slug }}</span></td>
                        <td>
                            @if($destination->status == 1)
                                <span class="badge-active"><i class="fas fa-circle" style="font-size:.45rem;margin-right:5px;vertical-align:middle;"></i>Active</span>
                            @else
                                <span class="badge-inactive"><i class="fas fa-circle" style="font-size:.45rem;margin-right:5px;vertical-align:middle;"></i>Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">

                                {{-- VIEW --}}
                                <button class="btn-icon view" title="View Details"
                                    onclick="openViewModal(
                                        '{{ addslashes($destination->name) }}',
                                        '{{ $destination->slug }}',
                                        '{{ $destination->status }}',
                                        '{{ addslashes($destination->description ?? '') }}',
                                        '{{ $destination->image ? asset('uploads/destinations/'.$destination->image) : asset('contents/admin/images/no-image.png') }}'
                                    )">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- EDIT --}}
                                <button class="btn-icon edit" title="Edit"
                                    onclick="openEditModal(
                                        '{{ $destination->slug }}',
                                        '{{ addslashes($destination->name) }}',
                                        '{{ $destination->status }}',
                                        '{{ addslashes($destination->description ?? '') }}',
                                        '{{ $destination->image ? asset('uploads/destinations/'.$destination->image) : '' }}'
                                    )">
                                    <i class="fas fa-pen"></i>
                                </button>

                                {{-- DELETE --}}
                                <button class="btn-icon delete" title="Delete"
                                    onclick="openDeleteModal('{{ $destination->id }}', '{{ addslashes($destination->name) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-map-marked-alt"></i>
                                <p>No destinations found. Add your first one!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>


{{-- ══════════════════════════════════
     ADD MODAL
══════════════════════════════════ --}}
<div class="modal-backdrop-custom" id="addModal">
    <div class="modal-box wide">
        <div class="modal-head">
            <h5><i class="fas fa-plus-circle" style="color:var(--accent);margin-right:9px;"></i>Add Destination</h5>
            <button class="modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Destination Name <span class="req">*</span></label>
                        <input type="text" name="name" class="form-control-custom"
                               placeholder="e.g. Cox's Bazar" value="{{ old('name') }}">
                        @error('name')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control-custom">
                            <option value="1" {{ old('status','1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Destination Image</label>
                    <div class="file-upload-area">
                        <input type="file" name="image" id="addImageInput" accept="image/*"
                               onchange="previewAddImage(this)">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span id="addUploadLabel">Click to upload or drag & drop</span>
                    </div>
                    <div style="text-align:center;">
                        <img id="addPreview" class="preview-new" src="" alt="Preview">
                    </div>
                    @error('image')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" class="form-control-custom"
                              placeholder="Write a short description...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="btn-primary-custom">
                    <i class="fas fa-save"></i> Save Destination
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════
     EDIT MODAL
══════════════════════════════════ --}}
<div class="modal-backdrop-custom" id="editModal">
    <div class="modal-box wide">
        <div class="modal-head">
            <h5><i class="fas fa-pen" style="color:#d97706;margin-right:9px;"></i>Edit Destination</h5>
            <button class="modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="editForm" action="" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Destination Name <span class="req">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control-custom"
                               placeholder="e.g. Cox's Bazar">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="editStatus" class="form-control-custom">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Destination Image</label>
                    {{-- Current image preview --}}
                    <div class="current-img-wrap" id="editCurrentImgWrap" style="display:none;">
                        <img id="editCurrentImg" src="" alt="Current">
                        <div>
                            <strong>Current Image</strong>
                            <span>Upload a new one to replace it</span>
                        </div>
                    </div>
                    <div class="file-upload-area">
                        <input type="file" name="image" id="editImageInput" accept="image/*"
                               onchange="previewEditImage(this)">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span id="editUploadLabel">Click to upload new image (optional)</span>
                    </div>
                    <div style="text-align:center;">
                        <img id="editPreview" class="preview-new" src="" alt="New Preview">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="editDescription" rows="4" class="form-control-custom"
                              placeholder="Write a short description..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn-warning-custom">
                    <i class="fas fa-save"></i> Update Destination
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════
     VIEW MODAL
══════════════════════════════════ --}}
<div class="modal-backdrop-custom" id="viewModal">
    <div class="modal-box">
        <div class="modal-head">
            <h5><i class="fas fa-eye" style="color:var(--accent);margin-right:9px;"></i>Destination Details</h5>
            <button class="modal-close" onclick="closeModal('viewModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <img id="view-img" src="" alt="" class="view-img">
            <div class="view-meta">
                <div class="view-meta-item">
                    <label>Name</label>
                    <p id="view-name">—</p>
                </div>
                <div class="view-meta-item">
                    <label>Slug</label>
                    <p id="view-slug" style="font-family:'Courier New',monospace;font-size:.82rem;">—</p>
                </div>
                <div class="view-meta-item">
                    <label>Status</label>
                    <p id="view-status">—</p>
                </div>
            </div>
            <div class="view-desc">
                <label>Description</label>
                <p id="view-desc">—</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('viewModal')">Close</button>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════
     DELETE MODAL
══════════════════════════════════ --}}
<div class="modal-backdrop-custom" id="deleteModal">
    <div class="modal-box" style="max-width:420px;">
        <div class="delete-modal-body">
            <div class="delete-icon"><i class="fas fa-trash-alt"></i></div>
            <h5>Delete Destination?</h5>
            <p>You are about to delete <strong id="delete-name"></strong>.<br>This action cannot be undone.</p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            <div class="modal-footer" style="justify-content:center;padding-top:8px;">
                <button type="button" class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="submit" class="btn-danger-custom">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    /* ── HELPERS ── */
    function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }

    document.querySelectorAll('.modal-backdrop-custom').forEach(function(el) {
        el.addEventListener('click', function(e) { if (e.target === el) closeModal(el.id); });
    });

    /* ── ADD MODAL ── */
    function openAddModal() { openModal('addModal'); }

    function previewAddImage(input) {
        const img   = document.getElementById('addPreview');
        const label = document.getElementById('addUploadLabel');
        if (input.files && input.files[0]) {
            img.src = URL.createObjectURL(input.files[0]);
            img.style.display = 'block';
            label.textContent  = input.files[0].name;
        }
    }

    /* ── EDIT MODAL ── */
    function openEditModal(slug, name, status, description, imgSrc) {

        // Set form action using slug
        const baseUrl = "{{ url('admin/destinations/update') }}";
        document.getElementById('editForm').action = baseUrl + '/' + slug;

        // Fill fields
        document.getElementById('editName').value        = name;
        document.getElementById('editDescription').value = description;

        const sel = document.getElementById('editStatus');
        sel.value = status;

        // Current image
        const wrap = document.getElementById('editCurrentImgWrap');
        const img  = document.getElementById('editCurrentImg');
        if (imgSrc) {
            img.src = imgSrc;
            wrap.style.display = 'flex';
        } else {
            wrap.style.display = 'none';
        }

        // Reset new file preview
        document.getElementById('editPreview').style.display = 'none';
        document.getElementById('editImageInput').value      = '';
        document.getElementById('editUploadLabel').textContent = 'Click to upload new image (optional)';

        openModal('editModal');
    }

    function previewEditImage(input) {
        const img   = document.getElementById('editPreview');
        const label = document.getElementById('editUploadLabel');
        if (input.files && input.files[0]) {
            img.src = URL.createObjectURL(input.files[0]);
            img.style.display = 'block';
            label.textContent  = input.files[0].name;
        }
    }

    /* ── VIEW MODAL ── */
    function openViewModal(name, slug, status, desc, imgSrc) {
        document.getElementById('view-name').textContent = name;
        document.getElementById('view-slug').textContent = slug;
        document.getElementById('view-desc').textContent = desc || 'No description provided.';
        document.getElementById('view-img').src          = imgSrc;

        const s = document.getElementById('view-status');
        s.innerHTML = status == 1
            ? '<span class="badge-active"><i class="fas fa-circle" style="font-size:.45rem;margin-right:5px;vertical-align:middle;"></i>Active</span>'
            : '<span class="badge-inactive"><i class="fas fa-circle" style="font-size:.45rem;margin-right:5px;vertical-align:middle;"></i>Inactive</span>';

        openModal('viewModal');
    }

    /* ── DELETE MODAL ── */
    function openDeleteModal(id, name) {
        document.getElementById('delete-name').textContent = name;
        const baseUrl = "{{ url('admin/destinations/delete') }}";
        document.getElementById('deleteForm').action = baseUrl + '/' + id;
        openModal('deleteModal');
    }

    /* Auto-open Add modal on validation error */
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() { openAddModal(); });
    @endif
</script>

@endsection

