<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Activity Logs Report</title>
    <style>
        @page { margin: 12mm 10mm 15mm 10mm; }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11.5px;
            line-height: 1.4;
        }
        .report {
            border: 3px solid #1e40af;
            padding: 25px 30px;
            background: white;
        }
        table { width: 100%; border-collapse: collapse; }
        .header-table { border-bottom: 3px solid #1e40af; margin-bottom: 20px; }
        .logo img { width: 67px; height: 67px; object-fit: contain; }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1e40af;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin: 20px 0 25px 0;
        }
        .info-table td { padding: 5px 8px; }
        .label { font-weight: bold; color: #1e40af; width: 130px; }

        .logs-table th {
            background: #f0f4ff;
            color: #1e40af;
            padding: 10px 8px;
            border-bottom: 2px solid #1e40af;
            text-align: left;
            font-size: 12px;
        }
        .logs-table td {
            padding: 9px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .action-created { color: #166534; }
        .action-updated { color: #1e40af; }
        .action-deleted { color: #991b1b; }
        .action-viewed   { color: #6b7280; }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="report">

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 80px; text-align: center;">
                <div class="logo">
                    <img src="{{ public_path('storage/shieldfavicon/shield3.png') }}" width="55" height="55" alt="Logo">
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

    <div class="title">ACTIVITY LOGS REPORT</div>

    <!-- Filter Info -->
    <table class="info-table" style="margin-bottom: 18px;">
        <tr>
            <td class="label">Generated On:</td>
            <td>{{ now()->format('F j, Y - g:i A') }}</td>
        </tr>
        @if(request('date_from') || request('date_to'))
        <tr>
            <td class="label">Date Range:</td>
            <td>
                {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'Start' }}
                to
                {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'Present' }}
            </td>
        </tr>
        @endif
        @if(request('user_id'))
        <tr>
            <td class="label">User:</td>
            <td>{{ \App\Models\User::find(request('user_id'))?->name ?? 'All Users' }}</td>
        </tr>
        @endif
    </table>

    <!-- Logs Table -->
    <table class="logs-table">
        <thead>
            <tr>
                <th style="width: 18%">User</th>
                <th style="width: 12%">Action</th>
                <th style="width: 28%">Description</th>
                <th style="width: 17%">Date & Time</th>
                <th style="width: 10%">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->user?->name ?? 'System' }}</td>
                    <td>
                        <span class="action-{{ $log->action }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 30px;">No activity logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Prepared By: <strong>System Administrator</strong>
    </div>

</div>

</body>
</html>
