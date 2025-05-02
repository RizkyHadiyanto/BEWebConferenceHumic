<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body { font-family: sans-serif; }
        h1 { text-align: center; }
        .section { margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>INVOICE</h1>

    <div class="section">
        <strong>Date Issued:</strong> {{ $invoice->date_of_issue ?? '-' }}<br>
        <strong>Invoice No:</strong> {{ $invoice->invoice_no }}
    </div>
    <div class="section">
    </div>
    <div class="section">
        <strong>Author Name:</strong> {{ implode(', ', $invoice->loa->author_names ?? []) }}<br>
        <strong>Institution:</strong> {{ $invoice->institution ?? '-' }}<br>
        <strong>Email:</strong> {{ $invoice->email ?? '-' }}<br>
        <strong>Paper ID:</strong> {{ $invoice->loa->paper_id }}<br>
        <strong>Paper Title:</strong> {{ optional($invoice->loa)->paper_title ?? '-' }}
    </div>
    <div class="section">
        <strong>Description:</strong> {{ $invoice->member_type }}<br>
        <strong>Price:</strong> ${{ number_format($invoice->amount, 2) }}
    </div>
    <div class="section">
        <strong>Virtual Account Number:</strong> {{ $invoice->virtualAccount->nomor_virtual_akun }}<br>
        <strong>Account Holder Name:</strong> {{ $invoice->virtualAccount->account_holder_name }}<br>
        <strong>Bank Name:</strong> {{ $invoice->virtualAccount->bank_name }}<br>
        <strong>Bank Branch:</strong> {{ $invoice->virtualAccount->bank_branch }}
    </div>
    <div class="section">
        <strong>Bank Name:</strong> {{ $invoice->bankTransfer->nama_bank }}<br>
        <strong>Swift Code:</strong> {{ $invoice->bankTransfer->swift_code }}<br>
        <strong>Beneficiary name/Recipient Name :</strong> {{ $invoice->bankTransfer->recipient_name }}<br>
        <strong>Beneficiary Bank Account No.:</strong> {{ $invoice->bankTransfer->beneficiary_bank_account_no }}<br>
        <strong>Bank Branch:</strong> {{ $invoice->bankTransfer->bank_branch }}<br>
        <strong>Bank Address:</strong> {{ $invoice->bankTransfer->bank_address }}<br>
        <strong>City:</strong> {{ $invoice->bankTransfer->city }}<br>
        <strong>Country:</strong> {{ $invoice->bankTransfer->country }}
    </div>
    <div class="section">
        <strong>Signature:</strong> {{ $invoice->signature->picture }}<br>
        <strong>Nama Penandatangan:</strong> {{ $invoice->signature->nama_penandatangan }}<br>
        <strong>Jabatan:</strong> {{ $invoice->signature->jabatan_penandatangan }}<br>
    </div>
</body>
</html>
