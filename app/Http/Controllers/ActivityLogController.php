<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $isStudentOrParent = $user->isStudent() || $user->isParentGuardian();

        if (!$user->isDeveloper() && !$isStudentOrParent) {
            abort(403, 'Unauthorized. Only developers and authorized accounts can view activity logs.');
        }

        $query = ActivityLog::with('user');
        if ($isStudentOrParent) {
            $query->where('user_id', $user->id);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('activity-logs.index', compact('logs'));
    }

    public function print(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $isStudentOrParent = $user->isStudent() || $user->isParentGuardian();

        if (!$user->isDeveloper() && !$isStudentOrParent) {
            abort(403, 'Unauthorized. Only developers and authorized accounts can print activity logs.');
        }

        $query = ActivityLog::with('user');
        if ($isStudentOrParent) {
            $query->where('user_id', $user->id);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return view('activity-logs.print', compact('logs'));
    }
}
