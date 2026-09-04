@extends('layouts.vendor')

@section('title', 'Edit Room Booking')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --rs-surface: #1a1d27; --rs-surface2: #222636; --rs-border: rgba(255,255,255,.07);
    --rs-text: #e2e8f0; --rs-muted: #64748b;
    --rs-indigo: #6366f1; --rs-purple: #8b5cf6;
    --rs-success: #22c55e; --rs-warning: #f59e0b; --rs-danger: #ef4444; --rs-secondary: #94a3b8;
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
.rs-header-content { position: relative; z-index: 1; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px; }
.rs-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(90deg, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

.rs-wrap .alert { background: var(--rs-surface); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 12px; font-size: .84rem; box-shadow: var(--rs-shadow); }
.rs-wrap .alert-danger  { border-left: 3px solid var(--rs-danger); }
.rs-wrap .alert-warning { border-left: 3px solid var(--rs-warning); }

.rs-btn {
    display: inline-flex; align-items: center; gap: 6px;
    border: 1px solid rgba(255,255,255,.12); background: var(--rs-surface2); color: var(--rs-text);
    border-radius: 9px; padding: 9px 16px; font-size: .8rem; font-weight: 600; text-decoration: none;
}
.rs-btn:hover { background: rgba(255,255,255,.08); color: var(--rs-text); }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); margin-bottom: 22px; overflow: hidden; }
.rs-card-header { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); }
.rs-card-header h5 { font-size: .95rem; font-weight: 700; margin: 0; color: var(--rs-text); }
.rs-card-body { padding: 20px; }

.rs-wrap label.form-label { color: var(--rs-muted); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }

.rs-wrap .form-control,
.rs-wrap .form-select {
    background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text);
    border-radius: 9px; font-size: .85rem; padding: 10px 13px;
}
.rs-wrap .form-control::placeholder { color: var(--rs-muted); }
.rs-wrap .form-control:focus,
.rs-wrap .form-select:focus {
    background: var(--rs-surface2); color: var(--rs-text); border-color: var(--rs-indigo);
    box-shadow: 0 0 0 3px rgba(99,102,241,.18);
}
.rs-wrap .form-control.bg-light { background: rgba(255,255,255,.03); color: var(--rs-muted); }
.rs-wrap .form-control:disabled,
.rs-wrap .form-control[readonly] { background: rgba(255,255,255,.03); color: var(--rs-muted); opacity: 1; }
.rs-wrap .form-select option { background: var(--rs-surface2); color: var(--rs-text); }
.rs-wrap small.text-muted { color: var(--rs-muted) !important; }
.rs-wrap .invalid-feedback { color: #fca5a5; font-size: .74rem; }
.rs-wrap .is-invalid { border-color: var(--rs-danger) !important; }

.rs-btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border: none;
    border-radius: 9px; padding: 10px 18px; font-size: .82rem; font-weight: 700; cursor: pointer;
}
.rs-btn-primary:hover { opacity: .92; color: #fff; }
.rs-btn-cancel {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text);
    border-radius: 9px; padding: 10px 18px; font-size: .82rem; font-weight: 600; text-decoration: none;
}
.rs-btn-cancel:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }

.rs-summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: .84rem; }
.rs-summary-row span:first-child { color: var(--rs-muted); }
.rs-summary-row.total span { font-weight: 700; font-size: 1.05rem; color: var(--rs-text); }
.rs-divider { border: none; border-top: 1px solid var(--rs-border); margin: 16px 0; }

.rs-guest-box { border: 1px solid var(--rs-border); background: var(--rs-surface2); border-radius: 11px; padding: 13px 15px; margin-bottom: 12px; }
.rs-guest-box:last-child { margin-bottom: 0; }

</style>


<div class="rs-wrap">

    {{-- HEADER --}}
    <div class="rs-header">
        <div class="rs-header-content">
            <div>
                <div class="rs-title"><i class="bi bi-pencil-square me-2"></i> Edit Room Booking</div>
                <div class="rs-subtitle">Update booking details and manage booking status.</div>
            </div>
            <div>
                <a href="{{ route('vendor.room-bookings.show', $booking) }}" class="rs-btn">
                    <i class="bi bi-arrow-left"></i> Back to Booking
                </a>
            </div>
        </div>
    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i> Please fix the following errors:
            </div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row g-4">

        {{-- ================= FORM ================= --}}
        <div class="col-lg-8">

            <div class="rs-card">
                <div class="rs-card-header">
                    <h5>Booking Information</h5>
                </div>

                <div class="rs-card-body">

                    <form action="{{ route('vendor.room-bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Booking Code --}}
                        <div class="mb-4">
                            <label class="form-label">Booking Code</label>
                            <input type="text" class="form-control bg-light" value="{{ $booking->booking_code }}" readonly>
                            <small class="text-muted">Booking code cannot be changed.</small>
                        </div>

                        {{-- Customer / Resort / Room --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Customer</label>
                                <input type="text" class="form-control bg-light" value="{{ $booking->user?->name ?? 'N/A' }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Resort</label>
                                <input type="text" class="form-control bg-light" value="{{ $booking->room?->name ?? 'N/A' }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Room</label>
                                <input type="text" class="form-control bg-light" value="{{ $booking->room?->name ?? 'N/A' }}" readonly>
                            </div>
                        </div>

                        {{-- Stay Dates --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="check_in" class="form-label">Check-in Date</label>
                                <input type="date" name="check_in" id="check_in"
                                    class="form-control @error('check_in') is-invalid @enderror"
                                    value="{{ old('check_in', optional($booking->check_in)->format('Y-m-d')) }}" required>
                                @error('check_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="check_out" class="form-label">Check-out Date</label>
                                <input type="date" name="check_out" id="check_out"
                                    class="form-control @error('check_out') is-invalid @enderror"
                                    value="{{ old('check_out', optional($booking->check_out)->format('Y-m-d')) }}" required>
                                @error('check_out')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Guest Information --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="room_count" class="form-label">Room Count</label>
                                <input type="number" id="room_count" class="form-control bg-light" value="{{ $booking->room_count ?? 1 }}" readonly>
                                <small class="text-muted">Room count cannot be changed here.</small>
                            </div>
                            <div class="col-md-4">
                                <label for="adults" class="form-label">Adults</label>
                                <input type="number" name="adults" id="adults" min="1"
                                    class="form-control @error('adults') is-invalid @enderror"
                                    value="{{ old('adults', $booking->adults ?? 1) }}" required>
                                @error('adults')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="children" class="form-label">Children</label>
                                <input type="number" name="children" id="children" min="0"
                                    class="form-control @error('children') is-invalid @enderror"
                                    value="{{ old('children', $booking->children ?? 0) }}">
                                @error('children')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Booking Status --}}
                        <div class="mb-4">
                            <label for="booking_status" class="form-label">Booking Status</label>
                            <select name="booking_status" id="booking_status"
                                class="form-select @error('booking_status') is-invalid @enderror" required>

                                @php $currentStatus = old('booking_status', $booking->booking_status); @endphp

                                <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $currentStatus === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="checked_in" {{ $currentStatus === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                                <option value="checked_out" {{ $currentStatus === 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                                <option value="cancelled" {{ $currentStatus === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('booking_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-1">Status transitions are protected by the booking controller.</small>
                        </div>

                        {{-- Payment Status --}}
                        <div class="mb-4">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select name="payment_status" id="payment_status"
                                class="form-select @error('payment_status') is-invalid @enderror" required>

                                @php $paymentStatus = old('payment_status', $booking->payment_status); @endphp

                                <option value="pending" {{ $paymentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $paymentStatus === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $paymentStatus === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            @error('payment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Special Request --}}
                        <div class="mb-4">
                            <label for="special_request" class="form-label">Special Request</label>
                            <textarea name="special_request" id="special_request" rows="5" maxlength="5000"
                                class="form-control @error('special_request') is-invalid @enderror"
                                placeholder="Enter any special request...">{{ old('special_request', $booking->special_request) }}</textarea>
                            @error('special_request')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('vendor.room-bookings.show', $booking) }}" class="rs-btn-cancel">Cancel</a>
                            <button type="submit" class="rs-btn-primary">
                                <i class="bi bi-save"></i> Update Booking
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>


        {{-- ================= SIDEBAR ================= --}}
        <div class="col-lg-4">

            {{-- Booking Summary --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <h5>Booking Summary</h5>
                </div>
                <div class="rs-card-body">

                    <div class="rs-summary-row">
                        <span>Booking Code</span>
                        <span class="fw-semibold" style="color:var(--rs-text);">{{ $booking->booking_code }}</span>
                    </div>

                    <div class="rs-summary-row">
                        <span>Room Count</span>
                        <span class="fw-semibold" style="color:var(--rs-text);">{{ $booking->room_count ?? 1 }}</span>
                    </div>

                    <div class="rs-summary-row">
                        <span>Total Nights</span>
                        <span class="fw-semibold" style="color:var(--rs-text);">{{ $booking->total_nights ?? 0 }}</span>
                    </div>

                    <hr class="rs-divider">

                    <div class="rs-summary-row">
                        <span>Subtotal</span>
                        <span class="fw-semibold" style="color:var(--rs-text);">৳{{ number_format($booking->subtotal ?? 0, 2) }}</span>
                    </div>

                    <div class="rs-summary-row">
                        <span>Discount</span>
                        <span style="color:#86efac;">- ৳{{ number_format($booking->discount ?? 0, 2) }}</span>
                    </div>

                    <div class="rs-summary-row">
                        <span>Tax</span>
                        <span style="color:var(--rs-text);">৳{{ number_format($booking->tax ?? 0, 2) }}</span>
                    </div>

                    <hr class="rs-divider">

                    <div class="rs-summary-row total">
                        <span>Total Amount</span>
                        <span>৳{{ number_format($booking->total_amount ?? 0, 2) }}</span>
                    </div>

                    <div class="rs-summary-row mb-0">
                        <span>Vendor Earning</span>
                        <span class="fw-bold" style="color:#86efac;">৳{{ number_format($booking->vendor_earning ?? 0, 2) }}</span>
                    </div>

                </div>
            </div>


            {{-- Guest List --}}
            @if($booking->guests && $booking->guests->count())
                <div class="rs-card">
                    <div class="rs-card-header">
                        <h5>Guest Information</h5>
                    </div>
                    <div class="rs-card-body">

                        @foreach($booking->guests as $guest)
                            <div class="rs-guest-box">
                                <div class="fw-semibold mb-1" style="color:var(--rs-text);">
                                    <i class="bi bi-person me-1"></i> {{ $guest->name }}
                                </div>

                                @if($guest->age !== null)
                                    <small class="text-muted d-block">Age: {{ $guest->age }}</small>
                                @endif

                                @if($guest->gender)
                                    <small class="text-muted d-block">Gender: {{ ucfirst($guest->gender) }}</small>
                                @endif

                                @if($guest->phone)
                                    <small class="text-muted d-block">Phone: {{ $guest->phone }}</small>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif


            {{-- Warning --}}
            <div class="alert alert-warning mb-0">
                <div class="fw-semibold mb-1">
                    <i class="bi bi-info-circle me-1"></i> Important
                </div>
                <small>
                    Financial amounts, commission and vendor earnings are not editable from this form.
                    Booking cancellation should be performed through the dedicated Cancel Booking action.
                </small>
            </div>

        </div>

    </div>

</div>

@endsection