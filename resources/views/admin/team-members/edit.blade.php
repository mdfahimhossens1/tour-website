@extends('layouts.admin')

@section('title', 'Edit Team Member')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user-edit me-2"></i>
                Edit Team Member
            </h4>

            <p class="text-muted mb-0">
                Update team member information and settings.
            </p>
        </div>

        <a href="{{ route('admin.team-members.index') }}"
           class="btn btn-light border">

            <i class="fas fa-arrow-left me-1"></i>
            Back to Team Members

        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    {{-- =========================================================
        MAIN FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route('admin.team-members.update', $teamMember->id) }}"
          enctype="multipart/form-data">

        @csrf

        @method('PUT')


        <div class="row g-4">

            {{-- =================================================
                LEFT COLUMN
            ================================================== --}}
            <div class="col-xl-8">

                {{-- =================================================
                    BASIC INFORMATION
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user me-2 text-primary"></i>
                            Basic Information
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Name --}}
                            <div class="col-md-6">

                                <label for="name"
                                       class="form-label fw-semibold">

                                    Name
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $teamMember->name) }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Enter team member name"
                                       required>

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Designation --}}
                            <div class="col-md-6">

                                <label for="designation"
                                       class="form-label fw-semibold">

                                    Designation

                                </label>

                                <input type="text"
                                       id="designation"
                                       name="designation"
                                       value="{{ old('designation', $teamMember->designation) }}"
                                       class="form-control @error('designation') is-invalid @enderror"
                                       placeholder="e.g. Chief Executive Officer">

                                @error('designation')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Bio --}}
                            <div class="col-12">

                                <label for="bio"
                                       class="form-label fw-semibold">

                                    Short Bio

                                </label>

                                <textarea id="bio"
                                          name="bio"
                                          rows="5"
                                          class="form-control @error('bio') is-invalid @enderror"
                                          placeholder="Write a short biography...">{{ old('bio', $teamMember->bio) }}</textarea>

                                @error('bio')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    CONTACT INFORMATION
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-address-card me-2 text-primary"></i>
                            Contact Information
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Email --}}
                            <div class="col-md-6">

                                <label for="email"
                                       class="form-label fw-semibold">

                                    Email

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>

                                    <input type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $teamMember->email) }}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="member@example.com">

                                </div>

                                @error('email')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Phone --}}
                            <div class="col-md-6">

                                <label for="phone"
                                       class="form-label fw-semibold">

                                    Phone

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>

                                    <input type="text"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $teamMember->phone) }}"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="+880 1XXXXXXXXX">

                                </div>

                                @error('phone')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    SOCIAL MEDIA
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-share-alt me-2 text-primary"></i>
                            Social Media
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Facebook --}}
                            <div class="col-md-6">

                                <label for="facebook_url"
                                       class="form-label fw-semibold">

                                    <i class="fab fa-facebook text-primary me-1"></i>
                                    Facebook URL

                                </label>

                                <input type="url"
                                       id="facebook_url"
                                       name="facebook_url"
                                       value="{{ old('facebook_url', $teamMember->facebook_url) }}"
                                       class="form-control @error('facebook_url') is-invalid @enderror"
                                       placeholder="https://facebook.com/...">

                                @error('facebook_url')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Instagram --}}
                            <div class="col-md-6">

                                <label for="instagram_url"
                                       class="form-label fw-semibold">

                                    <i class="fab fa-instagram text-danger me-1"></i>
                                    Instagram URL

                                </label>

                                <input type="url"
                                       id="instagram_url"
                                       name="instagram_url"
                                       value="{{ old('instagram_url', $teamMember->instagram_url) }}"
                                       class="form-control @error('instagram_url') is-invalid @enderror"
                                       placeholder="https://instagram.com/...">

                                @error('instagram_url')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- LinkedIn --}}
                            <div class="col-md-6">

                                <label for="linkedin_url"
                                       class="form-label fw-semibold">

                                    <i class="fab fa-linkedin text-primary me-1"></i>
                                    LinkedIn URL

                                </label>

                                <input type="url"
                                       id="linkedin_url"
                                       name="linkedin_url"
                                       value="{{ old('linkedin_url', $teamMember->linkedin_url) }}"
                                       class="form-control @error('linkedin_url') is-invalid @enderror"
                                       placeholder="https://linkedin.com/in/...">

                                @error('linkedin_url')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Twitter / X --}}
                            <div class="col-md-6">

                                <label for="twitter_url"
                                       class="form-label fw-semibold">

                                    <i class="fab fa-x-twitter me-1"></i>
                                    Twitter / X URL

                                </label>

                                <input type="url"
                                       id="twitter_url"
                                       name="twitter_url"
                                       value="{{ old('twitter_url', $teamMember->twitter_url) }}"
                                       class="form-control @error('twitter_url') is-invalid @enderror"
                                       placeholder="https://x.com/...">

                                @error('twitter_url')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                RIGHT COLUMN
            ================================================== --}}
            <div class="col-xl-4">

                {{-- =================================================
                    PROFILE IMAGE
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-image me-2 text-primary"></i>
                            Profile Image
                        </h5>

                    </div>

                    <div class="card-body">

                        {{-- Image Preview --}}
                        <div class="text-center mb-3">

                            <div id="imagePreviewWrapper"
                                 class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border"
                                 style="width:150px;height:150px;overflow:hidden;">

                                @if($teamMember->image)

                                    <img id="imagePreview"
                                         src="{{ $teamMember->image_url }}"
                                         alt="{{ $teamMember->name }}"
                                         style="width:100%;height:100%;object-fit:cover;">

                                    <div id="imagePlaceholder"
                                         style="display:none;"
                                         class="text-center text-muted">

                                        <i class="fas fa-user fa-3x mb-2"></i>

                                        <div class="small">
                                            No image
                                        </div>

                                    </div>

                                @else

                                    <img id="imagePreview"
                                         src=""
                                         alt="Preview"
                                         style="display:none;width:100%;height:100%;object-fit:cover;">

                                    <div id="imagePlaceholder"
                                         class="text-center text-muted">

                                        <i class="fas fa-user fa-3x mb-2"></i>

                                        <div class="small">
                                            No image
                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>


                        <label for="image"
                               class="form-label fw-semibold">

                            Change Profile Image

                        </label>

                        <input type="file"
                               id="image"
                               name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept=".jpg,.jpeg,.png,.webp">

                        <div class="form-text">
                            Leave empty to keep the current image.
                            JPG, JPEG, PNG or WEBP. Maximum 2MB.
                        </div>

                        @error('image')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                    DISPLAY SETTINGS
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-sliders-h me-2 text-primary"></i>
                            Display Settings
                        </h5>

                    </div>

                    <div class="card-body">

                        {{-- Sort Order --}}
                        <div class="mb-4">

                            <label for="sort_order"
                                   class="form-label fw-semibold">

                                Sort Order

                            </label>

                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', $teamMember->sort_order) }}"
                                   min="0"
                                   max="999999"
                                   class="form-control @error('sort_order') is-invalid @enderror">

                            <div class="form-text">
                                Lower numbers appear first.
                            </div>

                            @error('sort_order')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Active --}}
                        <div class="form-check form-switch mb-3">

                            <input type="hidden"
                                   name="is_active"
                                   value="0">

                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $teamMember->is_active) ? 'checked' : '' }}>

                            <label class="form-check-label fw-semibold"
                                   for="is_active">

                                Active

                            </label>

                            <div class="small text-muted">
                                Show this member on the website.
                            </div>

                        </div>


                        {{-- Featured --}}
                        <div class="form-check form-switch">

                            <input type="hidden"
                                   name="is_featured"
                                   value="0">

                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   id="is_featured"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured', $teamMember->is_featured) ? 'checked' : '' }}>

                            <label class="form-check-label fw-semibold"
                                   for="is_featured">

                                Featured Member

                            </label>

                            <div class="small text-muted">
                                Mark this member as featured.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    MEMBER META
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body">

                        <div class="small text-muted mb-2">
                            <i class="fas fa-calendar-plus me-1"></i>
                            Created
                        </div>

                        <div class="fw-semibold mb-3">
                            {{ $teamMember->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </div>


                        <div class="small text-muted mb-2">
                            <i class="fas fa-calendar-check me-1"></i>
                            Last Updated
                        </div>

                        <div class="fw-semibold">
                            {{ $teamMember->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <button type="submit"
                                class="btn btn-primary w-100 mb-2">

                            <i class="fas fa-save me-1"></i>
                            Update Team Member

                        </button>

                        <a href="{{ route('admin.team-members.index') }}"
                           class="btn btn-light border w-100">

                            <i class="fas fa-times me-1"></i>
                            Cancel

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =============================================================
    IMAGE PREVIEW
============================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');

    if (!imageInput || !imagePreview || !imagePlaceholder) {
        return;
    }

    imageInput.addEventListener('change', function (event) {

        const file = event.target.files[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {

            imageInput.value = '';

            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            imagePreview.src = e.target.result;

            imagePreview.style.display = 'block';

            imagePlaceholder.style.display = 'none';

        };

        reader.readAsDataURL(file);

    });

});
</script>

@endsection