<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    public function index(Request $request)
    {

        $attendance_logs = AttendanceLog::where('branches_id', $request->branch_id)->get();
        $attendance_logs->load(['branch.campus', 'patron.user']);

        return response()->json(["data" => $attendance_logs]);
    }
}
