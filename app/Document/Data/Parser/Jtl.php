<?php

namespace App\Document\Data\Parser;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Jtl
{
    public function parse($path): array
    {
        $content = file_get_contents($path);
        return $this->parseCsv($content);
    }

    function parseCsv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
    {
        $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
        $enc = preg_replace_callback(
            '/"(.*?)"/s',
            function ($field) {
                return urlencode(utf8_encode($field[1]));
            },
            $enc
        );
        $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
        $headers = str_getcsv(array_shift($lines));
        $count = count($headers);
        return array_filter(
            array_map(
                function ($line) use ($delimiter, $trim_fields, $headers, $count) {
                    $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
                    if (count($fields) < $count) {
                        return [];
                    }
                    $data = [];
                    foreach ($fields as $index => $field) {
                        $data[$headers[$index]] = $field;
                    }
                    return
                        array_map(
                            function ($field) {
                                return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                            },
                            $data
                        );
                },
                $lines
            )
        );
    }
}