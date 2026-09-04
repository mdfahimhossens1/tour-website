@extends('layouts.vendor')

@section('title', 'Payment Methods')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --rs-surface: #1a1d27; --rs-surface2: #222636; --rs-surface3: #2a2f42; --rs-border: rgba(255,255,255,.07);
    --rs-text: #e2e8f0; --rs-muted: #64748b;
    --rs-indigo: #6366f1; --rs-purple: #8b5cf6;
    --rs-success: #22c55e; --rs-danger: #ef4444;
    --rs-radius: 14px; --rs-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.rs-wrap { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--rs-text); }

.rs-header {
    background: linear-gradient(135deg, #0c1a2e 0%, #0e0c2e 55%, #0c1a2e 100%);
    border-radius: var(--rs-radius); padding: 28px 30px; margin-bottom: 22px;
    box-shadow: var(--rs-shadow); position: relative; overflow: hidden;
}
.rs-header::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.rs-header-content { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.rs-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(90deg, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

.rs-btn-primary { display: inline-flex; align-items: center; gap: 8px; border: none; background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple)); color: #fff; border-radius: 10px; padding: 11px 18px; font-size: .82rem; font-weight: 600; box-shadow: 0 8px 22px rgba(99,102,241,.28); }
.rs-btn-primary:hover { color: #fff; }

.rs-wrap .alert { background: var(--rs-surface); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 12px; font-size: .84rem; box-shadow: var(--rs-shadow); }
.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger { border-left: 3px solid var(--rs-danger); }
.rs-wrap .btn-close { filter: invert(1) grayscale(1) opacity(.6); }
.rs-wrap .alert ul { padding-left: 18px; margin: 6px 0 0; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; }

.rs-table { width: 100%; min-width: 950px; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 15px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-method-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(99,102,241,.12); color: #a5b4fc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1rem; }
.rs-method-name { font-weight: 600; font-size: .82rem; }
.rs-method-type { color: var(--rs-muted); font-size: .7rem; margin-top: 2px; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-transport { background: rgba(14,165,233,.1); color: #7dd3fc; border: 1px solid rgba(14,165,233,.18); }
.rs-badge-room      { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-both      { background: rgba(139,92,246,.1); color: #c4b5fd; border: 1px solid rgba(139,92,246,.18); }
.rs-badge-success   { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-muted     { background: rgba(255,255,255,.04); color: var(--rs-muted); border: 1px solid var(--rs-border); }

.rs-number { font-family: 'JetBrains Mono', monospace; font-size: .78rem; color: #cbd5e1; }

.rs-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid var(--rs-border); background: rgba(255,255,255,.02); color: var(--rs-muted); transition: all .2s; }
.rs-action-btn:hover { background: rgba(99,102,241,.14); color: #c7d2fe; border-color: rgba(99,102,241,.3); }
.rs-action-btn.danger:hover { background: rgba(239,68,68,.14); color: #fca5a5; border-color: rgba(239,68,68,.3); }

.rs-empty { text-align: center; padding: 70px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }


/* MODAL */

.rs-wrap .modal-content { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: 16px; color: var(--rs-text); box-shadow: 0 24px 60px rgba(0,0,0,.55); }
.rs-wrap .modal-header { border-bottom: 1px solid var(--rs-border); padding: 20px 24px; }
.rs-wrap .modal-header h5 { font-size: 1.05rem; font-weight: 700; }
.rs-wrap .modal-body { padding: 24px; }
.rs-wrap .modal-footer { border-top: 1px solid var(--rs-border); padding: 16px 24px; }
.rs-wrap .modal-body h6 { font-size: .84rem; font-weight: 700; color: #a5b4fc; }

.rs-field { margin-bottom: 16px; }
.rs-label { display: block; font-size: .78rem; font-weight: 600; color: #cbd5e1; margin-bottom: 7px; }
.rs-label small { color: var(--rs-muted); font-weight: 400; }

.rs-input, .rs-select, .rs-textarea {
    width: 100%; background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text);
    border-radius: 9px; padding: 10px 13px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .84rem;
    outline: none; transition: border-color .15s, box-shadow .15s;
}
.rs-textarea { resize: vertical; min-height: 80px; }
.rs-input::placeholder, .rs-textarea::placeholder { color: var(--rs-muted); }
.rs-input:focus, .rs-select:focus, .rs-textarea:focus { border-color: rgba(99,102,241,.5); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.rs-help { color: var(--rs-muted); font-size: .74rem; margin-top: 6px; display: flex; align-items: center; gap: 5px; }

.rs-btn-ghost { display: inline-flex; align-items: center; gap: 6px; border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.04); color: #e2e8f0; border-radius: 9px; padding: 9px 16px; font-size: .82rem; font-weight: 600; }
.rs-btn-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }

.rs-divider { border-top: 1px solid var(--rs-border); margin-top: 20px; padding-top: 20px; }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">

        <div class="rs-header-content">
            <div>
                <div class="rs-title"><i class="bi bi-credit-card me-2"></i> Payment Methods</div>
                <div class="rs-subtitle">Manage payment accounts for your transport and Room services.</div>
            </div>

            <button type="button" class="rs-btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                <i class="bi bi-plus-lg"></i> Add Payment Method
            </button>
        </div>

    </div>


    {{-- MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- TABLE --}}

    <div class="rs-card">

        <div class="table-responsive">

            <table class="rs-table">

                <thead>
                    <tr>
                        <th class="ps-4">Payment Method</th>
                        <th>Used For</th>
                        <th>Account / Number</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($methods as $method)

                        <tr>

                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rs-method-icon">
                                        @switch($method->type)
                                            @case('bkash') <i class="bi bi-phone"></i> @break
                                            @case('nagad') <i class="bi bi-wallet2"></i> @break
                                            @case('bank') <i class="bi bi-bank"></i> @break
                                            @case('paypal') <i class="bi bi-paypal"></i> @break
                                            @case('stripe') <i class="bi bi-credit-card-2-front"></i> @break
                                            @default <i class="bi bi-cash-stack"></i>
                                        @endswitch
                                    </div>
                                    <div>
                                        <div class="rs-method-name">{{ $method->name }}</div>
                                        <div class="rs-method-type">{{ $method->type_label }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($method->service_type === 'transport')
                                    <span class="rs-badge rs-badge-transport"><i class="bi bi-car-front"></i> Transport</span>
                                @elseif($method->service_type === 'room')
                                    <span class="rs-badge rs-badge-room"><i class="bi bi-building"></i> Room</span>
                                @else
                                    <span class="rs-badge rs-badge-both"><i class="bi bi-layers"></i> Transport &amp; Resort</span>
                                @endif
                            </td>

                            <td><span class="rs-number">{{ $method->account_number ?: '—' }}</span></td>

                            <td style="color:var(--rs-muted);">{{ $method->description ? \Illuminate\Support\Str::limit($method->description, 50) : '—' }}</td>

                            <td>
                                @if($method->status)
                                    <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Active</span>
                                @else
                                    <span class="rs-badge rs-badge-muted"><i class="bi bi-x-circle"></i> Inactive</span>
                                @endif
                            </td>

                            <td class="text-end pe-4">

                                <button type="button" class="rs-action-btn me-1" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#editPaymentMethodModal{{ $method->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                @if($method->status)
                                    <form action="{{ route('vendor.payment-methods.destroy', $method->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to deactivate this payment method?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rs-action-btn danger" title="Deactivate">
                                            <i class="bi bi-slash-circle"></i>
                                        </button>
                                    </form>
                                @endif

                            </td>

                        </tr>


                        {{-- EDIT MODAL --}}

                        <div class="modal fade" id="editPaymentMethodModal{{ $method->id }}" tabindex="-1">

                            <div class="modal-dialog modal-lg modal-dialog-centered">

                                <div class="modal-content">

                                    <form action="{{ route('vendor.payment-methods.update', $method->id) }}" method="POST">

                                        @csrf @method('PUT')

                                        <div class="modal-header">
                                            <h5><i class="bi bi-pencil me-2"></i> Edit Payment Method</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <div class="row g-3">

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Method Name *</label>
                                                    <input type="text" name="name" class="rs-input" value="{{ $method->name }}" required>
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Payment Type *</label>
                                                    <select name="type" class="rs-select" required>
                                                        @foreach(['bkash'=>'bKash','nagad'=>'Nagad','bank'=>'Bank','stripe'=>'Stripe','paypal'=>'PayPal','manual'=>'Manual'] as $value => $label)
                                                            <option value="{{ $value }}" @selected($method->type === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Payment For *</label>
                                                    <select name="service_type" class="rs-select" required>
                                                        <option value="transport" @selected($method->service_type === 'transport')>Transport</option>
                                                        <option value="room" @selected($method->service_type === 'room')>Resort</option>
                                                        <option value="both" @selected($method->service_type === 'both')>Transport &amp; Resort</option>
                                                    </select>
                                                    <div class="rs-help"><i class="bi bi-info-circle"></i> Select where customers can use this payment method.</div>
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Account / Number</label>
                                                    <input type="text" name="account_number" class="rs-input" value="{{ $method->account_number }}">
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Status *</label>
                                                    <select name="status" class="rs-select" required>
                                                        <option value="1" @selected($method->status)>Active</option>
                                                        <option value="0" @selected(!$method->status)>Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">API Key <small>(Optional)</small></label>
                                                    <input type="text" name="api_key" class="rs-input" value="{{ $method->api_key }}">
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Secret Key <small>(Optional)</small></label>
                                                    <input type="text" name="secret_key" class="rs-input" value="{{ $method->secret_key }}">
                                                </div>

                                                <div class="col-12 rs-field">
                                                    <label class="rs-label">Description</label>
                                                    <textarea name="description" class="rs-textarea" rows="3">{{ $method->description }}</textarea>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="rs-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="rs-btn-primary"><i class="bi bi-save"></i> Update Payment Method</button>
                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="rs-empty">
                                    <i class="bi bi-credit-card"></i>
                                    <div class="rs-empty-title">No Payment Methods Yet</div>
                                    <div style="font-size:.78rem;">Add bKash, Nagad, Bank or other payment accounts for your services.</div>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ADD PAYMENT METHOD MODAL --}}

<div class="modal fade rs-wrap" id="addPaymentMethodModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form action="{{ route('vendor.payment-methods.store') }}" method="POST">

                @csrf

                <div class="modal-header">
                    <h5><i class="bi bi-plus-circle me-2"></i> Add Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <h6 class="mb-3">Basic Information</h6>

                    <div class="row g-3">

                        <div class="col-md-6 rs-field">
                            <label class="rs-label">Method Name *</label>
                            <input type="text" name="name" class="rs-input" placeholder="e.g. Personal bKash" required>
                        </div>

                        <div class="col-md-6 rs-field">
                            <label class="rs-label">Payment Type *</label>
                            <select name="type" class="rs-select" required>
                                <option value="">Select Payment Type</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="bank">Bank</option>
                                <option value="stripe">Stripe</option>
                                <option value="paypal">PayPal</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>

                        <div class="col-md-6 rs-field">
                            <label class="rs-label">Payment For *</label>
                            <select name="service_type" class="rs-select" required>
                                <option value="">Select Service</option>
                                <option value="transport">Transport</option>
                                <option value="room">Room</option>
                                <option value="both">Transport &amp; Room</option>
                            </select>
                            <div class="rs-help"><i class="bi bi-info-circle"></i> Choose whether this payment method is for Transport, Resort, or both.</div>
                        </div>

                        <div class="col-md-6 rs-field">
                            <label class="rs-label">Account / Number</label>
                            <input type="text" name="account_number" class="rs-input" placeholder="017XXXXXXXX">
                        </div>

                        <div class="col-md-6 rs-field">
                            <label class="rs-label">Status *</label>
                            <select name="status" class="rs-select" required>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="rs-divider">
                        <h6 class="mb-3">API Credentials <small style="color:var(--rs-muted);font-weight:400;">(Optional)</small></h6>

                        <div class="row g-3">
                            <div class="col-md-6 rs-field">
                                <label class="rs-label">API Key</label>
                                <input type="text" name="api_key" class="rs-input" placeholder="Optional API Key">
                            </div>
                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Secret Key</label>
                                <input type="text" name="secret_key" class="rs-input" placeholder="Optional Secret Key">
                            </div>
                        </div>
                    </div>

                    <div class="rs-divider">
                        <label class="rs-label">Description</label>
                        <textarea name="description" class="rs-textarea" rows="3" placeholder="Optional payment instructions for customers..."></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="rs-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="rs-btn-primary"><i class="bi bi-save"></i> Save Payment Method</button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection