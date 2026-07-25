<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">
            English Title
        </label>
        <input
            type="text"
            class="form-control"
            name="policies[{{ $policy->id }}][title_en]"
            value="{{ old('policies.'.$policy->id.'.title_en', $policy->title_en) }}"
            placeholder="Enter English title">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">
            Bangla Title
        </label>
        <input
            type="text"
            class="form-control"
            name="policies[{{ $policy->id }}][title_bn]"
            value="{{ old('policies.'.$policy->id.'.title_bn', $policy->title_bn) }}"
            placeholder="বাংলা শিরোনাম লিখুন">
    </div>

</div>

<div class="mb-3">
    <label class="form-label">
        English Content
    </label>
    <textarea
        class="form-control editor"
        id="editor_{{ $policy->id }}_en"
        rows="12"
        name="policies[{{ $policy->id }}][content_en]">{{ old('policies.'.$policy->id.'.content_en', $policy->content_en) }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">
        Bangla Content
    </label>
    <textarea
        class="form-control editor"
        id="editor_{{ $policy->id }}_bn"
        rows="12"
        name="policies[{{ $policy->id }}][content_bn]">{{ old('policies.'.$policy->id.'.content_bn', $policy->content_bn) }}</textarea>
</div>

<div class="form-check form-switch">
    <input
        class="form-check-input"
        type="checkbox"
        value="1"
        id="status_{{ $policy->id }}"
        name="policies[{{ $policy->id }}][status]"
        {{ $policy->status ? 'checked' : '' }}>
    <label class="form-check-label" for="status_{{ $policy->id }}">
        Publish this policy
    </label>
</div>