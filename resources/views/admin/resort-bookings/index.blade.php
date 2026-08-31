@extends('layouts.admin')

@section('title', 'Resort Booking Management')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fas fa-hotel text-primary"></i>
            Resort Booking Management
        </h4>

    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.resort-bookings.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Booking Code</label>
                    <input type="text" name="booking_code" class="form-control" placeholder="Search by code" value="{{ request('booking_code') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Customer</label>
                    <select name="user_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="booking_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('booking_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="checked_in" {{ request('booking_status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                        <option value="checked_out" {{ request('booking_status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                        <option value="cancelled" {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bookings Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">#</th>
                            <th>Booking Code</th>
                            <th>Customer</th>
                            <th>Resort</th>
                            <th>Room</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Total (৳)</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $bookings->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $booking->booking_code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">
                                            {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        {{ $booking->user->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>{{ $booking->resort->name ?? 'N/A' }}</td>
                                <td>{{ $booking->room->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $booking->check_in->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $booking->check_out->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <strong>৳ {{ number_format($booking->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $paymentColors = [
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            'refunded' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $paymentColors[$booking->payment_status] ?? 'secondary' }}">
                                        <i class="fas fa-{{ $booking->payment_status == 'paid' ? 'check' : ($booking->payment_status == 'pending' ? 'clock' : 'times') }}"></i>
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'confirmed' => 'success',
                                            'checked_in' => 'primary',
                                            'checked_out' => 'info',
                                            'cancelled' => 'danger',
                                            'pending' => 'warning'
                                        ];
                                        $statusIcons = [
                                            'confirmed' => 'check-circle',
                                            'checked_in' => 'sign-in-alt',
                                            'checked_out' => 'sign-out-alt',
                                            'cancelled' => 'times-circle',
                                            'pending' => 'clock'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$booking->booking_status] ?? 'secondary' }}">
                                        <i class="fas fa-{{ $statusIcons[$booking->booking_status] ?? 'info-circle' }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info viewBtn" data-id="{{ $booking->id }}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning editBtn" data-id="{{ $booking->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $booking->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-2x text-muted d-block mb-2"></i>
                                    <span class="text-muted">No bookings found</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} entries
                </div>
                <div>
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =========================================== --}}
{{-- CREATE BOOKING MODAL --}}
{{-- =========================================== --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('admin.resort-bookings.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-primary"></i>
                        Create Resort Booking
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Customer --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required>
                                <option value="">Select Customer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Resort --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Resort <span class="text-danger">*</span></label>
                            <select id="create_resort" name="resort_id" class="form-select" required>
                                <option value="">Select Resort</option>
                                @foreach($resorts as $resort)
                                    <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Room --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Room <span class="text-danger">*</span></label>
                            <select id="create_room" name="room_id" class="form-select" required>
                                <option value="">Select Room</option>
                            </select>
                        </div>

                        {{-- Room Price Display --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Room Price (per night)</label>
                            <input type="text" id="room_price" class="form-control" readonly value="৳ 0.00">
                        </div>

                        {{-- Check In --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Check In <span class="text-danger">*</span></label>
                            <input type="date" name="check_in" id="check_in" class="form-control" required>
                        </div>

                        {{-- Check Out --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Check Out <span class="text-danger">*</span></label>
                            <input type="date" name="check_out" id="check_out" class="form-control" required>
                        </div>

                        {{-- Total Nights --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Total Nights</label>
                            <input type="text" id="total_nights" name="total_nights" class="form-control" readonly value="0">
                        </div>

                        {{-- Total Amount --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Total Amount (৳)</label>
                            <input type="text" id="total_amount" name="total_amount" class="form-control" readonly value="0.00">
                        </div>

                        {{-- Adults --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Adults <span class="text-danger">*</span></label>
                            <input type="number" name="adults" value="1" min="1" class="form-control" required>
                        </div>

                        {{-- Children --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Children</label>
                            <input type="number" name="children" value="0" min="0" class="form-control">
                        </div>

                        {{-- Discount --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Discount (৳)</label>
                            <input type="number" step="0.01" value="0" name="discount" id="discount" class="form-control">
                        </div>

                        {{-- Tax --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Tax (৳)</label>
                            <input type="number" step="0.01" value="0" name="tax" id="tax" class="form-control">
                        </div>

                        {{-- ========================================= --}}
                        {{-- GUEST SECTION --}}
                        {{-- ========================================= --}}
                        <div class="col-md-12 mb-3">
                            <hr>
                            <h5 class="mb-3">
                                <i class="fas fa-users text-primary"></i>
                                Guest Information
                            </h5>

                            <div id="guestWrapper">
                                {{-- Dynamic guests will be added here --}}
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-success" id="addGuest">
                                    <i class="fas fa-plus"></i>
                                    Add Guest
                                </button>
                            </div>
                        </div>

                        {{-- Special Request --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Special Request</label>
                            <textarea name="special_request" rows="2" class="form-control" placeholder="Any special requests..."></textarea>
                        </div>

                        {{-- Hidden fields for payment and booking status --}}
                        <input type="hidden" name="payment_status" value="pending">
                        <input type="hidden" name="booking_status" value="pending">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================== --}}
{{-- EDIT BOOKING MODAL --}}
{{-- =========================================== --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit text-warning"></i>
                        Edit Resort Booking
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Customer --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                            <select name="user_id" id="edit_user" class="form-select" required>
                                <option value="">Select Customer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Resort --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Resort <span class="text-danger">*</span></label>
                            <select id="edit_resort" name="resort_id" class="form-select" required>
                                @foreach($resorts as $resort)
                                    <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Room --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Room <span class="text-danger">*</span></label>
                            <select id="edit_room" name="room_id" class="form-select" required>
                                <option value="">Select Room</option>
                            </select>
                        </div>

                        {{-- Check In --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Check In <span class="text-danger">*</span></label>
                            <input type="date" name="check_in" id="edit_check_in" class="form-control" required>
                        </div>

                        {{-- Check Out --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Check Out <span class="text-danger">*</span></label>
                            <input type="date" name="check_out" id="edit_check_out" class="form-control" required>
                        </div>

                        {{-- Adults --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Adults <span class="text-danger">*</span></label>
                            <input type="number" name="adults" id="edit_adults" min="1" class="form-control" required>
                        </div>

                        {{-- Children --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Children</label>
                            <input type="number" name="children" id="edit_children" min="0" class="form-control">
                        </div>

                        {{-- Discount --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Discount (৳)</label>
                            <input type="number" step="0.01" name="discount" id="edit_discount" class="form-control">
                        </div>

                        {{-- Tax --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Tax (৳)</label>
                            <input type="number" step="0.01" name="tax" id="edit_tax" class="form-control">
                        </div>

                        {{-- Payment Status --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Payment Status <span class="text-danger">*</span></label>
                            <select name="payment_status" id="edit_payment_status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>

                        {{-- Booking Status --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Booking Status <span class="text-danger">*</span></label>
                            <select name="booking_status" id="edit_booking_status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="checked_in">Checked In</option>
                                <option value="checked_out">Checked Out</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        {{-- Special Request --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Special Request</label>
                            <textarea name="special_request" id="edit_special_request" rows="3" class="form-control" placeholder="Any special requests..."></textarea>
                        </div>

                        {{-- ========================================= --}}
                        {{-- EDIT GUEST SECTION --}}
                        {{-- ========================================= --}}
                        <div class="col-md-12 mb-3">
                            <hr>
                            <h5 class="mb-3">
                                <i class="fas fa-users text-primary"></i>
                                Guest Information
                            </h5>

                            <div id="editGuestWrapper">
                                {{-- Existing guests will be loaded here --}}
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-success" id="editAddGuest">
                                    <i class="fas fa-plus"></i>
                                    Add Guest
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i>
                        Update Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================== --}}
{{-- VIEW BOOKING MODAL --}}
{{-- =========================================== --}}
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle text-info"></i>
                    Booking Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Booking Information</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <th width="40%">Booking Code</th>
                                        <td><span class="fw-bold" id="v_booking_code"></span></td>
                                    </tr>
                                    <tr>
                                        <th>Customer</th>
                                        <td id="v_customer"></td>
                                    </tr>
                                    <tr>
                                        <th>Resort</th>
                                        <td id="v_resort"></td>
                                    </tr>
                                    <tr>
                                        <th>Room</th>
                                        <td id="v_room"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Stay Details</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <th width="40%">Check In</th>
                                        <td id="v_check_in"></td>
                                    </tr>
                                    <tr>
                                        <th>Check Out</th>
                                        <td id="v_check_out"></td>
                                    </tr>
                                    <tr>
                                        <th>Total Nights</th>
                                        <td id="v_nights"></td>
                                    </tr>
                                    <tr>
                                        <th>Adults</th>
                                        <td id="v_adults"></td>
                                    </tr>
                                    <tr>
                                        <th>Children</th>
                                        <td id="v_children"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Financial Details</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <th width="40%">Total Amount</th>
                                        <td class="fw-bold text-success" id="v_total"></td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status</th>
                                        <td id="v_payment"></td>
                                    </tr>
                                    <tr>
                                        <th>Booking Status</th>
                                        <td id="v_status"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Special Request</h6>
                                <p id="v_request" class="card-text text-muted">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- View Guests --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="fas fa-users text-primary"></i>
                                    Guest List
                                </h6>
                                <div id="v_guests" class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Phone</th>
                                                <th>NID</th>
                                                <th>Passport</th>
                                            </tr>
                                        </thead>
                                        <tbody id="v_guests_body">
                                            {{-- Guests will be loaded here --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

{{-- =========================================== --}}
{{-- DELETE BOOKING MODAL --}}
{{-- =========================================== --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <h5 class="text-dark">Are you sure?</h5>
                    <p class="text-muted">You want to delete this booking? This action cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- =========================================== --}}
{{-- JAVASCRIPT --}}
{{-- =========================================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {

    /**
     * ===========================================
     * CREATE MODAL - Load Rooms by Resort
     * ===========================================
     */
    $('#create_resort').change(function() {
        let resortId = $(this).val();
        let roomSelect = $('#create_room');

        if (!resortId) {
            roomSelect.html('<option value="">Select Room</option>');
            $('#room_price').val('৳ 0.00');
            return;
        }

        roomSelect.html('<option>Loading...</option>');

        $.get('/admin/resort-bookings/get-rooms/' + resortId, function(response) {
            let html = '<option value="">Select Room</option>';
            
            if (response.data && response.data.length > 0) {
                $.each(response.data, function(i, room) {
                    html += `<option value="${room.id}" data-price="${room.price || 0}">${room.name}</option>`;
                });
            }
            
            roomSelect.html(html);
        }).fail(function() {
            roomSelect.html('<option value="">Error loading rooms</option>');
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load rooms. Please try again.'
            });
        });
    });

    /**
     * ===========================================
     * CREATE MODAL - Load Room Price
     * ===========================================
     */
    function loadRoomPrice() {
        let roomId = $('#create_room').val();
        let date = $('#check_in').val();

        if (!roomId || !date) {
            return;
        }

        $.post("{{ route('admin.resort-bookings.getPrice') }}", {
            _token: "{{ csrf_token() }}",
            room_id: roomId,
            date: date
        }, function(response) {
            if (response.price) {
                $('#room_price').val('৳ ' + parseFloat(response.price).toFixed(2));
                calculateTotal();
            } else {
                $('#room_price').val('৳ 0.00');
            }
        }).fail(function() {
            $('#room_price').val('৳ 0.00');
        });
    }

    $('#create_room').change(loadRoomPrice);
    $('#check_in').change(loadRoomPrice);

    /**
     * ===========================================
     * CREATE MODAL - Calculate Nights
     * ===========================================
     */
    function calculateNights() {
        let checkIn = $('#check_in').val();
        let checkOut = $('#check_out').val();

        if (!checkIn || !checkOut) {
            $('#total_nights').val(0);
            return;
        }

        let start = new Date(checkIn);
        let end = new Date(checkOut);
        let diff = end - start;
        let nights = Math.floor(diff / (1000 * 60 * 60 * 24));

        if (nights < 0) nights = 0;
        
        $('#total_nights').val(nights);
        calculateTotal();
    }

    $('#check_in, #check_out').on('change', calculateNights);

    /**
     * ===========================================
     * CREATE MODAL - Calculate Total
     * ===========================================
     */
    function calculateTotal() {
        let priceText = $('#room_price').val();
        let price = parseFloat(priceText.replace(/[^0-9.]/g, '')) || 0;
        let nights = parseInt($('#total_nights').val()) || 0;
        let discount = parseFloat($('#discount').val()) || 0;
        let tax = parseFloat($('#tax').val()) || 0;

        let subtotal = price * nights;
        let total = (subtotal - discount) + tax;

        if (total < 0) total = 0;

        $('#total_amount').val(total.toFixed(2));
    }

    $('#discount, #tax').on('keyup change', calculateTotal);

    /**
     * ===========================================
     * CREATE MODAL - Dynamic Guests
     * ===========================================
     */
    let guestIndex = 0;

    $('#addGuest').click(function() {
        let html = `
            <div class="card border mb-3 guest-item">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <strong>
                        <i class="fas fa-user text-primary"></i>
                        Guest ${guestIndex + 1}
                    </strong>
                    <button type="button" class="btn btn-sm btn-danger removeGuest">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="guests[${guestIndex}][name]" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="guests[${guestIndex}][age]" class="form-control" placeholder="Age" min="0">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="guests[${guestIndex}][gender]" class="form-select">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="guests[${guestIndex}][phone]" class="form-control" placeholder="Phone number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NID Number</label>
                            <input type="text" name="guests[${guestIndex}][nid]" class="form-control" placeholder="NID number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passport Number</label>
                            <input type="text" name="guests[${guestIndex}][passport]" class="form-control" placeholder="Passport number">
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#guestWrapper').append(html);
        guestIndex++;
    });

    $(document).on('click', '.removeGuest', function() {
        $(this).closest('.guest-item').remove();
    });

    /**
     * ===========================================
     * CREATE MODAL - Reset on Close
     * ===========================================
     */
    $('#createModal').on('hidden.bs.modal', function() {
        $('#createForm')[0].reset();
        $('#create_room').html('<option value="">Select Room</option>');
        $('#room_price').val('৳ 0.00');
        $('#total_nights').val(0);
        $('#total_amount').val('0.00');
        $('#guestWrapper').html('');
        guestIndex = 0;
    });

    /**
     * ===========================================
     * EDIT MODAL - Load Rooms by Resort
     * ===========================================
     */
    $('#edit_resort').change(function() {
        let resortId = $(this).val();
        let roomSelect = $('#edit_room');

        if (!resortId) {
            roomSelect.html('<option value="">Select Room</option>');
            return;
        }

        roomSelect.html('<option>Loading...</option>');

        $.get('/admin/resort-bookings/get-rooms/' + resortId, function(response) {
            let html = '<option value="">Select Room</option>';
            
            if (response.data && response.data.length > 0) {
                $.each(response.data, function(i, room) {
                    html += `<option value="${room.id}" data-price="${room.price || 0}">${room.name}</option>`;
                });
            }
            
            roomSelect.html(html);
        }).fail(function() {
            roomSelect.html('<option value="">Error loading rooms</option>');
        });
    });

    /**
     * ===========================================
     * EDIT MODAL - Dynamic Guests
     * ===========================================
     */
    let editGuestIndex = 0;

    $('#editAddGuest').click(function() {
        let html = `
            <div class="card border mb-3 guest-item">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <strong>
                        <i class="fas fa-user text-primary"></i>
                        Guest ${editGuestIndex + 1}
                    </strong>
                    <button type="button" class="btn btn-sm btn-danger removeEditGuest">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="edit_guests[${editGuestIndex}][name]" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="edit_guests[${editGuestIndex}][age]" class="form-control" placeholder="Age" min="0">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="edit_guests[${editGuestIndex}][gender]" class="form-select">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="edit_guests[${editGuestIndex}][phone]" class="form-control" placeholder="Phone number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NID Number</label>
                            <input type="text" name="edit_guests[${editGuestIndex}][nid]" class="form-control" placeholder="NID number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passport Number</label>
                            <input type="text" name="edit_guests[${editGuestIndex}][passport]" class="form-control" placeholder="Passport number">
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#editGuestWrapper').append(html);
        editGuestIndex++;
    });

    $(document).on('click', '.removeEditGuest', function() {
        $(this).closest('.guest-item').remove();
    });

    /**
     * ===========================================
     * VIEW MODAL - Show Booking Details
     * ===========================================
     */
    $('.viewBtn').click(function() {
        let id = $(this).data('id');

        $.get('/admin/resort-bookings/' + id, function(response) {
            let d = response.data;

            // Basic Info
            $('#v_booking_code').text(d.booking_code);
            $('#v_customer').text(d.user.name);
            $('#v_resort').text(d.resort.name);
            $('#v_room').text(d.room.name);
            $('#v_check_in').text(new Date(d.check_in).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }));
            $('#v_check_out').text(new Date(d.check_out).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }));
            $('#v_nights').text(d.total_nights);
            $('#v_adults').text(d.adults);
            $('#v_children').text(d.children);
            
            // Financial
            let total = parseFloat(d.total_amount).toFixed(2);
            $('#v_total').text('৳ ' + total);
            
            // Payment Status
            let paymentColors = {
                'paid': 'success',
                'pending': 'warning',
                'failed': 'danger',
                'refunded': 'secondary'
            };
            $('#v_payment').html(
                `<span class="badge bg-${paymentColors[d.payment_status] || 'secondary'}">${d.payment_status.charAt(0).toUpperCase() + d.payment_status.slice(1)}</span>`
            );
            
            // Booking Status
            let statusColors = {
                'confirmed': 'success',
                'checked_in': 'primary',
                'checked_out': 'info',
                'cancelled': 'danger',
                'pending': 'warning'
            };
            let statusText = d.booking_status.replace('_', ' ');
            $('#v_status').html(
                `<span class="badge bg-${statusColors[d.booking_status] || 'secondary'}">${statusText.charAt(0).toUpperCase() + statusText.slice(1)}</span>`
            );
            
            $('#v_request').text(d.special_request || '-');

            // Guests
            let guestHtml = '';
            if (d.guests && d.guests.length > 0) {
                $.each(d.guests, function(index, guest) {
                    guestHtml += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong>${guest.name}</strong></td>
                            <td>${guest.age || '-'}</td>
                            <td><span class="badge bg-light text-dark">${guest.gender || '-'}</span></td>
                            <td>${guest.phone || '-'}</td>
                            <td>${guest.nid || '-'}</td>
                            <td>${guest.passport || '-'}</td>
                        </tr>
                    `;
                });
            } else {
                guestHtml = `
                    <tr>
                        <td colspan="7" class="text-center text-muted">No guests added</td>
                    </tr>
                `;
            }
            $('#v_guests_body').html(guestHtml);

            $('#viewModal').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load booking details.'
            });
        });
    });

    /**
     * ===========================================
     * EDIT MODAL - Load Booking Data
     * ===========================================
     */
    $('.editBtn').click(function() {
        let id = $(this).data('id');

        $.get('/admin/resort-bookings/' + id, function(response) {
            let d = response.data;

            // Reset edit guest wrapper
            $('#editGuestWrapper').html('');
            editGuestIndex = 0;

            // Set form action
            $('#editForm').attr('action', '/admin/resort-bookings/' + id);
            
            // Basic Info
            $('#edit_user').val(d.user_id);
            $('#edit_resort').val(d.resort_id).trigger('change');

            setTimeout(function() {
                $('#edit_room').val(d.room_id);
            }, 300);

            $('#edit_check_in').val(d.check_in);
            $('#edit_check_out').val(d.check_out);
            $('#edit_adults').val(d.adults);
            $('#edit_children').val(d.children);
            $('#edit_discount').val(d.discount);
            $('#edit_tax').val(d.tax);
            $('#edit_payment_status').val(d.payment_status);
            $('#edit_booking_status').val(d.booking_status);
            $('#edit_special_request').val(d.special_request);

            // Load existing guests
            if (d.guests && d.guests.length > 0) {
                $.each(d.guests, function(index, guest) {
                    let html = `
                        <div class="card border mb-3 guest-item">
                            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                <strong>
                                    <i class="fas fa-user text-primary"></i>
                                    Guest ${index + 1}
                                </strong>
                                <button type="button" class="btn btn-sm btn-danger removeEditGuest">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="edit_guests[${index}][name]" class="form-control" value="${guest.name}" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Age</label>
                                        <input type="number" name="edit_guests[${index}][age]" class="form-control" value="${guest.age || ''}">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="edit_guests[${index}][gender]" class="form-select">
                                            <option value="male" ${guest.gender == 'male' ? 'selected' : ''}>Male</option>
                                            <option value="female" ${guest.gender == 'female' ? 'selected' : ''}>Female</option>
                                            <option value="other" ${guest.gender == 'other' ? 'selected' : ''}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="edit_guests[${index}][phone]" class="form-control" value="${guest.phone || ''}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NID Number</label>
                                        <input type="text" name="edit_guests[${index}][nid]" class="form-control" value="${guest.nid || ''}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Passport Number</label>
                                        <input type="text" name="edit_guests[${index}][passport]" class="form-control" value="${guest.passport || ''}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#editGuestWrapper').append(html);
                    editGuestIndex = index + 1;
                });
            }

            $('#editModal').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load booking data.'
            });
        });
    });

    /**
     * ===========================================
     * DELETE MODAL
     * ===========================================
     */
    $('.deleteBtn').click(function() {
        let id = $(this).data('id');
        let url = '/admin/resort-bookings/' + id;
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });

    /**
     * ===========================================
     * FORM VALIDATION - Check Dates
     * ===========================================
     */
    $('#createForm, #editForm').on('submit', function(e) {
        let checkIn = $(this).find('[name="check_in"]').val();
        let checkOut = $(this).find('[name="check_out"]').val();

        if (checkIn && checkOut && new Date(checkIn) >= new Date(checkOut)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date Range',
                text: 'Check Out date must be after Check In date.'
            });
            return false;
        }
    });

});
</script>
@endpush