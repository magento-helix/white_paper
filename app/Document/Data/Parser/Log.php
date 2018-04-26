<?php

namespace App\Document\Data\Parser;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Log
{
    public function parse($path)
    {
        return file_get_contents($path);
    }
}