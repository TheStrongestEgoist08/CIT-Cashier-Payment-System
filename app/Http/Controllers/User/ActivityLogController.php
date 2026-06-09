<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(100)->withQueryString();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('activities.index', compact('logs', 'users'));
    }

    public function printPdf(Request $request)
    {
        $logs = $this->getFilteredLogs($request);

        $pdf = Pdf::loadView('activities.partials.pdf', compact('logs'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('activity-logs-' . now()->format('Y-m-d-H-i-s') . '.pdf');
    }

    public function exportPdf(Request $request)
    {
        $logs = $this->getFilteredLogs($request);

        $pdf = Pdf::loadView('activities.partials.pdf', compact('logs'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->download('activity-logs-' . now()->format('Y-m-d-H-i-s') . '.pdf');
    }

    private function getFilteredLogs(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query->get();
    }
}
