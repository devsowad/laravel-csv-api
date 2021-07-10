<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCSVProcess;
use App\Models\UserBatch;
use Illuminate\Support\Facades\Bus;

class CSVController extends Controller
{
    public function home()
    {
        return view('file-upload');
    }

    public function upload()
    {
        request()->validate([
            'csv' => 'required|file|mimes:csv',
        ]);

        $items = file(request()->csv);

        $header = str_getcsv($items[0]);

        unset($items[0]);

        $chunks = array_chunk($items, 100);

        $batch = Bus::batch([])->dispatch();

        foreach ($chunks as $chunk) {
            $batch->add(new SalesCSVProcess($chunk, $header, $batch->id));
        }

        UserBatch::create([
            'user_id'   => auth('api')->id(),
            'batch_id'  => $batch->id,
            'file_name' => request()->name,
        ]);

        $batch->name = request()->name;

        return $batch;
    }

    public function batch()
    {
        return Bus::findBatch(request()->id);
    }

    public function history()
    {
        $batches = UserBatch::where('user_id', auth('api')->id())
            ->leftJoin('job_batches', 'batch_id', '=', 'job_batches.id')
            ->latest('job_batches.finished_at')
            ->get();

        foreach ($batches as $batch) {
            $batch->finished_at = date('h:m:s d-M-Y', $batch->finished_at);
            $batch->created_at  = date('h:m:s d-M-Y', $batch->created_at);
        }

        return $batches;
    }

    public function batches()
    {
        $results = [];

        $qry = UserBatch::where('user_id', auth('api')->id())
            ->leftJoin('job_batches', 'batch_id', '=', 'job_batches.id');

        request()->ids
        ? $qry->whereIn('batch_id', request()->ids)
        : $qry->where('job_batches.pending_jobs', '>', 0);

        $batches = $qry->get(['batch_id', 'file_name']);

        foreach ($batches as $key => $batch) {
            $results[$key]       = Bus::findBatch($batch->batch_id);
            $results[$key]->name = $batch->file_name;
        }

        return $results;
    }
}
