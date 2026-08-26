@extends('layouts.vendor')

@section('title', 'Invoice - ' . $booking->booking_code)

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Invoice
            </h4>

            <p class="text-muted mb-0">
                Invoice for booking #{{ $booking->booking_code }}
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('vendor.invoices.print', $booking) }}"
                target="_blank"
                class="btn btn-light border"
            >
                <i class="bi bi-printer me-1"></i>
                Print
            </a>


            <a
                href="{{ route('vendor.invoices.download', $booking) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Download PDF
            </a>

        </div>

    </div>


    {{-- Invoice --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4 p-lg-5">

            {{-- Invoice Header --}}
            <div class="row align-items-start mb-5">

                <div class="col-md-6">

                    <h2 class="fw-bold mb-2">
                        {{ $booking->vendor?->name ?? 'Vendor' }}
                    </h2>

                    <p class="text-muted mb-1">
                        Resort Booking Invoice
                    </p>

                    @if($booking->vendor?->email)

                        <small class="text-muted d-block">
                            {{ $booking->vendor->email }}
                        </small>

                    @endif

                    @if($booking->vendor?->phone)

                        <small class="text-muted d-block">
                            {{ $booking->vendor->phone }}
                        </small>

                    @endif

                </div>


                <div class="col-md-6 text-md-end mt-4 mt-md-0">

                    <h4 class="fw-bold mb-2">
                        INVOICE
                    </h4>

                    <div class="text-muted">
                        Invoice No:
                        <strong>
                            INV-{{ $booking->booking_code }}
                        </strong>
                    </div>

                    <div class="text-muted">
                        Booking No:
                        <strong>
                            #{{ $booking->booking_code }}
                        </strong>
                    </div>

                    <div class="text-muted">
                        Date:
                        <strong>
                            {{ $booking->created_at?->format('d M Y') }}
                        </strong>
                    </div>

                </div>

            </div>


            <hr>


            {{-- Customer / Resort --}}
            <div class="row g-4 my-4">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-3">
                        Billed To
                    </h6>

                    <div class="border rounded p-3">

                        <strong class="d-block mb-1">
                            {{ $booking->user?->name ?? 'N/A' }}
                        </strong>

                        @if($booking->user?->email)

                            <span class="text-muted d-block">
                                {{ $booking->user->email }}
                            </span>

                        @endif

                        @if($booking->user?->phone)

                            <span class="text-muted d-block">
                                {{ $booking->user->phone }}
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-6">

                    <h6 class="fw-bold mb-3">
                        Accommodation
                    </h6>

                    <div class="border rounded p-3">

                        <strong class="d-block mb-1">
                            {{ $booking->resort?->name ?? 'N/A' }}
                        </strong>

                        <span class="text-muted d-block">
                            Room:
                            {{ $booking->room?->name ?? 'N/A' }}
                        </span>

                        @if($booking->room?->room_no)

                            <span class="text-muted d-block">
                                Room No:
                                {{ $booking->room->room_no }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Stay Information --}}
            <div class="row g-3 mb-4">

                <div class="col-md-3">

                    <div class="bg-light rounded p-3">

                        <small class="text-muted d-block">
                            Check In
                        </small>

                        <strong>
                            {{ $booking->check_in?->format('d M Y') }}
                        </strong>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="bg-light rounded p-3">

                        <small class="text-muted d-block">
                            Check Out
                        </small>

                        <strong>
                            {{ $booking->check_out?->format('d M Y') }}
                        </strong>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="bg-light rounded p-3">

                        <small class="text-muted d-block">
                            Nights
                        </small>

                        <strong>
                            {{ $booking->total_nights }}
                        </strong>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="bg-light rounded p-3">

                        <small class="text-muted d-block">
                            Guests
                        </small>

                        <strong>
                            {{ $booking->adults }} Adults,
                            {{ $booking->children }} Children
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Pricing --}}
            <div class="table-responsive mb-4">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Description
                            </th>

                            <th class="text-center">
                                Qty
                            </th>

                            <th class="text-end">
                                Rate
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td>

                                <strong>
                                    {{ $booking->room?->name ?? 'Room Accommodation' }}
                                </strong>

                                <small class="text-muted d-block">
                                    {{ $booking->check_in?->format('d M Y') }}
                                    -
                                    {{ $booking->check_out?->format('d M Y') }}
                                </small>

                            </td>

                            <td class="text-center">
                                {{ $booking->total_nights }}
                            </td>

                            <td class="text-end">
                                ৳{{ number_format($booking->room_price, 2) }}
                            </td>

                            <td class="text-end fw-semibold">
                                ৳{{ number_format($booking->subtotal, 2) }}
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- Totals --}}
            <div class="row justify-content-end">

                <div class="col-md-5">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Subtotal
                        </span>

                        <strong>
                            ৳{{ number_format($booking->subtotal, 2) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Discount
                        </span>

                        <strong class="text-success">
                            - ৳{{ number_format($booking->discount ?? 0, 2) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Tax
                        </span>

                        <strong>
                            ৳{{ number_format($booking->tax ?? 0, 2) }}
                        </strong>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between align-items-center">

                        <span class="fw-bold fs-5">
                            Total
                        </span>

                        <strong class="fw-bold fs-4 text-primary">
                            ৳{{ number_format($booking->total_amount, 2) }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Payment --}}
            <div class="row g-4 mt-4">

                <div class="col-md-6">

                    <h6 class="fw-bold">
                        Payment Information
                    </h6>

                    <p class="mb-1">
                        Status:

                        @if($booking->payment_status === 'paid')

                            <span class="badge bg-success">
                                Paid
                            </span>

                        @elseif($booking->payment_status === 'pending')

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @else

                            <span class="badge bg-danger">
                                {{ ucfirst($booking->payment_status) }}
                            </span>

                        @endif

                    </p>

                </div>


                <div class="col-md-6 text-md-end">

                    <h6 class="fw-bold">
                        Vendor Earnings
                    </h6>

                    <p class="mb-1">
                        Commission:
                        ৳{{ number_format($booking->admin_commission ?? 0, 2) }}
                    </p>

                    <strong class="text-success fs-5">
                        Vendor Earning:
                        ৳{{ number_format($booking->vendor_earning ?? 0, 2) }}
                    </strong>

                </div>

            </div>


            {{-- Footer --}}
            <div class="border-top mt-5 pt-4 text-center">

                <p class="text-muted mb-1">
                    Thank you for choosing our service.
                </p>

                <small class="text-muted">
                    This is a computer-generated invoice and does not require a signature.
                </small>

            </div>

        </div>

    </div>

</div>

@endsection