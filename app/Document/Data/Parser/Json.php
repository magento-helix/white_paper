<?php

namespace App\Document\Data\Parser;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Json
{
    public function parse($path): array
    {
        return json_decode(file_get_contents($path), true);
    }
}