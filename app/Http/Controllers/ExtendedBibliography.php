<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Book;
use App\Models\Dissertation;
use App\Models\Electronic;
use App\Models\Newspaper;
use App\Models\Periodical;
use App\Models\Serial;
use App\Models\Thesis;
use App\Models\Vertical;

class ExtendedBibliography extends Controller
{
    public static function create($p, $item)
    {
        if ($item->item_type === 'audio') {
            Audio::create([
                'item_id' => $item->id,
                'format' => $p->format,
                'duration' => $p->duration,
                'producer' => $p->producer,
            ]);
        } elseif ($item->item_type === 'book') {
            Book::create([
                'item_id' => $item->id,
                'isbn_issn' => $p->isbn_issn,
                'edition' => $p->edition,
                'pages' => $p->pages,
            ]);
        } elseif ($item->item_type === 'thesis') {
            Thesis::create([
                'item_id' => $item->id,
                'abstract' => $p->description,
                'advisor' => $p->advisor,
                'doi' => $p->doi,
                'program_id' => $p->program_id,
                'researchers' => $p->researchers,
            ]);
        } elseif ($item->item_type === 'dissertation') {
            Dissertation::create([
                'item_id' => $item->id,
                'abstract' => $p->description,
                'advisor' => $p->advisor,
                'doi' => $p->doi,
                'program_id' => $p->program_id,
                'researchers' => $p->researchers,
            ]);
        } elseif ($item->item_type === 'serials') {
            Serial::create([
                'item_id' => $item->id,
                'doi' => $p->doi,
                'isbn_issn' => $p->isbn_issn,
                'issue' => $p->issue,
                'pages' => $p->pages,
                'volume' => $p->volume,
            ]);
        } elseif ($item->item_type === 'periodical') {
            Periodical::create([
                'item_id' => $item->id,
                'isbn_issn' => $p->isbn_issue,
                'issue' => $p->issue,
                'pages' => $p->pages,
                'volume' => $p->volume,
            ]);
        } elseif ($item->item_type === 'electronic') {
            Electronic::create([
                'item_id' => $item->id,
                'access_url' => $p->access_url,
                'file_size' => $p->file_size,
                'isbn_issn' => $p->isbn_issn,
            ]);
        } elseif ($item->item_type === 'vertical') {
            Vertical::create([
                'item_id' => $item->id,
                'location' => $p->location,
                'notes' => $p->notes,
                'organization' => $p->organization,
            ]);
        } elseif ($item->item_type === 'newspaper') {
            Newspaper::create([
                'item_id' => $item->id,
                'date' => $p->date,
                'edition' => $p->edition,
                'pages' => $p->pages,
            ]);
        }
    }
}
