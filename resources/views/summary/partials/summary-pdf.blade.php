<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Summary of Account</title>
    <style>
        @page { margin: 12mm 10mm 15mm 10mm; }

        body {
            font-family: 'Arial', sans-serif !important;
            font-size: 12px;
            line-height: 1.4;
        }
        .receipt {
            border: 3px solid #1e40af;
            padding: 25px 30px;
            background: white;
        }
        table { width: 100%; border-collapse: collapse; }
        .header-table { border-bottom: 3px solid #1e40af; margin-bottom: 16px; }
        .logo img { width: 67px; height: 67px; object-fit: contain; }
        .company-name {
            font-size: 23px;
            font-weight: bold;
            color: #1e40af;
        }
        .title {
            text-align: center;
            font-size: 19px;
            font-weight: bold;
            color: #1e40af;
            margin: 18px 0 22px 0;
        }
        .details-table td { padding: 4px 8px; vertical-align: top; }
        .label { font-weight: bold; color: #1e40af; width: 140px; }

        .particulars th {
            background: #f0f4ff;
            color: #1e40af;
            padding: 8px 6px;
            border-bottom: 2px solid #1e40af;
            font-size: 12px;
        }
        .particulars td {
            padding: 6.5px 6px;
            border-bottom: 1px solid #e2e8f0;
        }

        .paid-row td    { color: #166534; }
        .unpaid-row td  { color: #991b1b; font-weight: 500; }
        .exempted-row td { color: #854d0e; font-style: italic; }

        .section-header {
            background: #f0f4ff;
            padding: 8px 10px;
            font-weight: bold;
            color: #1e40af;
            margin: 20px 0 8px 0;
            font-size: 13.5px;
        }
        .subtotal-paid {
            background: #ecfdf5 !important;
            color: #166534;
            font-weight: bold;
        }
        .subtotal-unpaid {
            background: #fef2f2 !important;
            color: #991b1b;
            font-weight: bold;
        }
        .subtotal-exempted {
            background: #fefce8 !important;
            color: #854d0e;
            font-weight: bold;
        }
        .grand-subtotal {
            background: #f8fafc;
            font-weight: bold;
            border-top: 2px solid #1e40af;
            font-size: 13px;
        }
    </style>
</head>
<body>

@foreach ($soas as $soa)
    @php
        $student = $soa['student'];
        $groupedData = $soa['groupedData'] ?? [];
        $generatedAt = $soa['generatedAt'] ?? now();
        $preparedBy = $soa['preparedBy'] ?? 'Cashier / Registrar';
    @endphp

    <div class="receipt">

        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="width: 70px; text-align: center;">
                    <div class="logo">
                        <img src="{{ public_path('storage/shieldfavicon/shield3.png') }}" width="42" height="42" alt="Logo">
                    </div>
                </td>
                <td>
                    <div class="company-name">CAPELLAN INSTITUTE OF TECHNOLOGY</div>
                    <div style="font-size: 10.5px; line-height: 1.4;">
                        2nd Flr. Goden Bldg., L. Coscio Avenue Brgy 1-A San Pablo City Laguna<br>
                        Mobile: 0912 820 0541 | Landline: (049) 501-1468 | Email: capellan.spc@gmail.com
                    </div>
                </td>
            </tr>
        </table>

        <div class="title">SUMMARY OF ACCOUNT</div>

        <!-- Student Details -->
        <table class="details-table">
            <tr>
                <td class="label">Student Name:</td>
                <td>{{ $student->complete_name ?? ($student->last_name . ', ' . $student->first_name . ' ' . ($student->middle_name ?? '')) }}</td>
                <td class="label">Student No:</td>
                <td style="text-align:right">{{ $student->student_id ?? $student->student_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Grade / Section:</td>
                <td>{{ $student->grade_level ?? $student->year_level ?? 'N/A' }} - {{ $student->section ?? 'N/A' }}</td>
                <td class="label">Date Generated:</td>
                <td style="text-align:right">{{ $generatedAt->format('F j, Y - g:i A') }}</td>
            </tr>
        </table>

        <!-- Grouped Payables -->
        @foreach ($groupedData as $gradeLevel => $years)
            @foreach ($years as $schoolYear => $data)

                <div class="section-header">
                    {{ $gradeLevel }} — S.Y {{ $schoolYear }}
                </div>

                <div class="particulars">
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align:left">Particulars</th>
                                <th style="text-align:center">OR</th>
                                <th style="text-align:right">Amount</th>
                                <th style="text-align:right">Penalty</th>
                                <th style="text-align:right">Paid Amount</th>
                                <th style="text-align:right">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Paid Items -->
                            @foreach ($data['paid'] as $item)
                                <tr class="paid-row">
                                    <td>{{ $item['payable_name'] }}</td>
                                    <td style="text-align:center">{{ $item['OR'] }}</td>
                                    <td style="text-align:right">{{ number_format($item['amount'], 2) }}</td>
                                    <td style="text-align:right">{{ number_format($item['penalty_amount'], 2) }}</td>
                                    <td style="text-align:right">{{ number_format($item['paid_amount'], 2) }}</td>
                                    <td style="text-align:center">{{ number_format($item['total_amount'], 2) }}</td>
                                </tr>
                            @endforeach

                            @if (count($data['paid']) > 0)
                            <tr class="subtotal-paid">
                                <td colspan="5" style="text-align:right">TOTAL</td>
                                <td style="text-align:right">Php {{ number_format($data['paidTotal'], 2) }}</td>
                            </tr>
                            @endif

                            <!-- Unpaid Items -->
                            @foreach ($data['unpaid'] as $item)
                                <tr class="unpaid-row">
                                    <td>{{ $item['payable_name'] }}</td>
                                    <td style="text-align:center">{{ $item['OR'] }}</td>
                                    <td style="text-align:right">{{ number_format($item['amount'], 2) }}</td>
                                    <td style="text-align:right">{{ number_format($item['penalty_amount'], 2) }}</td>
                                    <td style="text-align:right">{{ number_format($item['paid_amount'], 2) }}</td>
                                    <td style="text-align:center">{{ number_format($item['total_amount'], 2) }}</td>
                                </tr>
                            @endforeach

                            @if (count($data['unpaid']) > 0)
                            <tr class="subtotal-unpaid">
                                <td colspan="5" style="text-align:right">TOTAL</td>
                                <td style="text-align:right">Php {{ number_format($data['unpaidTotal'], 2) }}</td>
                            </tr>
                            @endif

                            <!-- Exempted Items -->
                            @foreach ($data['exempted'] as $item)
                                <tr class="exempted-row">
                                    <td>{{ $item['payable_name'] }}</td>
                                    <td style="text-align:center">{{ $item['OR'] }}</td>
                                    <td style="text-align:right">{{ number_format($item['amount'], 2) }}</td>
                                    <td style="text-align:right">{{ number_format($item['penalty_amount'], 2) }}</td>
                                    <td style="text-align:right">{{ number_format($item['paid_amount'], 2) }}</td>
                                    <td style="text-align:center">{{ $item['remarks'] }}</td>
                                </tr>
                            @endforeach

                            @if (count($data['exempted']) > 0)
                            <tr class="subtotal-exempted">
                                <td colspan="5" style="text-align:right">TOTAL</td>
                                <td style="text-align:right">Php {{ number_format($data['exemptedTotal'], 2) }}</td>
                            </tr>
                            @endif

                            <!-- GRAND TOTAL -->
                            @php
                                $grandTotal = $data['paidTotal'] + $data['unpaidTotal'] + $data['exemptedTotal'];
                            @endphp
                            @if ($grandTotal > 0)
                            <tr class="grand-subtotal">
                                <td colspan="5" style="text-align:right">GRAND TOTAL</td>
                                <td style="text-align:right">Php {{ number_format($grandTotal, 2) }}</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>

            @endforeach
        @endforeach

        <div style="margin-top: 30px; text-align: right; font-size: 15px; font-weight: bold;">
            Prepared By: <strong>{{ $preparedBy }}</strong>
        </div>

    </div>

    @if (!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif

@endforeach

</body>
</html>
