@extends('layouts.vendor')

@section('title', 'Add Facility')

@section('page')

<style>
    .facility-wrap {
        max-width: 850px;
        margin: 0 auto;
    }

    .facility-header {
        background: linear-gradient(
            135deg,
            #0c1a2e 0%,
            #0e0c2e 55%,
            #0c1a2e 100%
        );

        border-radius: 14px;
        padding: 26px 28px;
        margin-bottom: 22px;
        box-shadow: 0 8px 32px rgba(0,0,0,.35);
    }

    .facility-title {
        color: #fff;
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .facility-subtitle {
        color: rgba(255,255,255,.5);
        font-size: .8rem;
    }

    .facility-card {
        background: #1a1d27;
        border: 1px solid rgba(255,255,255,.07);
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 8px 32px rgba(0,0,0,.35);
    }

    .facility-label {
        display: block;
        color: #cbd5e1;
        font-size: .75rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .facility-label span {
        color: #ef4444;
    }

    .facility-input,
    .facility-select {
        width: 100%;
        background: #222636;
        border: 1px solid rgba(255,255,255,.08);
        color: #e2e8f0;
        border-radius: 8px;
        padding: 11px 13px;
        font-size: .8rem;
        outline: none;
        transition: .2s;
    }

    .facility-input:focus,
    .facility-select:focus {
        border-color: rgba(99,102,241,.6);
        box-shadow: 0 0 0 3px rgba(99,102,241,.08);
    }

    .facility-input::placeholder {
        color: #64748b;
    }

    .facility-select option {
        background: #222636;
        color: #e2e8f0;
    }

    .facility-help {
        color: #64748b;
        font-size: .68rem;
        margin-top: 6px;
    }

    .facility-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 20px;
    }

    .facility-field {
        margin-bottom: 20px;
    }

    .facility-preview {
        margin-top: 8px;
        padding: 13px 15px;
        background: rgba(99,102,241,.06);
        border: 1px solid rgba(99,102,241,.12);
        border-radius: 9px;
        color: #a5b4fc;
        font-size: .72rem;
    }

    .facility-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding-top: 8px;
        border-top: 1px solid rgba(255,255,255,.07);
    }

    .facility-btn {
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: .76rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .facility-btn-back {
        background: #222636;
        border: 1px solid rgba(255,255,255,.08);
        color: #94a3b8;
    }

    .facility-btn-back:hover {
        background: #2b3042;
        color: #fff;
    }

    .facility-btn-save {
        background: #6366f1;
        color: #fff;
    }

    .facility-btn-save:hover {
        background: #4f46e5;
        transform: translateY(-1px);
    }

    .facility-error {
        color: #f87171;
        font-size: .68rem;
        margin-top: 5px;
    }

    @media(max-width: 700px) {
        .facility-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .facility-card {
            padding: 20px;
        }

        .facility-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .facility-btn {
            text-align: center;
            width: 100%;
        }
    }
</style>


<div class="facility-wrap">

    {{-- HEADER --}}
    <div class="facility-header">

        <div class="facility-title">
            <i class="fas fa-plus-circle me-2"></i>
            Add New Facility
        </div>

        <div class="facility-subtitle">
            Create a custom facility for your resort or rooms.
        </div>

    </div>


    {{-- FORM --}}
    <div class="facility-card">

        <form
            method="POST"
            action="{{ route('vendor.facilities.store') }}"
        >

            @csrf


            {{-- NAME + TYPE --}}
            <div class="facility-row">

                {{-- NAME --}}
                <div>

                    <label class="facility-label">
                        Facility Name <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="facility-input"
                        placeholder="e.g. Swimming Pool"
                        required
                    >

                    @error('name')
                        <div class="facility-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- TYPE --}}
                <div>

                    <label class="facility-label">
                        Facility Type <span>*</span>
                    </label>

                    <select
                        name="type"
                        class="facility-select"
                        required
                    >

                        <option value="">
                            Select Facility Type
                        </option>

                        <option
                            value="resort"
                            {{ old('type') === 'resort' ? 'selected' : '' }}
                        >
                            Resort Facility
                        </option>

                        <option
                            value="room"
                            {{ old('type') === 'room' ? 'selected' : '' }}
                        >
                            Room Facility
                        </option>

                    </select>

                    @error('type')
                        <div class="facility-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- ICON --}}
            <div class="facility-field">

                <label class="facility-label">
                    Icon
                </label>

                <input
                    type="text"
                    name="icon"
                    value="{{ old('icon') }}"
                    class="facility-input"
                    placeholder="e.g. fas fa-swimming-pool"
                >

                <div class="facility-help">
                    Use a Font Awesome icon class.
                    Example:
                    <strong>fas fa-wifi</strong>,
                    <strong>fas fa-car</strong>,
                    <strong>fas fa-utensils</strong>
                </div>

                @error('icon')
                    <div class="facility-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- INFO --}}
            <div class="facility-preview">

                <i class="fas fa-info-circle me-1"></i>

                After creating this facility, you can assign it to your
                <strong>Resort</strong> or individual
                <strong>Rooms</strong> from their respective management pages.

            </div>


            {{-- ACTIONS --}}
            <div class="facility-actions mt-4">

                <a
                    href="{{ route('vendor.facilities.index') }}"
                    class="facility-btn facility-btn-back"
                >
                    <i class="fas fa-arrow-left me-1"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="facility-btn facility-btn-save"
                >
                    <i class="fas fa-save me-1"></i>
                    Create Facility
                </button>

            </div>

        </form>

    </div>

</div>

@endsection