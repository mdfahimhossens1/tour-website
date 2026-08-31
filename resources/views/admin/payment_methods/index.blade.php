@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('page')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

    :root {
        --p-surface: #1a1d27;
        --p-surface2: #222636;
        --p-border: rgba(255,255,255,.07);
        --p-accent: #0ea5e9;
        --p-accent2: #38bdf8;
        --p-green: #10b981;
        --p-success: #22c55e;
        --p-danger: #ef4444;
        --p-warning: #f59e0b;
        --p-purple: #8b5cf6;
        --p-text: #e2e8f0;
        --p-muted: #64748b;
        --p-radius: 14px;
        --p-radius-sm: 8px;
        --p-shadow: 0 8px 32px rgba(0,0,0,.45);
    }

    .pm-wrap {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--p-text);
    }

    /* HEADER */
    .pm-header {
        background: linear-gradient(135deg,#0c1a2e 0%,#0c3558 50%,#083344 100%);
        border-radius: var(--p-radius);
        padding: 28px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--p-shadow);
    }

    .pm-header::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: .5;
        background-image:
            linear-gradient(rgba(14,165,233,.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(14,165,233,.04) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    .pm-header::after {
        content: '';
        position: absolute;
        right: -40px;
        top: -40px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(
            circle,
            rgba(14,165,233,.18) 0%,
            transparent 70%
        );
    }

    .pm-header .title {
        font-size: 1.5rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
        background: linear-gradient(90deg,#fff,var(--p-accent2));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .pm-header .subtitle {
        color: rgba(255,255,255,.45);
        font-size: .85rem;
        margin-top: 4px;
        position: relative;
        z-index: 1;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 40px;
        padding: 6px 16px;
        font-size: .8rem;
        font-weight: 600;
        color: #fff;
        position: relative;
        z-index: 1;
    }

    .stat-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    /* GRID */
    .pm-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill,minmax(300px,1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    /* CARD */
    .pm-method-card {
        background: var(--p-surface);
        border: 1px solid var(--p-border);
        border-radius: var(--p-radius);
        padding: 20px;
        box-shadow: var(--p-shadow);
        transition: transform .2s,border-color .2s;
        position: relative;
        overflow: hidden;
    }

    .pm-method-card:hover {
        transform: translateY(-2px);
        border-color: rgba(255,255,255,.12);
    }

    .pm-method-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        border-radius: 3px 0 0 3px;
    }

    .pm-method-card.active-card::before {
        background: var(--p-green);
    }

    .pm-method-card.inactive-card::before {
        background: var(--p-danger);
    }

    .pm-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .pm-card-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pm-card-name {
        font-weight: 700;
        font-size: .95rem;
    }

    .pm-card-type {
        font-size: .72rem;
        color: var(--p-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-top: 2px;
    }

    .pm-card-actions {
        display: flex;
        gap: 6px;
    }

    /* TYPE ICON */
    .pm-type-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .pm-type-bkash {
        background: rgba(244,63,142,.12);
        color: #f9a8d4;
    }

    .pm-type-nagad {
        background: rgba(249,115,22,.12);
        color: #fdba74;
    }

    .pm-type-bank {
        background: rgba(16,185,129,.12);
        color: #6ee7b7;
    }

    .pm-type-manual {
        background: rgba(139,92,246,.12);
        color: #c4b5fd;
    }

    .pm-type-default {
        background: rgba(100,116,139,.1);
        color: #94a3b8;
    }

    /* ACCOUNT */
    .pm-account {
        font-family: 'JetBrains Mono', monospace;
        font-size: .8rem;
        background: var(--p-surface2);
        border: 1px solid var(--p-border);
        padding: 8px 12px;
        border-radius: var(--p-radius-sm);
        color: var(--p-accent2);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pm-account-label {
        color: var(--p-muted);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .68rem;
        margin-bottom: 2px;
    }

    .pm-account-copy {
        background: none;
        border: none;
        color: var(--p-muted);
        cursor: pointer;
        font-size: .7rem;
        transition: color .2s;
    }

    .pm-account-copy:hover {
        color: var(--p-accent);
    }

    .pm-no-account {
        background: rgba(100,116,139,.06);
        border: 1px dashed var(--p-border);
        padding: 8px 12px;
        border-radius: var(--p-radius-sm);
        color: var(--p-muted);
        font-size: .75rem;
        margin-bottom: 12px;
    }

    /* DESCRIPTION */
    .pm-desc {
        font-size: .78rem;
        color: var(--p-muted);
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .pm-footer-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    /* BADGES */
    .pm-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
    }

    .pm-badge-active {
        background: rgba(34,197,94,.12);
        color: #86efac;
        border: 1px solid rgba(34,197,94,.25);
    }

    .pm-badge-inactive {
        background: rgba(239,68,68,.12);
        color: #fca5a5;
        border: 1px solid rgba(239,68,68,.25);
    }

    /* BUTTONS */
    .pm-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        font-family: inherit;
        font-weight: 600;
        border-radius: var(--p-radius-sm);
        transition: all .2s;
        text-decoration: none;
    }

    .pm-btn-primary {
        background: var(--p-accent);
        color: #0c1a2e;
        padding: 9px 18px;
        font-size: .85rem;
    }

    .pm-btn-primary:hover {
        background: var(--p-accent2);
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(14,165,233,.35);
        color: #0c1a2e;
    }

    .pm-btn-outline {
        background: transparent;
        color: var(--p-text);
        border: 1px solid var(--p-border);
        padding: 9px 14px;
        font-size: .82rem;
    }

    .pm-btn-outline:hover {
        background: var(--p-surface2);
        color: var(--p-text);
    }

    .pm-btn-icon {
        background: var(--p-surface2);
        color: var(--p-muted);
        border: 1px solid var(--p-border);
        padding: 6px 10px;
        font-size: .78rem;
        border-radius: 6px;
    }

    .pm-btn-icon:hover {
        color: var(--p-warning);
        border-color: rgba(245,158,11,.3);
    }

    .pm-btn-danger-ghost {
        background: rgba(239,68,68,.1);
        color: #fca5a5;
        border: 1px solid rgba(239,68,68,.2);
        padding: 6px 10px;
        font-size: .78rem;
        border-radius: 6px;
    }

    .pm-btn-danger-ghost:hover {
        background: rgba(239,68,68,.2);
    }

    /* MODAL */
    .pm-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,.72);
        backdrop-filter: blur(6px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s;
        padding: 20px;
    }

    .pm-modal-overlay.open {
        opacity: 1;
        pointer-events: auto;
    }

    .pm-modal {
        background: var(--p-surface);
        border: 1px solid var(--p-border);
        border-radius: 18px;
        width: min(600px,96vw);
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 24px 64px rgba(0,0,0,.6);
        transform: translateY(24px) scale(.97);
        transition: transform .3s;
    }

    .pm-modal-overlay.open .pm-modal {
        transform: translateY(0) scale(1);
    }

    .pm-modal-header {
        padding: 22px 28px 18px;
        border-bottom: 1px solid var(--p-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pm-modal-title {
        font-size: 1.1rem;
        font-weight: 700;
    }

    .pm-modal-close {
        background: var(--p-surface2);
        border: 1px solid var(--p-border);
        color: var(--p-muted);
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pm-modal-close:hover {
        color: var(--p-text);
    }

    .pm-modal-body {
        padding: 24px 28px;
    }

    .pm-modal-footer {
        padding: 18px 28px;
        border-top: 1px solid var(--p-border);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    /* FORM */
    .pm-field {
        margin-bottom: 18px;
    }

    .pm-field label {
        display: block;
        font-size: .8rem;
        font-weight: 600;
        color: var(--p-muted);
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .pm-field input,
    .pm-field select,
    .pm-field textarea {
        width: 100%;
        background: var(--p-surface2);
        border: 1px solid var(--p-border);
        border-radius: var(--p-radius-sm);
        padding: 10px 14px;
        color: var(--p-text);
        font-family: inherit;
        font-size: .875rem;
        outline: none;
        resize: vertical;
        transition: border-color .2s,box-shadow .2s;
        box-sizing: border-box;
    }

    .pm-field input:focus,
    .pm-field select:focus,
    .pm-field textarea:focus {
        border-color: var(--p-accent);
        box-shadow: 0 0 0 3px rgba(14,165,233,.12);
    }

    .pm-field select option {
        background: var(--p-surface2);
        color: var(--p-text);
    }

    .pm-field .mono {
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: .04em;
    }

    .pm-field .err {
        color: #fca5a5;
        font-size: .78rem;
        margin-top: 5px;
    }

    .pm-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 18px;
    }

    .pm-section {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--p-accent2);
        margin: 4px 0 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pm-section::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--p-border);
    }

    .pm-type-chips {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .pm-type-chip {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid var(--p-border);
        background: var(--p-surface2);
        color: var(--p-muted);
        transition: all .2s;
    }

    .pm-type-chip:hover {
        color: var(--p-text);
        border-color: rgba(255,255,255,.15);
    }

    .pm-type-chip.sel-bkash {
        background: rgba(244,63,142,.15);
        color: #f9a8d4;
        border-color: rgba(244,63,142,.3);
    }

    .pm-type-chip.sel-nagad {
        background: rgba(249,115,22,.15);
        color: #fdba74;
        border-color: rgba(249,115,22,.3);
    }

    .pm-type-chip.sel-bank {
        background: rgba(16,185,129,.15);
        color: #6ee7b7;
        border-color: rgba(16,185,129,.3);
    }

    .pm-type-chip.sel-manual {
        background: rgba(139,92,246,.15);
        color: #c4b5fd;
        border-color: rgba(139,92,246,.3);
    }

    /* EMPTY */
    .pm-empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--p-muted);
    }

    .pm-empty i {
        font-size: 2.5rem;
        margin-bottom: 14px;
        opacity: .4;
        display: block;
    }

    /* DELETE */
    .pm-del-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(239,68,68,.12);
        border: 2px solid rgba(239,68,68,.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fca5a5;
        margin: 0 auto 18px;
    }

    /* TOAST */
    #pm-toast-container {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pm-toast {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--p-surface);
        border: 1px solid var(--p-border);
        border-radius: 12px;
        padding: 14px 18px;
        min-width: 280px;
        box-shadow: 0 8px 30px rgba(0,0,0,.5);
        transform: translateX(120%);
        transition: transform .35s;
        font-family: 'Plus Jakarta Sans',sans-serif;
        position: relative;
        overflow: hidden;
    }

    .pm-toast.show {
        transform: translateX(0);
    }

    .pm-toast-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pm-toast-success .pm-toast-icon {
        background: rgba(34,197,94,.15);
        color: var(--p-success);
    }

    .pm-toast-danger .pm-toast-icon {
        background: rgba(239,68,68,.15);
        color: var(--p-danger);
    }

    .pm-toast-info .pm-toast-icon {
        background: rgba(56,189,248,.15);
        color: var(--p-accent);
    }

    .pm-toast-title {
        font-size: .875rem;
        font-weight: 700;
        color: var(--p-text);
    }

    .pm-toast-msg {
        font-size: .78rem;
        color: var(--p-muted);
        margin-top: 2px;
    }

    @media(max-width: 650px) {
        .pm-grid-2 {
            grid-template-columns: 1fr;
        }

        .pm-header {
            padding: 22px;
        }

        .pm-modal-body,
        .pm-modal-header,
        .pm-modal-footer {
            padding-left: 20px;
            padding-right: 20px;
        }

        #pm-toast-container {
            right: 15px;
            left: 15px;
        }

        .pm-toast {
            min-width: 0;
        }
    }
</style>


@if(session('success'))
    <div id="flash-success" data-msg="{{ session('success') }}"></div>
@endif

@if(session('error'))
    <div id="flash-error" data-msg="{{ session('error') }}"></div>
@endif


<div class="pm-wrap">

    {{-- HEADER --}}
    <div class="pm-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>

                <div class="title">
                    <i class="fas fa-credit-card me-2"></i>
                    Payment Methods
                </div>

                <div class="subtitle">
                    Manage all active and inactive payment methods
                </div>

                <div class="d-flex gap-2 mt-3 flex-wrap">

                    <span class="stat-pill">
                        <span class="dot" style="background:#22c55e"></span>
                        {{ $methods->where('status', 1)->count() }} Active
                    </span>

                    <span class="stat-pill">
                        <span class="dot" style="background:#ef4444"></span>
                        {{ $methods->where('status', 0)->count() }} Inactive
                    </span>

                    <span class="stat-pill">
                        <span class="dot" style="background:#38bdf8"></span>
                        {{ $methods->count() }} Total
                    </span>

                </div>

            </div>

            <div style="position:relative;z-index:1;">

                <button
                    type="button"
                    class="pm-btn pm-btn-primary"
                    onclick="openAddModal()"
                >
                    <i class="fas fa-plus"></i>
                    Add Method
                </button>

            </div>

        </div>

    </div>


    {{-- PAYMENT METHOD CARDS --}}
    @if($methods->count() > 0)

        <div class="pm-grid">

            @foreach($methods as $method)

                @php

                    $type = strtolower($method->type ?? 'manual');

                    if ($type === 'bkash') {
                        $typeClass = 'pm-type-bkash';
                        $typeIcon = 'fas fa-mobile-alt';
                    } elseif ($type === 'nagad') {
                        $typeClass = 'pm-type-nagad';
                        $typeIcon = 'fas fa-mobile-alt';
                    } elseif ($type === 'bank') {
                        $typeClass = 'pm-type-bank';
                        $typeIcon = 'fas fa-university';
                    } elseif ($type === 'manual') {
                        $typeClass = 'pm-type-manual';
                        $typeIcon = 'fas fa-hand-holding-usd';
                    } else {
                        $typeClass = 'pm-type-default';
                        $typeIcon = 'fas fa-credit-card';
                    }

                    $methodData = [
                        'id' => $method->id,
                        'name' => $method->name,
                        'type' => $method->type,
                        'account_number' => $method->account_number,
                        'api_key' => $method->api_key,
                        'secret_key' => $method->secret_key,
                        'description' => $method->description,
                        'status' => (int) $method->status,
                    ];

                @endphp


                <div class="pm-method-card {{ $method->status ? 'active-card' : 'inactive-card' }}">

                    <div class="pm-card-top">

                        <div class="pm-card-left">

                            <div class="pm-type-icon {{ $typeClass }}">
                                <i class="{{ $typeIcon }}"></i>
                            </div>

                            <div>

                                <div class="pm-card-name">
                                    {{ $method->name }}
                                </div>

                                <div class="pm-card-type">
                                    {{ ucfirst($method->type ?? 'manual') }}
                                </div>

                            </div>

                        </div>


                        <div class="pm-card-actions">

                            {{-- EDIT --}}
                            <button
                                type="button"
                                class="pm-btn pm-btn-icon"
                                title="Edit"
                                onclick='openEditModal(@json($methodData))'
                            >
                                <i class="fas fa-pen"></i>
                            </button>


                            {{-- DEACTIVATE --}}
                            @if($method->status)

                                <button
                                    type="button"
                                    class="pm-btn pm-btn-danger-ghost"
                                    title="Deactivate"
                                    onclick='openDeleteModal({{ $method->id }}, @json($method->name))'
                                >
                                    <i class="fas fa-ban"></i>
                                </button>

                            @endif

                        </div>

                    </div>


                    {{-- ACCOUNT NUMBER --}}
                    @if($method->account_number)

                        <div class="pm-account">

                            <div>
                                <div class="pm-account-label">
                                    Account / Number
                                </div>

                                <span>
                                    {{ $method->account_number }}
                                </span>
                            </div>

                            <button
                                type="button"
                                class="pm-account-copy"
                                onclick='copyText(@json($method->account_number))'
                                title="Copy"
                            >
                                <i class="fas fa-copy"></i>
                            </button>

                        </div>

                    @else

                        <div class="pm-no-account">
                            <i class="fas fa-info-circle me-1"></i>
                            No account number required
                        </div>

                    @endif


                    {{-- DESCRIPTION --}}
                    @if($method->description)

                        <div class="pm-desc">
                            {{ \Illuminate\Support\Str::limit($method->description, 100) }}
                        </div>

                    @endif


                    {{-- FOOTER --}}
                    <div class="pm-footer-row">

                        <span class="pm-badge {{ $method->status ? 'pm-badge-active' : 'pm-badge-inactive' }}">

                            <i
                                class="fas fa-circle"
                                style="font-size:.4rem;"
                            ></i>

                            {{ $method->status ? 'Active' : 'Inactive' }}

                        </span>


                        @if($method->api_key)

                            <span
                                style="font-size:.72rem;color:var(--p-muted);"
                            >
                                <i
                                    class="fas fa-key"
                                    style="color:var(--p-warning);"
                                ></i>

                                API Key set
                            </span>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div
            class="pm-empty"
            style="
                background:var(--p-surface);
                border:1px solid var(--p-border);
                border-radius:var(--p-radius);
                box-shadow:var(--p-shadow);
            "
        >

            <i class="fas fa-credit-card"></i>

            <p>
                No payment methods added yet.
            </p>

            <button
                type="button"
                class="pm-btn pm-btn-primary"
                onclick="openAddModal()"
            >
                <i class="fas fa-plus"></i>
                Add Payment Method
            </button>

        </div>

    @endif

</div>


{{-- =========================================================
     ADD PAYMENT METHOD MODAL
========================================================= --}}

<div class="pm-modal-overlay" id="addModal">

    <div class="pm-modal">

        <div class="pm-modal-header">

            <div class="pm-modal-title">

                <i
                    class="fas fa-plus-circle me-2"
                    style="color:var(--p-accent2)"
                ></i>

                Add Payment Method

            </div>

            <button
                type="button"
                class="pm-modal-close"
                onclick="closeModal('addModal')"
            >
                <i class="fas fa-times"></i>
            </button>

        </div>


        <form
            method="POST"
            action="{{ route('admin.payment_methods.store') }}"
        >

            @csrf

            <div class="pm-modal-body">

                <div class="pm-section">
                    <i class="fas fa-info-circle"></i>
                    Basic Information
                </div>


                <div class="pm-grid-2">

                    {{-- NAME --}}
                    <div class="pm-field">

                        <label>
                            Method Name
                            <span style="color:var(--p-danger)">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. bKash"
                            required
                        >

                        @error('name')
                            <div class="err">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- TYPE --}}
                    <div class="pm-field">

                        <label>
                            Type
                            <span style="color:var(--p-danger)">*</span>
                        </label>

                        <input
                            type="text"
                            name="type"
                            id="add_type"
                            value="{{ old('type') }}"
                            placeholder="bkash / nagad / bank / manual"
                            required
                        >

                        <div class="pm-type-chips">

                            <span
                                class="pm-type-chip"
                                onclick="setType('add','bkash')"
                            >
                                bKash
                            </span>

                            <span
                                class="pm-type-chip"
                                onclick="setType('add','nagad')"
                            >
                                Nagad
                            </span>

                            <span
                                class="pm-type-chip"
                                onclick="setType('add','bank')"
                            >
                                Bank
                            </span>

                            <span
                                class="pm-type-chip"
                                onclick="setType('add','manual')"
                            >
                                Manual
                            </span>

                        </div>

                        @error('type')
                            <div class="err">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                <div class="pm-grid-2">

                    {{-- ACCOUNT --}}
                    <div class="pm-field">

                        <label>
                            Account / Number
                        </label>

                        <input
                            type="text"
                            name="account_number"
                            value="{{ old('account_number') }}"
                            placeholder="01XXXXXXXXX / Account Number"
                            class="mono"
                        >

                    </div>


                    {{-- STATUS --}}
                    <div class="pm-field">

                        <label>
                            Status
                        </label>

                        <select name="status">

                            <option
                                value="1"
                                {{ old('status', 1) == 1 ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ old('status') === '0' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                {{-- API --}}
                <div class="pm-section">

                    <i class="fas fa-key"></i>

                    API Credentials

                    <span
                        style="
                            font-size:.65rem;
                            color:var(--p-muted);
                            font-weight:400;
                            text-transform:none;
                            letter-spacing:0;
                        "
                    >
                        (optional)
                    </span>

                </div>


                <div class="pm-grid-2">

                    <div class="pm-field">

                        <label>API Key</label>

                        <input
                            type="text"
                            name="api_key"
                            value="{{ old('api_key') }}"
                            placeholder="Optional"
                        >

                    </div>


                    <div class="pm-field">

                        <label>Secret Key</label>

                        <input
                            type="text"
                            name="secret_key"
                            value="{{ old('secret_key') }}"
                            placeholder="Optional"
                        >

                    </div>

                </div>


                {{-- DESCRIPTION --}}
                <div class="pm-field">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Payment instructions or short note..."
                    >{{ old('description') }}</textarea>

                </div>

            </div>


            <div class="pm-modal-footer">

                <button
                    type="button"
                    class="pm-btn pm-btn-outline"
                    onclick="closeModal('addModal')"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="pm-btn pm-btn-primary"
                >
                    <i class="fas fa-save"></i>
                    Save Method
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     EDIT PAYMENT METHOD MODAL
========================================================= --}}

<div class="pm-modal-overlay" id="editModal">

    <div class="pm-modal">

        <div class="pm-modal-header">

            <div class="pm-modal-title">

                <i
                    class="fas fa-pen me-2"
                    style="color:var(--p-warning)"
                ></i>

                Edit Payment Method

            </div>

            <button
                type="button"
                class="pm-modal-close"
                onclick="closeModal('editModal')"
            >
                <i class="fas fa-times"></i>
            </button>

        </div>


<form
    method="POST"
    id="editForm"
    action=""
>
    @csrf
    @method('PUT')

    <div class="pm-modal-body">

        <div class="pm-section">
            <i class="fas fa-info-circle"></i>
            Basic Information
        </div>

        <div class="pm-grid-2">

            {{-- NAME --}}
            <div class="pm-field">

                <label>
                    Method Name
                    <span style="color:var(--p-danger)">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    id="edit_name"
                    required
                >

                <div class="err" id="edit-name-error"></div>

            </div>


            {{-- TYPE --}}
            <div class="pm-field">

                <label>
                    Type
                    <span style="color:var(--p-danger)">*</span>
                </label>

                <input
                    type="text"
                    name="type"
                    id="edit_type"
                    required
                >

                <div class="pm-type-chips">

                    <span
                        class="pm-type-chip"
                        onclick="setType('edit','bkash')"
                    >
                        bKash
                    </span>

                    <span
                        class="pm-type-chip"
                        onclick="setType('edit','nagad')"
                    >
                        Nagad
                    </span>

                    <span
                        class="pm-type-chip"
                        onclick="setType('edit','bank')"
                    >
                        Bank
                    </span>

                    <span
                        class="pm-type-chip"
                        onclick="setType('edit','manual')"
                    >
                        Manual
                    </span>

                </div>

            </div>

        </div>


        <div class="pm-grid-2">

            {{-- ACCOUNT --}}
            <div class="pm-field">

                <label>Account / Number</label>

                <input
                    type="text"
                    name="account_number"
                    id="edit_account_number"
                    class="mono"
                    placeholder="Optional"
                >

            </div>


            {{-- STATUS --}}
            <div class="pm-field">

                <label>Status</label>

                <select
                    name="status"
                    id="edit_status"
                >
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>

            </div>

        </div>


        <div class="pm-section">

            <i class="fas fa-key"></i>

            API Credentials

            <span
                style="
                    font-size:.65rem;
                    color:var(--p-muted);
                    font-weight:400;
                    text-transform:none;
                    letter-spacing:0;
                "
            >
                (optional)
            </span>

        </div>


        <div class="pm-grid-2">

            <div class="pm-field">

                <label>API Key</label>

                <input
                    type="text"
                    name="api_key"
                    id="edit_api_key"
                    placeholder="Leave blank to keep existing"
                >

            </div>


            <div class="pm-field">

                <label>Secret Key</label>

                <input
                    type="text"
                    name="secret_key"
                    id="edit_secret_key"
                    placeholder="Leave blank to keep existing"
                >

            </div>

        </div>


        <div class="pm-field">

            <label>Description</label>

            <textarea
                name="description"
                id="edit_description"
                rows="3"
                placeholder="Payment instructions..."
            ></textarea>

        </div>

    </div>


    <div class="pm-modal-footer">

        <button
            type="button"
            class="pm-btn pm-btn-outline"
            onclick="closeModal('editModal')"
        >
            Cancel
        </button>

        <button
            type="submit"
            class="pm-btn pm-btn-primary"
            style="background:var(--p-warning);color:#1a1d27;"
        >
            <i class="fas fa-save"></i>
            Update Method
        </button>

    </div>

</form>

    </div>

</div>


{{-- =========================================================
     DEACTIVATE MODAL
========================================================= --}}

<div class="pm-modal-overlay" id="deleteModal">

    <div
        class="pm-modal"
        style="width:min(420px,96vw);"
    >

        <div
            class="pm-modal-body"
            style="
                text-align:center;
                padding:40px 28px 28px;
            "
        >

            <div class="pm-del-icon">
                <i class="fas fa-ban"></i>
            </div>

            <h5 style="font-weight:700;margin-bottom:8px;">
                Deactivate Payment Method?
            </h5>

            <p
                style="
                    color:var(--p-muted);
                    font-size:.88rem;
                    margin-bottom:4px;
                "
            >
                <strong
                    id="del-method-name"
                    style="color:var(--p-text);"
                ></strong>

                will be deactivated.
            </p>

            <p
                style="
                    color:var(--p-muted);
                    font-size:.8rem;
                "
            >
                You can keep the record and reactivate it later.
            </p>

        </div>


        <div
            class="pm-modal-footer"
            style="justify-content:center;gap:14px;"
        >

            <button
                type="button"
                class="pm-btn pm-btn-outline"
                onclick="closeModal('deleteModal')"
            >
                <i class="fas fa-times"></i>
                Cancel
            </button>


<form
    method="POST"
    id="deleteForm"
    action=""
    style="margin:0;"
>
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="pm-btn"
        style="
            background:var(--p-danger);
            color:#fff;
            padding:9px 18px;
            font-size:.875rem;
        "
    >
        <i class="fas fa-ban"></i>
        Yes, Deactivate
    </button>
</form>

        </div>

    </div>

</div>


{{-- TOAST --}}
<div id="pm-toast-container"></div>


<script>
(function () {

    /* =====================================================
       MODAL
    ===================================================== */

    window.openModal = function (id) {

        var modal = document.getElementById(id);

        if (!modal) {
            return;
        }

        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    };


    window.closeModal = function (id) {

        var modal = document.getElementById(id);

        if (!modal) {
            return;
        }

        modal.classList.remove('open');
        document.body.style.overflow = '';
    };


    document.querySelectorAll('.pm-modal-overlay').forEach(function (overlay) {

        overlay.addEventListener('click', function (event) {

            if (event.target === overlay) {
                closeModal(overlay.id);
            }

        });

    });


    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            document
                .querySelectorAll('.pm-modal-overlay.open')
                .forEach(function (modal) {

                    closeModal(modal.id);

                });

        }

    });


    /* =====================================================
       ADD MODAL
    ===================================================== */

    window.openAddModal = function () {

        openModal('addModal');

    };


    /* =====================================================
       TYPE SELECTOR
    ===================================================== */

    window.setType = function (prefix, type) {

        var input = document.getElementById(prefix + '_type');

        if (!input) {
            return;
        }

        input.value = type;


        var modalId = prefix === 'add'
            ? 'addModal'
            : 'editModal';


        document
            .querySelectorAll('#' + modalId + ' .pm-type-chip')
            .forEach(function (chip) {

                chip.classList.remove(
                    'sel-bkash',
                    'sel-nagad',
                    'sel-bank',
                    'sel-manual'
                );


                var chipType = chip.textContent
                    .trim()
                    .toLowerCase();


                if (chipType === type.toLowerCase()) {

                    chip.classList.add(
                        'sel-' + type.toLowerCase()
                    );

                }

            });

    };


    /* =====================================================
       EDIT MODAL
    ===================================================== */

    window.openEditModal = function (data) {

        if (!data || !data.id) {

            showToast(
                'danger',
                'Error',
                'Payment method information could not be loaded.'
            );

            return;
        }


        /* Fill form fields */

        document.getElementById('edit_name').value =
            data.name || '';

        document.getElementById('edit_type').value =
            data.type || '';

        document.getElementById('edit_account_number').value =
            data.account_number || '';

        /*
         * API Key এবং Secret Key খালি রাখছি।
         * Backend-এ blank হলে আগের value থাকবে।
         */

        document.getElementById('edit_api_key').value = '';

        document.getElementById('edit_secret_key').value = '';

        document.getElementById('edit_description').value =
            data.description || '';

        document.getElementById('edit_status').value =
            String(data.status) === '1' ? '1' : '0';


        /*
         * তোমার Update Route:
         *
         * /admin/payment-methods/{id}/update
         *
         * এখানে সরাসরি ID বসানো হচ্ছে।
         */

var baseUrl = @json(url('/admin/payment-methods'));

document.getElementById('editForm').action =
    baseUrl + '/' + data.id;


        /* Highlight selected type */

        var selectedType = String(
            data.type || ''
        ).toLowerCase();


        document
            .querySelectorAll('#editModal .pm-type-chip')
            .forEach(function (chip) {

                chip.classList.remove(
                    'sel-bkash',
                    'sel-nagad',
                    'sel-bank',
                    'sel-manual'
                );


                var chipText = chip.textContent
                    .trim()
                    .toLowerCase();


                if (chipText === selectedType) {

                    chip.classList.add(
                        'sel-' + selectedType
                    );

                }

            });


        openModal('editModal');

    };


    /* =====================================================
       DEACTIVATE / DELETE MODAL
    ===================================================== */

/* =====================================================
   DEACTIVATE MODAL - FIXED
===================================================== */

window.openDeleteModal = function (id, name) {

    // নিরাপত্তার জন্য ID আছে কিনা check করছি
    if (!id) {
        showToast(
            'danger',
            'Error',
            'Payment method ID পাওয়া যায়নি।'
        );
        return;
    }

    // Modal-এ Payment Method-এর নাম দেখানো
    document.getElementById('del-method-name').textContent =
        name || 'Payment Method';


    /*
     * আমরা Laravel-এর DELETE route ব্যবহার করছি:
     *
     * DELETE /admin/payment-methods/{id}
     *
     * তাই শুধু base URL + ID লাগবে।
     */

    var baseUrl = @json(url('/admin/payment-methods'));

    document.getElementById('deleteForm').action =
        baseUrl + '/' + id;


    // Delete confirmation modal open
    openModal('deleteModal');
};



    /* =====================================================
       COPY ACCOUNT NUMBER
    ===================================================== */

    window.copyText = function (text) {

        if (!navigator.clipboard) {

            showToast(
                'danger',
                'Error',
                'Clipboard is not supported by this browser.'
            );

            return;
        }


        navigator.clipboard
            .writeText(text)
            .then(function () {

                showToast(
                    'info',
                    'Copied!',
                    text + ' copied to clipboard.'
                );

            })
            .catch(function () {

                showToast(
                    'danger',
                    'Error',
                    'Could not copy the number.'
                );

            });

    };


    /* =====================================================
       TOAST
    ===================================================== */

    window.showToast = function (type, title, message) {

        var container =
            document.getElementById('pm-toast-container');


        if (!container) {
            return;
        }


        var toast =
            document.createElement('div');


        toast.className =
            'pm-toast pm-toast-' + type;


        var icon =
            'fas fa-info-circle';


        if (type === 'success') {
            icon = 'fas fa-check-circle';
        }

        if (type === 'danger') {
            icon = 'fas fa-exclamation-circle';
        }

        if (type === 'info') {
            icon = 'fas fa-copy';
        }


        toast.innerHTML =
            '<div class="pm-toast-icon">' +
                '<i class="' + icon + '"></i>' +
            '</div>' +

            '<div>' +
                '<div class="pm-toast-title">' +
                    escapeHtml(title) +
                '</div>' +

                '<div class="pm-toast-msg">' +
                    escapeHtml(message) +
                '</div>' +
            '</div>';


        container.appendChild(toast);


        setTimeout(function () {

            toast.classList.add('show');

        }, 20);


        setTimeout(function () {

            toast.classList.remove('show');

            setTimeout(function () {

                toast.remove();

            }, 400);

        }, 3500);

    };


    /* =====================================================
       ESCAPE HTML
    ===================================================== */

    function escapeHtml(value) {

        var div =
            document.createElement('div');

        div.textContent =
            value == null
                ? ''
                : String(value);

        return div.innerHTML;

    }


    /* =====================================================
       FLASH MESSAGE
    ===================================================== */

    var success =
        document.getElementById('flash-success');


    var error =
        document.getElementById('flash-error');


    if (success) {

        showToast(
            'success',
            'Success',
            success.dataset.msg
        );

    }


    if (error) {

        showToast(
            'danger',
            'Error',
            error.dataset.msg
        );

    }


    /* =====================================================
       VALIDATION ERROR
    ===================================================== */

    @if($errors->any())

        @if(old('_method') !== 'PUT')

            openAddModal();

        @endif

    @endif


})();
</script>

@endsection