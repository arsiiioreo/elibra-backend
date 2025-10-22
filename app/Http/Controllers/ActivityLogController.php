<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index() {
        $activities = ActivityLog::where('user_id', auth('api')->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($activities);
    }
}
