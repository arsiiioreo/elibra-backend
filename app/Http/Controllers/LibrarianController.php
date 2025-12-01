<?php

namespace App\Http\Controllers;

use App\Models\Patron;
use Exception;

class LibrarianController extends Controller
{
    public function dashboard()
    {
        try {
            $me = auth('api')->user();
            $campusId = $me->librarian->section->branch->campus->id;

            $data = [
                'status_cards' => [
                    [
                        'title' => 'Total Students', 'value' => [
                            'old' => Patron::whereHas('program.department.campus', function ($q) use ($campusId) {
                                $q->where('id', $campusId);
                            })
                                ->whereDate('created_at', '<=', now()->subDay())
                                ->count(),
                            'new' => Patron::whereHas('program.department.campus', function ($q) use ($campusId) {
                                $q->where('id', $campusId);
                            })
                                ->whereDate('created_at', '<=', today())
                                ->count(),
                        ],
                    ],
                    ['title' => 'Total Faculty', 'value' => ['old' => '2', 'new' => '4']],
                    ['title' => 'Total Books', 'value' => ['old' => '1500', 'new' => '1800']],
                    ['title' => 'Books Issued', 'value' => ['old' => '300', 'new' => '450']],
                ],
            ];

            return response()->json(['data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
