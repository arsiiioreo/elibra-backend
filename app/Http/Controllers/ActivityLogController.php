<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request) {

        $activities = ActivityLog::where('user_id', auth('api')->user()->id)
            ->when($request->date, function ($q, $date) {
                $q->where('created_at', 'like', "%$date%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($activities);
    }
}
