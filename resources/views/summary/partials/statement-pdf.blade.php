<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOA - {{ \Carbon\Carbon::parse($filterMonth)->format('F Y') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logofavicon/favicon.ico') }}">

    <style>
        * { box-sizing: border-box; }

        @page { margin: 8mm; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9.8px;
            color: #111;
            line-height: 1.3;
        }

        table { width: 100%; border-collapse: collapse; }

        .soa-container td {
            width: 50%;
            vertical-align: top;
            padding: 4px;
        }

        .soa {
            border: 2px solid #1e40af;
            padding: 10px 12px;
            min-height: 260px;
            page-break-inside: avoid;
            background: #fff;
        }

        /* HEADER */
        .header {
            border-bottom: 2px solid #1e40af;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .school-name {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
        }

        .title {
            text-align: center;
            font-size: 11.5px;
            font-weight: bold;
            color: #1e40af;
            margin: 8px 0;
        }

        /* STUDENT INFO */
        .info-table td {
            padding: 2px 0;
            font-size: 9.5px;
        }

        .label {
            width: 90px;
            font-weight: bold;
            color: #1e40af;
        }

        /* PARTICULARS TABLE */
        .particulars-table {
            width: 100%;
            margin-top: 8px;
        }

        .particulars-table th {
            background: #eef2ff;
            color: #1e40af;
            padding: 5px 6px;
            font-size: 9px;
            text-align: left;
            border-bottom: 2px solid #1e40af;
        }

        .particulars-table td {
            padding: 4px 6px;
            font-size: 9.2px;
            border-bottom: 1px solid #e5e7eb;
        }

        .amount {
            text-align: right;
            font-weight: 600;
        }

        /* TOTAL */
        .total-section {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 2px solid #1e40af;
            text-align: right;
        }

        .total-balance {
            font-size: 13px;
            font-weight: bold;
            color: #b91c1c;
        }

        .signature {
            margin-top: 20px;
            text-align: center;
        }

        .signature-line {
            width: 160px;
            border-top: 1px solid #333;
            margin: 0 auto 4px;
        }

        .disclaimer {
            margin-top: 15px;
            text-align: center;
            font-size: 8.5px;
            color: #555;
            font-style: italic;
        }
    </style>
</head>
<body>

<table class="soa-container">
    @php
        $chunks = array_chunk($studentsData ?? [], 2);
    @endphp

    @foreach ($chunks as $row)
        <tr>
            @foreach ($row as $data)
                <td>
                    <div class="soa">

                        <!-- HEADER -->
                        <div class="header">
                            <table style="width:100%">
                                <tr>
                                    <td style="width:50px;">
                                        <img src="{{ public_path('storage/shieldfavicon/shield3.png') }}" width="42" height="42" alt="Logo">
                                    </td>
                                    <td>
                                        <div class="school-name">CAPELLAN INSTITUTE OF TECHNOLOGY</div>
                                        <div style="font-size:8.5px; color:#444;">
                                            2nd Flr. Golden Bldg., L. Coscio Avenue Brgy 1-A San Pablo City Laguna
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="title">STATEMENT OF ACCOUNT</div>
                        </div>

                        <!-- STUDENT DETAILS -->
                        <table class="info-table">
                            <tr>
                                <td class="label">Name:</td>
                                <td>{{ $data['student']->complete_name ?? ($data['student']->last_name . ', ' . $data['student']->first_name) }}</td>
                            </tr>
                            <tr>
                                <td class="label">Student No:</td>
                                <td>{{ $data['student']->student_id ?? $data['student']->student_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Grade & Section:</td>
                                <td>{{ $data['student']->grade_level ?? $data['student']->year_level ?? 'N/A' }} - {{ $data['student']->section ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">School Year:</td>
                                <td>{{ $data['student']->school_year ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">As of:</td>
                                <td>{{ \Carbon\Carbon::parse($filterMonth)->format('F Y') }}</td>
                            </tr>
                        </table>

                        <!-- PAYABLES TABLE - Updated as requested -->
                        <table class="particulars-table">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th style="text-align:right">Amount</th>
                                    <th style="text-align:right">Penalty</th>
                                    <th style="text-align:right">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($data['payables'] ?? [] as $item)
                                <tr>
                                    <td>{{ $item['payable_name'] }}</td>
                                    <td class="amount">{{ number_format($item['amount'] ?? 0, 2) }}</td>
                                    <td class="amount">{{ number_format($item['penalty_amount'] ?? 0, 2) }}</td>
                                    <td class="amount" style="color:#b91c1c; font-weight:bold;">
                                        {{ number_format($item['total_amount'] ?? 0, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center; padding:15px;">
                                        No outstanding payables as of this month.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- TOTAL -->
                        <div class="total-section">
                            <div class="total-balance">
                                TOTAL: Php {{ number_format($data['total_balance'] ?? 0, 2) }}
                            </div>
                        </div>

                        <!-- SIGNATURE -->
                        <div class="signature">
                            <div class="signature-line"></div>
                            <small>{{ $preparedBy ?? 'Cashier / Registrar' }}</small>
                            <br>
                            <small>Cashier / Registrar</small>
                        </div>

                        <!-- DISCLAIMER MESSAGE -->
                        <div class="disclaimer">
                            Please disregard this statement if payment has already been made.
                        </div>

                    </div>
                </td>
            @endforeach>

            @if (count($row) === 1)
                <td></td>
            @endif
        </tr>
    @endforeach
</table>

</body>
</html>
