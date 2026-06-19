<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgement Receipt</title>

    <style>
        body {
            font-family: 'Arial', sans-serif !important;
        }

        * {
            font-family: 'Arial', sans-serif !important;
        }
        
        @page {
            size: 58mm auto;
            margin: 1mm;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 14.5px;           /* Overall smaller */
            line-height: 1.18;
            font-weight: 900;
            color: #000;
        }

        .receipt {
            width: 100%;
            max-width: 58mm;
            margin: 0 auto;
            padding: 0;
            box-sizing: border-box;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: 900;
        }

        .divider {
            border-top: 3px solid #000;
            margin: 3mm 0;
        }

        .store-name {
            font-size: 15.5px;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        .logo {
            width: 18mm;
            height: auto;
            display: block;
            margin: 0 auto 1mm;
            filter: grayscale(1) brightness(0%);
            -webkit-filter: grayscale(1) brightness(0%);
        }

        .receipt,
        .receipt * {
            color: #000 !important;
        }

        .store-info {
            font-size: 10.5px;
            line-height: 1.25;
            margin-top: 1.2mm;
            margin-bottom: 1mm;
            font-weight: 800;
        }

        .title {
            font-size: 15.5px;
            font-weight: 900;
            margin: 3mm 0 2mm;
            text-transform: uppercase;
        }

        /* META SECTION - MADE SMALLER */
        .meta td {
            vertical-align: top;
            padding: 0.9mm 0;
            font-size: 11.8px;           /* Smaller meta text */
            font-weight: 900;
        }

        .meta td.label {
            width: 38%;
            padding-right: 2mm;
        }

        .meta td.value {
            width: 62%;
            text-align: right;
            word-break: break-word;
        }

        .section-label {
            font-size: 11.5px;
            font-weight: 900;
            margin: 1mm 0 1.5mm;
            text-transform: uppercase;
        }

        .items thead th {
            font-size: 12px;
            font-weight: 900;
            padding: 1.5mm 0.5mm;
            border-bottom: 3px solid #000;
            text-transform: uppercase;
        }

        .items tbody td {
            padding: 1.7mm 0.5mm;
            font-size: 12px;
            font-weight: 900;
        }

        .items .desc {
            width: 48%;
            word-break: break-word;
        }

        .items .qty {
            width: 12%;
            text-align: center;
        }

        .items .amount {
            width: 40%;
            text-align: right;
            white-space: nowrap;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 0.5mm 0;
        }

        .summary-row.total {
            font-size: 13.5px;
            font-weight: 900;
            margin-top: 2.5mm;
            padding-top: 2mm;
            border-top: 3px solid #000;
        }

        .footer {
            margin-top: 4mm;
            text-align: center;
            font-size: 10.5px;
            line-height: 1.3;
            font-weight: 800;
        }

        .signature-line {
            width: 46mm;
            margin: 5mm auto 2mm;
            border-top: 1.5px solid #000;
        }

        @media print {
            html, body {
                width: 58mm;
                background: #fff;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="center">
            <img src="file://{{ str_replace('\\', '/', storage_path('app/public/logofavicon/logo3.png')) }}"
                 alt="CIT Logo" class="logo">
        </div>

        <div class="center store-name">Capellan Institute of Technology</div>

        <div class="center store-info">
            2nd Flr. Golden Bldg., L. Cosico Ave.<br>
            Brgy. 1-A, San Pablo City, Laguna<br>
            Mobile: 0912 820 0541<br>
            Email: capellan.spc@gmail.com
        </div>

        <div class="divider"></div>

        <div class="center title">Acknowledgement Receipt</div>

        <table class="meta">
            <tr>
                <td class="label">No.</td>
                <td class="value bold">{{ $transaction->transaction_code ?? str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="label">Date / Time</td>
                <td class="value">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y g:i A') }}</td>
            </tr>
            <tr>
                <td class="label">Student</td>
                <td class="value bold">{{ $transaction->student_name ?? ($transaction->student->complete_name ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td class="label">Grade / Sec</td>
                <td class="value">
                    @if ($transaction->student)
                        {{ $transaction->student->year_level }}{{ $transaction->student->section ? ' - ' . $transaction->student->section : '' }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Cashier</td>
                <td class="value">{{ $transaction->recorded_by ?? $transaction->created_by?->name ?? 'System' }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="section-label">Particulars</div>

        <table class="items">
            <thead>
                <tr>
                    <th class="desc">Item</th>
                    <th class="qty">Qty</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $payables = is_string($transaction->payables)
                        ? json_decode($transaction->payables, true)
                        : $transaction->payables;
                @endphp

                @forelse ($payables as $payable)
                    <tr>
                        <td class="desc">{{ $payable['payable_name'] ?? 'Unknown Item' }}</td>
                        <td class="qty">{{ $payable['quantity'] ?? 1 }}</td>
                        <td class="amount">Php {{ number_format($payable['total'] ?? $payable['amount'] ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="desc" colspan="3">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-row total">
                <span class="label">TOTAL</span>
                <span>Php {{ number_format($transaction->total_amount, 2) }}</span>
            </div>
        </div>

        <br>

        <div class="signature-line"></div>
        <div class="footer">
            Cashier / Authorized Personnel<br>
            Thank you for your payment.
        </div>
    </div>
</body>
</html>
