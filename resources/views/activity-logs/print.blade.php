<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Log Report - {{ config('app.name', 'Laravel') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 20px;
            font-size: 13px;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #ccc;
        }
        .badge-registration { background-color: #e6f4ea; color: #137333; border-color: #c2e7c9; }
        .badge-login { background-color: #e8f0fe; color: #1a73e8; border-color: #c2e7c9; }
        .badge-logout { background-color: #f1f3f4; color: #5f6368; border-color: #dadce0; }
        .badge-profile { background-color: #f3e8fd; color: #9c27b0; border-color: #e8d0fc; }
        .badge-deletion { background-color: #fce8e6; color: #c5221f; border-color: #fad2cf; }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }
        @media print {
            body {
                margin: 0;
                font-size: 12px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="float: right; text-align: right;">
            <button onclick="window.print()" class="no-print" style="padding: 6px 12px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-bottom: 10px;">Print Page</button>
            <p>Report Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
        <h1>Activity Log Report</h1>
        <p>System Administrator Access Only</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>User Details</th>
                <th>Action</th>
                <th>Description</th>
                <th>IP Address</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>
                        <strong>{{ $log->user_name ?? 'Deleted User' }}</strong><br>
                        <span style="font-size: 11px; color: #666;">{{ $log->user_email ?? 'N/A' }}</span>
                    </td>
                    <td>
                        @php
                            $badgeClass = match ($log->action) {
                                'Registration' => 'badge-registration',
                                'Login' => 'badge-login',
                                'Logout' => 'badge-logout',
                                'Profile Updated' => 'badge-profile',
                                'Account Deletion' => 'badge-deletion',
                                default => ''
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $log->action }}</span>
                    </td>
                    <td>{{ $log->description }}</td>
                    <td style="font-family: monospace;">{{ $log->ip_address ?? 'N/A' }}</td>
                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No activity logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Confidential Document &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
