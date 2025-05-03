<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ICICyTA 2024 Receipt</title>
    <style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
        }

        body {
        background-color: #f5f5f5;
        }

        .container {
        max-width: 800px;
        margin: 0 auto;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Header styles */
        header {
        background-color: #8257e6;
        color: white;
        padding: 2rem;
        }

        .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        }

        h1 {
        font-size: 2.5rem;
        font-weight: bold;
        }

        .logos {
        display: flex;
        gap: 15px;
        }

        .logo {
        height: 40px;
        }

        /* Main content styles */
        main {
        padding: 2rem;
        }

        h2 {
        text-align: center;
        font-size: 1.8rem;
        margin-bottom: 40px;
        font-weight: bold;
        }

        .receipt-header {
        display: flex;
        flex-direction: column;
        margin-bottom: 30px;
        }

        .issue-info {
        display: flex;
        justify-content: flex-end;
        }

        .issue-info > .info-table > tbody > tr > td:first-child {
        font-weight: bold;
        }

        .info-table td {
        padding: 5px;
        vertical-align: top;
        }

        .paper-info > .info-table > tbody > tr > td:nth-child(1),
        .paper-info > .info-table > tbody > tr > td:nth-child(2) {
        font-weight: bold;
        }

        .paper-info td:nth-child(3) {
        max-width: 400px;
        }

        /* Payment details styles */
        .payment-details {
        margin: 20px 0;
        }

        .payment-table {
        width: 100%;
        border-collapse: collapse;
        }

        .payment-table tr {
        border-bottom: 1px solid #ddd;
        }

        .payment-table td {
        padding: 15px 0;
        }

        .label {
        font-weight: bold;
        width: 150px;
        }

        .value {
        border-bottom: 1px solid #ddd;
        width: calc(100% - 150px);
        padding: 1rem !important;
        }

        .highlight {
        background-color: #d3def1;
        padding: 10px;
        }

        /* Amount box styles */
        .amount-box {
        background-color: #d3def1;
        display: inline-block;
        padding: 1rem 2rem;
        margin: 20px 0;
        font-weight: bold;
        font-size: 1.2rem;
        }

        /* Signature styles */
        .signature {
        text-align: right;
        margin-top: 80px;
        }

        .sign-container {
        display: inline-block;
        text-align: center;
        margin-top: 10px;
        }

        .sign-title {
        font-weight: bold;
        color: #6747b9;
        font-size: 1.3rem;
        margin-bottom: 10px;
        }

        .sign-name {
        text-decoration: underline;
        }

        .signature-image {
        width: 100px;
        height: 80px;
        margin: 0 auto 10px;
        background-image: url("signature.png");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        }

        /* Footer styles */
        footer {
        background-color: #8257e6;
        color: white;
        text-align: center;
        padding: 2rem;
        font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
        <div class="header-content">
            <h1>ICICyTA 2024</h1>
            <div class="logos">
            <img
                src="https://rb.gy/duo9um"
                alt="Telkom University"
                class="logo"
            />
            <img src="https://rb.gy/k8c62g" alt="UTM" class="logo" />
            <img src="https://rb.gy/c6tagb" alt="IEEE" class="logo" />
            </div>
        </div>
        </header>

        <main>
        <h2>CONFERENCE PAYMENT RECEIPT</h2>

        <div class="receipt-header">
            <div class="issue-info">
            <table class="info-table">
                <tr>
                <td>Date of Issue</td>
                <td>{{ $payment->payment_date }}</td>
                </tr>
                <tr>
                <td>Invoice No.</td>
                <td>{{ $payment->invoice_no }}</td>
                </tr>
            </table>
            </div>

            <div class="paper-info">
            <table class="info-table">
                <tr>
                <td>Paper ID</td>
                <td>:</td>
                <td>{{ $payment->paper_id ?? '-' }}</td>
                </tr>
                <tr>
                <td>Title</td>
                <td>:</td>
                <td>
                    {{ $payment->paper_title ?? '-' }}
                </td>
                </tr>
            </table>
            </div>
        </div>

        <div class="payment-details">
            <table class="payment-table">
            <tr>
                <td class="label">Received from</td>
                <td class="value">{{ $payment->received_from ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Amount</td>
                <td class="value highlight">{{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">In Payment of</td>
                <td class="value">{{ $payment->in_payment_of ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Payment Date</td>
                <td class="value">{{ $payment->payment_date ?? '-' }}</td>
            </tr>
            </table>
        </div>

        <div class="amount-box">
            <p>{{ number_format($payment->amount, 2) }}</p>
        </div>

        <div class="signature">
            <p>Bandung, Nov 01, 2024</p>
            <div class="sign-container">
            <p class="sign-title">ICICyTA</p>
            <div class="signature-image">{{ $payment->signature->picture }}</div>
            <p class="sign-name">{{ $payment->signature->nama_penandatangan }}</p>
            <p>{{ $payment->signature->jabatan_penandatangan }}</p>
            </div>
        </div>
        </main>

        <footer>
        <p>
            The 4<sup>th</sup> International Conference on Intelligent Cybernetics
            Technology and Applications 2024
        </p>
        </footer>
    </div>
</body>
</html>
