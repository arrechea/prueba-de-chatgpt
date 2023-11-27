<?php

namespace App\Librerias\Helpers;

use Illuminate\Support\Facades\Storage;

abstract class LibFileHelpers
{
    static public function get_csv_data($csv)
    {
        $file_path = 'storage/app/public/csvs/' . $csv;
        if (!is_readable($file_path)) {
            return false;
        }

        $header = false;
        $return = [];

        $file_to_read = fopen($file_path, 'r');
        if ($file_to_read !== false) {

            while (($data = fgetcsv($file_to_read)) !== false) {
                if ($header === false) {
                    foreach ($data as $i => $datum) {
                        $data[ $i ] = strtolower($datum);
                    }
                    $header = $data;
                } else {
                    $return[] = array_combine($header, $data);
                }
            }
            fclose($file_to_read);
        }

        return $return;
    }
}
