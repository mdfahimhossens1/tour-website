<div class="row">
    {{-- ===== BASIC INFORMATION ===== --}}
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2">{{ __('Basic Information') }}</h5>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
            <input 
                type="text" 
                name="name" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name', $teamMember->name ?? '') }}"
                placeholder="Enter full name"
                required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Sort Order') }}</label>
            <input 
                type="number" 
                name="sort_order" 
                class="form-control @error('sort_order') is-invalid @enderror" 
                value="{{ old('sort_order', $teamMember->sort_order ?? 0) }}"
                placeholder="0 = highest">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- ===== DESIGNATIONS ===== --}}
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2 mt-3">{{ __('Designation') }}</h5>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Designation (English)') }} <span class="text-danger">*</span></label>
            <input 
                type="text" 
                name="designation_en" 
                class="form-control @error('designation_en') is-invalid @enderror" 
                value="{{ old('designation_en', $teamMember->designation_en ?? '') }}"
                placeholder="e.g. CEO & Founder"
                required>
            @error('designation_en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Designation (Bangla)') }} <span class="text-danger">*</span></label>
            <input 
                type="text" 
                name="designation_bn" 
                class="form-control @error('designation_bn') is-invalid @enderror" 
                value="{{ old('designation_bn', $teamMember->designation_bn ?? '') }}"
                placeholder="যেমন: সিইও ও প্রতিষ্ঠাতা"
                required>
            @error('designation_bn')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- ===== CONTACT ===== --}}
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2 mt-3">{{ __('Contact Information') }}</h5>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Email Address') }}</label>
            <input 
                type="email" 
                name="email" 
                class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email', $teamMember->email ?? '') }}"
                placeholder="example@domain.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Phone Number') }}</label>
            <input 
                type="text" 
                name="phone" 
                class="form-control @error('phone') is-invalid @enderror" 
                value="{{ old('phone', $teamMember->phone ?? '') }}"
                placeholder="+880 1712-345678">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- ===== SOCIAL LINKS ===== --}}
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2 mt-3">{{ __('Social Links') }}</h5>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Facebook') }}</label>
            <input 
                type="url" 
                name="facebook" 
                class="form-control @error('facebook') is-invalid @enderror" 
                value="{{ old('facebook', $teamMember->facebook ?? '') }}"
                placeholder="https://facebook.com/username">
            @error('facebook')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('LinkedIn') }}</label>
            <input 
                type="url" 
                name="linkedin" 
                class="form-control @error('linkedin') is-invalid @enderror" 
                value="{{ old('linkedin', $teamMember->linkedin ?? '') }}"
                placeholder="https://linkedin.com/in/username">
            @error('linkedin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Twitter / X') }}</label>
            <input 
                type="url" 
                name="twitter" 
                class="form-control @error('twitter') is-invalid @enderror" 
                value="{{ old('twitter', $teamMember->twitter ?? '') }}"
                placeholder="https://twitter.com/username">
            @error('twitter')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- ===== BIO ===== --}}
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2 mt-3">{{ __('Biography') }}</h5>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bio (English)') }}</label>
            <textarea 
                name="bio_en" 
                class="form-control @error('bio_en') is-invalid @enderror" 
                rows="4"
                placeholder="Write a short biography in English...">{{ old('bio_en', $teamMember->bio_en ?? '') }}</textarea>
            @error('bio_en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bio (Bangla)') }}</label>
            <textarea 
                name="bio_bn" 
                class="form-control @error('bio_bn') is-invalid @enderror" 
                rows="4"
                placeholder="বাংলায় একটি সংক্ষিপ্ত জীবনী লিখুন...">{{ old('bio_bn', $teamMember->bio_bn ?? '') }}</textarea>
            @error('bio_bn')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- ===== IMAGE & STATUS ===== --}}
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2 mt-3">{{ __('Image & Settings') }}</h5>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Profile Photo') }}</label>
            <input 
                type="file" 
                name="image" 
                class="form-control @error('image') is-invalid @enderror" 
                accept="image/*">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if(isset($teamMember) && $teamMember->image)
                <div class="mt-2">
                    <img 
                        src="{{ asset('storage/'.$teamMember->image) }}" 
                        alt="{{ $teamMember->name }}"
                        class="rounded border" 
                        width="100">
                    <p class="text-muted small mt-1">Current photo</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Status') }}</label>
            <select 
                name="status" 
                class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $teamMember->status ?? 1) == 1 ? 'selected' : '' }}>
                    {{ __('Active') }}
                </option>
                <option value="0" {{ old('status', $teamMember->status ?? 1) == 0 ? 'selected' : '' }}>
                    {{ __('Inactive') }}
                </option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- ===== FORM ACTIONS ===== --}}
    <div class="col-md-12 mt-3">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ isset($teamMember) ? __('Update') : __('Create') }}
            </button>
            <a href="{{ route('admin.team-members.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('Cancel') }}
            </a>
        </div>
    </div>
</div>