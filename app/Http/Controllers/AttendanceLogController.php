<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Branch;
use App\Models\Patron;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceLogController extends Controller
{
    public function logs(Request $request)
    {
        // 🧾 Validate branch_id
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        // 🏫 Fetch branch details (to get opening & closing hours)
        $branch = Branch::findOrFail($request->branch_id);

        // Convert opening and closing hours to Carbon
        $opening = Carbon::parse($branch->opening_hour);
        $closing = Carbon::parse($branch->closing_hour);

        // 🕓 Get attendance logs grouped by hour for today
        $logs = AttendanceLog::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as total')
        )
            ->where('branches_id', $branch->id)
            ->whereDate('created_at', Carbon::today())
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->get()
            ->keyBy('hour'); // Key by hour for fast lookup later

        // 🧮 Generate full hourly range from opening → closing
        $hours = [];
        for ($time = $opening->copy(); $time->lte($closing); $time->addHour()) {
            $hour = $time->hour; // integer (e.g. 8, 9, 10)
            $hours[] = [
                'hour' => $time->format('g A'), // e.g. "8 AM"
                'total' => isset($logs[$hour]) ? $logs[$hour]->total : 0,
            ];
        }

        return response()->json($hours);
    }

    public function record(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|exists:patrons,id_number',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $branch = Branch::find($request->branch_id);

        if ($branch) {
            $now = Carbon::now();

            // Check if CURRENT TIME is OUTSIDE the open-close interval
            if (! ($now->between($branch->opening_hour, $branch->closing_hour))) {

                // If now is BEFORE opening
                if ($now->lt($branch->opening_hour)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Library is not open yet.',
                    ]);
                }

                // Else, AFTER closing
                return response()->json([
                    'status' => false,
                    'message' => 'Library is already closed.',
                ]);
            }
        }

        $patron = Patron::with(['user' => fn ($q) => $q->withTrashed()])->where('id_number', $request->id_number)->first();

        if ($patron) {
            $log = AttendanceLog::create([
                'patron_id' => $patron->id,
                'branches_id' => $request->branch_id,
                'status' => 'in',
                // 'created_at' => Carbon::today()->setHour(rand(9, 16))->setMinute(rand(0, 59))->setSecond(rand(0, 59)),
            ]);
            $log->load(['patron.user', 'patron.program.department', 'patron.patron_type']);
            $log->patron->user->profile_picture = $log->patron->user->profile_picture ?? asset('logo.png');

            return response()->json([
                'status' => true,
                'data' => $log,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Patron not found.',
            ]);
        }
    }
}
