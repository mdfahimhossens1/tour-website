<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Invoice - {{ $booking->booking_code }}
    </title>

    <style>

        @page {
            margin: 35px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            font-size: 12px;
            line-height: 1.5;
        }

        .clearfix {
            clear: both;
        }

        .header {
            width: 100%;
            margin-bottom: 25px;
        }

        .vendor {
            float: left;
            width: 55%;
        }

        .invoice-title {
            float: right;
            width: 45%;
            text-align: right;
        }

        h1 {
            margin: 0 0 5px;
            font-size: 24px;
        }

        h2 {
            margin: 0 0 5px;
            font-size: 18px;
        }

        h3 {
            margin: 0 0 8px;
            font-size: 14px;
        }

        p {
            margin: 3px 0;
        }

        .muted {
            color: #777;
        }

        .line {
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        .box-row {
            width: 100%;
            margin-bottom: 20px;
        }

        .box {
            width: 48%;
            float: left;
            border: 1px solid #ddd;
            padding: 12px;
            min-height: 85px;
        }

        .box.right {
            float: right;
        }

        .info-row {
            width: 100%;
            margin-bottom: 20px;
        }

        .info {
            width: 23%;
            float: left;
            margin-right: 2%;
            background: #f5f5f5;
            padding: 10px;
            min-height: 55px;
        }

        .info:last-child {
            margin-right: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #f3f3f3;
            padding: 9px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        td {
            padding: 9px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            width: 40%;
            margin-left: auto;
            margin-top: 20px;
        }

        .total-row {
            width: 100%;
            padding: 5px 0;
        }

        .total-label {
            float: left;
        }

        .total-value {
            float: right;
        }

        .grand-total {
            border-top: 1px solid #222;
            margin-top: 8px;
            padding-top: 10px;
            font-size: 15px;
            font-weight: bold;
        }

        .success {
            color: #198754;
        }

        .danger {
            color: #dc3545;
        }

        .footer {
            border-top: 1px solid #ddd;
            margin-top: 40px;
            padding-top: 15px;
            text-align: center;
            color: #777;
        }

    </style>

</head>


<body>

{{-- Header --}}

<div class="header">

    <div class="vendor">

        <h2>
            {{ $booking->vendor?->name ?? 'Vendor' }}
        </h2>

        <p class="muted">
            Resort Booking Invoice
        </p>

        @if($booking->vendor?->email)

            <p>
                {{ $booking->vendor->email }}
            </p>

        @endif

        @if($booking->vendor?->phone)

            <p>
                {{ $booking->vendor->phone }}
            </p>

        @endif

    </div>


    <div class="invoice-title">

        <h1>
            INVOICE
        </h1>

        <p>
            Invoice No:
            <strong>
                INV-{{ $booking->booking_code }}
            </strong>
        </p>

        <p>
            Booking No:
            <strong>
                #{{ $booking->booking_code }}
            </strong>
        </p>

        <p>
            Date:
            {{ $booking->created_at?->format('d M Y') }}
        </p>

    </div>

    <div class="clearfix"></div>

</div>


<div class="line"></div>


{{-- Customer / Resort --}}

<div class="box-row">

    <div class="box">

        <h3>
            Billed To
        </h3>

        <strong>
            {{ $booking->user?->name ?? 'N/A' }}
        </strong>

        @if($booking->user?->email)

            <p class="muted">
                {{ $booking->user->email }}
            </p>

        @endif

        @if($booking->user?->phone)

            <p class="muted">
                {{ $booking->user->phone }}
            </p>

        @endif

    </div>


    <div class="box right">

        <h3>
            Accommodation
        </h3>

        <strong>
            {{ $booking->resort?->name ?? 'N/A' }}
        </strong>

        <p>
            Room:
            {{ $booking->room?->name ?? 'N/A' }}
        </p>

        @if($booking->room?->room_no)

            <p>
                Room No:
                {{ $booking->room->room_no }}
            </p>

        @endif

    </div>

    <div class="clearfix"></div>

</div>


{{-- Stay Information --}}

<div class="info-row">

    <div class="info">

        <span class="muted">
            Check In
        </span>

        <strong>
            {{ $booking->check_in?->format('d M Y') }}
        </strong>

    </div>


    <div class="info">

        <span class="muted">
            Check Out
        </span>

        <strong>
            {{ $booking->check_out?->format('d M Y') }}
        </strong>

    </div>


    <div class="info">

        <span class="muted">
            Nights
        </span>

        <strong>
            {{ $booking->total_nights }}
        </strong>

    </div>


    <div class="info">

        <span class="muted">
            Guests
        </span>

        <strong>
            {{ $booking->adults }} Adults,
            {{ $booking->children }} Children
        </strong>

    </div>

    <div class="clearfix"></div>

</div>


{{-- Pricing --}}

<table>

    <thead>

        <tr>

            <th>
                Description
            </th>

            <th class="text-center">
                Qty
            </th>

            <th class="text-right">
                Rate
            </th>

            <th class="text-right">
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

                <p class="muted">
                    {{ $booking->check_in?->format('d M Y') }}
                    -
                    {{ $booking->check_out?->format('d M Y') }}
                </p>

            </td>

            <td class="text-center">
                {{ $booking->total_nights }}
            </td>

            <td class="text-right">
                ৳{{ number_format($booking->room_price, 2) }}
            </td>

            <td class="text-right">
                ৳{{ number_format($booking->subtotal, 2) }}
            </td>

        </tr>

    </tbody>

</table>


{{-- Totals --}}

<div class="totals">

    <div class="total-row">

        <span class="total-label">
            Subtotal
        </span>

        <span class="total-value">
            ৳{{ number_format($booking->subtotal, 2) }}
        </span>

        <div class="clearfix"></div>

    </div>


    <div class="total-row">

        <span class="total-label">
            Discount
        </span>

        <span class="total-value success">
            - ৳{{ number_format($booking->discount ?? 0, 2) }}
        </span>

        <div class="clearfix"></div>

    </div>


    <div class="total-row">

        <span class="total-label">
            Tax
        </span>

        <span class="total-value">
            ৳{{ number_format($booking->tax ?? 0, 2) }}
        </span>

        <div class="clearfix"></div>

    </div>


    <div class="total-row grand-total">

        <span class="total-label">
            Total
        </span>

        <span class="total-value">
            ৳{{ number_format($booking->total_amount, 2) }}
        </span>

        <div class="clearfix"></div>

    </div>

</div>


{{-- Payment / Earnings --}}

<div class="line"></div>

<div class="box-row">

    <div class="box">

        <h3>
            Payment Information
        </h3>

        <p>
            Status:

            <strong>
                {{ ucfirst($booking->payment_status) }}
            </strong>
        </p>

    </div>


    <div class="box right">

        <h3>
            Vendor Earnings
        </h3>

        <p>
            Admin Commission:
            ৳{{ number_format($booking->admin_commission ?? 0, 2) }}
        </p>

        <p class="success">

            <strong>
                Vendor Earning:
                ৳{{ number_format($booking->vendor_earning ?? 0, 2) }}
            </strong>

        </p>

    </div>

    <div class="clearfix"></div>

</div>


<div class="footer">

    <p>
        Thank you for choosing our service.
    </p>

    <p>
        This is a computer-generated invoice and does not require a signature.
    </p>

</div>

</body>

</html>