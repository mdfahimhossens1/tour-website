<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial; }
        .box { padding: 20px; border: 1px solid #ddd; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>

<div class="box">

    <h2>INVOICE</h2>

    <p><strong>Transaction ID:</strong> {{ $transaction->transaction_id }}</p>
    <p><strong>User:</strong> {{ $transaction->user->name ?? '' }}</p>
    <p><strong>Booking ID:</strong> #{{ $transaction->booking_id }}</p>
    <p><strong>Status:</strong> {{ strtoupper($transaction->status) }}</p>

    <table>
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>

        <tr>
            <td>Tour Booking Payment</td>
            <td>{{ $transaction->amount }}</td>
        </tr>

    </table>

    <h3 style="text-align:right;">
        Total: {{ $transaction->amount }}
    </h3>

</div>

</body>
</html>