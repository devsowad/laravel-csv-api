<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCSVProcess;
use DB;
use Illuminate\Support\Facades\Bus;

class CSVController extends Controller
{
    public function home()
    {
        return view('file-upload');
    }

    public function upload()
    {
        $items = file(request()->csv);

        $header = str_getcsv($items[0]);

        unset($items[0]);

        $chunks = array_chunk($items, 100);

        $batch = Bus::batch([])->dispatch();

        foreach ($chunks as $chunk) {
            $batch->add(new SalesCSVProcess($chunk, $header));
        }

        return $batch;
    }

    public function batch()
    {
        return Bus::findBatch(request()->id);
    }

    public function pendingJob()
    {
        $batch = DB::table('job_batches')->where('pending_jobs', '>', 0)->latest()->first();
        return $batch?->id ? Bus::findBatch($batch->id) : null;
    }
}
