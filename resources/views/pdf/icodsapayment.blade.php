<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body { font-family: sans-serif; }
        h1 { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>PAYMENT RECEIPT</h1>

    <div class="section">
        <strong>Date of Issue:</strong> {{ $payment->payment_date }}<br>
        <strong>Invoice No:</strong> {{ $payment->invoice_no }}
    </div>
    <div class="section">
        <strong>Paper ID:</strong> {{ $payment->paper_id ?? '-' }}<br>
        <strong>Paper Title:</strong> {{ $payment->paper_title ?? '-' }}
    </div>
    <div class="section">
        <strong>Received From:</strong> {{ $payment->received_from ?? '-' }}<br>
        <strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}<br>
        <strong>In Payment of:</strong> {{ $payment->in_payment_of ?? '-' }}<br>
        <strong>Payment Date:</strong> {{ $payment->payment_date ?? '-' }}
    </div>
    <div class="section">
        <strong>Signature:</strong> {{ $payment->signature->picture }}<br>
        <strong>Nama Penandatangan:</strong> {{ $payment->signature->nama_penandatangan }}<br>
        <strong>Jabatan:</strong> {{ $payment->signature->jabatan_penandatangan }}<br>
    </div>
</body>
</html>
