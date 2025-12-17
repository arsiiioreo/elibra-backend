<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Exception;

class SectionController extends Controller
{
    public function index($branch)
    {
        try {
            $sections = Section::where('branch_id', $branch)->get();

            return response()->json(['status' => 'success', 'data' => $sections]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function sections()
    {
        try {
            $branchId = auth('api')->user()->librarian->section->branch->id;
            $sections = Section::where('branch_id', $branchId)->get();

            return response()->json([
                'status' => 'success',
                'data' => $sections,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
