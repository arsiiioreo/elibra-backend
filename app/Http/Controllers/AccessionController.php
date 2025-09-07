<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessionRequest;
use App\Models\Accession;
use App\Models\Item;
use Illuminate\Support\Str;

class AccessionController extends Controller
{
    // Create accessions for new item
    public static function accessioning($type, $item_id, $copies)
    {

        $prefix = self::prefixes($type);

        // find last accession for this TYPE (for accession_code sequence)
        $lastAccessionByType = Accession::where('accession_code', 'like', $prefix . '%')
            ->orderBy('accession_code', 'desc')
            ->first();

        $lastNumberByType = $lastAccessionByType
            ? (int) Str::after($lastAccessionByType->accession_code, $prefix)
            : 0;

        // find last copy number for this ITEM (for copy_number sequence)
        $lastCopyForItem = Accession::where('item_id', $item_id)
            ->orderBy('copy_number', 'desc')
            ->first();

        $lastCopyNumber = $lastCopyForItem ? $lastCopyForItem->copy_number : 0;

        for ($i = 1; $i <= $copies; $i++) {
            $nextNumberByType = $lastNumberByType + $i;
            $accession_code = $prefix . str_pad($nextNumberByType, 6, '0', STR_PAD_LEFT);

            Accession::create([
                'item_id'        => $item_id,
                'accession_code' => $accession_code,
                'copy_number'    => $lastCopyNumber + $i,
                'status'         => '0',
            ]);
        }
    }

    // Create accessions for existing item  
    public function accessioning_old(AccessionRequest $request)
    {
        $copies = (int) $request->copies;
        $item = Item::find($request->item_id);

        $prefix = self::prefixes($item->type);

        // find last accession for this TYPE (for accession_code sequence)
        $lastAccessionByType = Accession::where('accession_code', 'like', $prefix . '%')
            ->orderBy('accession_code', 'desc')
            ->first();

        $lastNumberByType = $lastAccessionByType
            ? (int) Str::after($lastAccessionByType->accession_code, $prefix)
            : 0;

        // find last copy number for this ITEM (for copy_number sequence)
        $lastCopyForItem = Accession::where('item_id', $request->item_id)
            ->orderBy('copy_number', 'desc')
            ->first();

        $lastCopyNumber = $lastCopyForItem ? $lastCopyForItem->copy_number : 0;

        for ($i = 1; $i <= $copies; $i++) {
            $nextNumberByType = $lastNumberByType + $i;
            $accession_code = $prefix . str_pad($nextNumberByType, 6, '0', STR_PAD_LEFT);

            Accession::create([
                'item_id'        => $request->item_id,
                'accession_code' => $accession_code,
                'copy_number'    => $lastCopyNumber + $i,
                'status'         => '0',
            ]);
        }
    }


    public static function prefixes($type)
    {
        $prefixes = [
            'book'                  => 'GC',
            'reference'             => 'RF',
            'reserved'              => 'RS',
            'periodical'            => 'PD',
            'undergraduate_thesis'  => 'UT',
            'graduate_thesis'       => 'GT',
            'serials'               => 'SR',
            'electronic'            => 'ER',
        ];
        return $prefixes[$type] ?? '';
    }
}
