<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data\Jtl;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Parser
{
    public function parse($path): array
    {
        $lines = explode("\n", file_get_contents($path));
        $headers = str_getcsv(array_shift($lines));
        $data = [];
        foreach ($lines as $line) {
            $row = [];
            foreach (str_getcsv($line) as $key => $field)
                $row[$headers[$key]] = $field;
            $data[] = $row;
        }
        return $data;
    }
}