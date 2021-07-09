<?php

namespace App\Http\Controllers;

use App\Models\Sales;

class TestController extends Controller
{
    public function __invoke()
    {
        $items = file(request()->csv);

        $header = str_getcsv($items[0]);
        unset($items[0]);

        $chunks = array_chunk($items, 100);

        foreach ($chunks as $chunk) {
            foreach (array_map('str_getcsv', $chunk) as $data) {
                Sales::create([
                    'body' => json_encode(array_combine($header, $data)),
                ]);
            }
        }

        return $header;
    }
}
