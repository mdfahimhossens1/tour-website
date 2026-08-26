<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Invoice - {{ $booking->booking_code }}
    </title>

    <style>

        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #222;
            margin: 0;
            background: #fff;
        }

        .invoice {
            max-width: 900px;
            margin: auto;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .right {
            text-align: right;
        }

        h1 {
            margin: 0;
            font-size: 30px;
        }

        h2 {
            margin: 0 0 5px;
        }

        h3 {
            margin-bottom: 8px;
        }

        .muted {
            color: #777;
        }

        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 25px 0;
        }

        .grid {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .box {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 6px;
        }

        .stay {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .stay-item {
            flex: 1;
            background: #f6f6f6;
            padding: 12px;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border-bottom: 1px solid #ddd;
            padding: 12px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            width: 350px;
            margin-left: auto;
            margin-top: 25px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }

        .grand {
            border-top: 2px solid #222;
            padding-top: 12px;
            font-size: 18px;
            font-weight: bold;
        }

        .success {
            color: #198754;
        }

        .footer {
            text-align: center;
            color: #777;
            border-top: 1px solid #ddd;
            margin-top: 50px;
            padding-top: 20px;
        }

        @media print {

            body {
                margin: 0;
            }

            .invoice {
                padding: 0;
            }

        }

    </style>

</head>


<body onload="window.print()">

<div class="invoice">

    <div class="header">

        <div>

            <h2>
                {{ $booking->vendor?->name ?? 'Vendor' }}
            </h2>

            <div class="muted">
                Resort Booking Invoice
            </div>

            @if($booking->vendor?->email)
                <div>
                    {{ $booking->vendor->email }}
                </div>
            @endif

            @if($booking->vendor?->phone)
                <div>
                    {{ $booking->vendor->phone }}
                </div>
            @endif

        </div>


        <div class="right">

            <h1>
                INVOICE
            </h1>

            <div>
                Invoice:
                <strong>
                    INV-{{ $booking->booking_code }}
                </strong>
            </div>

            <div>
                Booking:
                #{{ $booking->booking_code }}
            </div>

            <div>
                {{ $booking->created_at?->format('d M Y') }}
            </div>

        </div>

    </div>


    <hr>


    <div class="grid">

        <div class="box">

            <h3>
                Billed To
            </h3>

            <strong>
                {{ $booking->user?->name ?? 'N/A' }}
            </strong>

            @if($booking->user?->email)
                <div class="muted">
                    {{ $booking->user->email }}
                </div>
            @endif

            @if($booking->user?->phone)
                <div class="muted">
                    {{ $booking->user->phone }}
                </div>
            @endif

        </div>


        <div class="box">

            <h3>
                Accommodation
            </h3>

            <strong>
                {{ $booking->resort?->name ?? 'N/A' }}
            </strong>

            <div>
                Room:
                {{ $booking->room?->name ?? 'N/A' }}
            </div>

            @if($booking->room?->room_no)

                <div>
                    Room No:
                    {{ $booking->room->room_no }}
                </div>

            @endif

        </div>

    </div>


    <div class="stay">

        <div class="stay-item">

            <small class="muted">
                Check In
            </small>

            <strong>
                {{ $booking->check_in?->format('d M Y') }}
            </strong>

        </div>


        <div class="stay-item">

            <small class="muted">
                Check Out
            </small>

            <strong>
                {{ $booking->check_out?->format('d M Y') }}
            </strong>

        </div>


        <div class="stay-item">

            <small class="muted">
                Nights
            </small>

            <strong>
                {{ $booking->total_nights }}
            </strong>

        </div>


        <div class="stay-item">

            <small class="muted">
                Guests
            </small>

            <strong>
                {{ $booking->adults }} Adults,
                {{ $booking->children }} Children
            </strong>

        </div>

    </div>


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

                    <div class="muted">
                        {{ $booking->check_in?->format('d M Y') }}
                        -
                        {{ $booking->check_out?->format('d M Y') }}
                    </div>

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


    <div class="totals">

        <div class="total-row">

            <span>
                Subtotal
            </span>

            <strong>
                ৳{{ number_format($booking->subtotal, 2) }}
            </strong>

        </div>


        <div class="total-row">

            <span>
                Discount
            </span>

            <strong class="success">
                - ৳{{ number_format($booking->discount ?? 0, 2) }}
            </strong>

        </div>


        <div class="total-row">

            <span>
                Tax
            </span>

            <strong>
                ৳{{ number_format($booking->tax ?? 0, 2) }}
            </strong>

        </div>


        <div class="total-row grand">

            <span>
                Total
            </span>

            <strong>
                ৳{{ number_format($booking->total_amount, 2) }}
            </strong>

        </div>

    </div>


    <hr>


    <div class="grid">

        <div class="box">

            <h3>
                Payment
            </h3>

            Status:

            <strong>
                {{ ucfirst($booking->payment_status) }}
            </strong>

        </div>


        <div class="box">

            <h3>
                Vendor Earnings
            </h3>

            <div>
                Admin Commission:
                ৳{{ number_format($booking->admin_commission ?? 0, 2) }}
            </div>

            <strong class="success">

                Vendor Earning:
                ৳{{ number_format($booking->vendor_earning ?? 0, 2) }}

            </strong>

        </div>

    </div>


    <div class="footer">

        <div>
            Thank you for choosing our service.
        </div>

        <small>
            This is a computer-generated invoice and does not require a signature.
        </small>

    </div>

</div>

</body>

</html>