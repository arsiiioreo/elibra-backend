<?php

namespace App\Http\Controllers;

use App\Models\AcquisitionLine;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function acquisition_slip(Request $request)
    {
        if (! $request->input('acquisition_id')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing required information.',
            ], 400);
        }

        $id = $request->input('acquisition_id');
        $acquisition = AcquisitionLine::where('acquisition_id', $id)->with(['acquisition.accession', 'item.branch.campus'])->first();

        $path = public_path('images/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/'.$type.';base64,'.base64_encode($data);

        $unit_price = $acquisition->unit_price;
        $qty = $acquisition->quantity;
        $discount = $acquisition->discount;

        $data = [
            'campus' => $acquisition->item->branch->campus->name,
            'branch' => $acquisition->item->branch->name,
            'campus_address' => $acquisition->item->branch->campus->address,
            'transaction_id' => $acquisition->acquisition->purchaseId,
            'transaction_date' => $acquisition->acquisition->created_at->format('F d, Y - H:i D'),

            'title' => $acquisition->item->title,
            'call_number' => $acquisition->item->call_number,
            'acquisition_mode' => $acquisition->acquisition->acquisition_mode,
            'acquisition_date' => Carbon::parse($acquisition->acquisition->acquisition_date)->format('F d, Y'),
            'dealer' => $acquisition->acquisition->dealer,

            'unit_price' => $unit_price,
            'qty' => $qty,
            'discount' => $discount,
            'total_cost' => ($unit_price * $qty) - $discount,

            'recorded_by' => $acquisition->acquisition->receiver->user->full_name_normal,
            'recorder_information' => $acquisition->acquisition->receiver->user->roleText.', '.$acquisition->acquisition->receiver->section->name,

            'approved_by' => $acquisition->item->branch->branch_head,
            'approved_information' => 'Library Head',

            'accessions' => $acquisition->acquisition->accession,
        ];

        $pdf = Pdf::loadView('pdf.acquisition_slip', [
            'logo' => $logo,
            'data' => $data,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('acquisition_slip_'.$data['transaction_id'].'.pdf');
    }
}
