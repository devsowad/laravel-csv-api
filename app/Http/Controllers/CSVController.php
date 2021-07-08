<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCSVProcess;
use App\Models\UserBatch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

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

        UserBatch::create([
            'user_id'   => auth('api')->id(),
            'batch_id'  => $batch->id,
            'file_name' => request()->name,
        ]);

        return $batch;
    }

    public function batch()
    {
        return Bus::findBatch(request()->id);
    }

    public function batches()
    {
        $batches = UserBatch::where('user_id', auth('api')->id())
            ->leftJoin('job_batches', 'batch_id', '=', 'job_batches.id')
            ->get();

        foreach ($batches as $batch) {
            $batch->finished_at = date('h:m:s d-M-Y', $batch->finished_at);
            $batch->created_at  = date('h:m:s d-M-Y', $batch->created_at);
        }

        return $batches;
    }

    public function pendingJob()
    {
        $batch = DB::table('user_has_batches')
            ->where('user_id', auth('api')->id())
            ->leftJoin('job_batches', 'batch_id', '=', 'job_batches.id')
            ->where('job_batches.pending_jobs', '>', 0)
            ->latest('job_batches.created_at')
            ->first('job_batches.id');

        return $batch?->id ? Bus::findBatch($batch->id) : null;
    }
}
