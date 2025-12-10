<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Branch;
use App\Models\Patron;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceLogController extends Controller
{
    public function logs(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
        ]);

        $section = Section::with('branch')->findOrFail($request->section_id);
        $branch = $section->branch;

        $opening = Carbon::parse($branch->opening_hour);
        $closing = Carbon::parse($branch->closing_hour);

        if ($closing->lessThan($opening)) {
            $closing->addDay(); // handle overnight branches
        }

        $logs = AttendanceLog::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as total')
        )
            ->where('section_id', $section->id)
            ->whereDate('created_at', Carbon::today())
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->get()
            ->keyBy('hour');

        $hours = [];
        for ($time = $opening->copy(); $time->lt($closing); $time->addHour()) {
            $hour = $time->hour;
            $hours[] = [
                'hour' => $time->format('g A'),
                'total' => $logs[$hour]->total ?? 0,
            ];
        }

        return response()->json($hours);
    }

    public function record(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|exists:patrons,id_number',
            'section_id' => 'required|exists:sections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // ✅ Source of truth: SECTION → BRANCH
        $section = Section::with('branch')->findOrFail($request->section_id);
        $branch = $section->branch;

        $now = Carbon::now();
        $opening = Carbon::parse($branch->opening_hour);
        $closing = Carbon::parse($branch->closing_hour);

        // ✅ Handle overnight branches
        if ($closing->lessThan($opening)) {
            $closing->addDay();
        }

        if (! $now->between($opening, $closing)) {
            return response()->json([
                'status' => false,
                'message' => $now->lt($opening)
                    ? 'Library is not open yet.'
                    : 'Library is already closed.',
            ]);
        }

        $patron = Patron::with([
            'user' => fn ($q) => $q->withTrashed(),
            'program.department',
            'patron_type',
        ])
            ->where('id_number', $request->id_number)
            ->first();

        if (! $patron) {
            return response()->json([
                'status' => false,
                'message' => 'Patron not found.',
            ]);
        }

        // ✅ RECORD USING SECTION_ID
        $log = AttendanceLog::create([
            'patron_id' => $patron->id,
            'section_id' => $section->id,
            'status' => 'in',
        ]);

        $log->load(['patron.user', 'patron.program.department', 'patron.patron_type']);
        $log->patron->user->profile_picture ??= asset('logo.png');

        return response()->json([
            'status' => true,
            'data' => $log,
        ]);
    }
}
