<?php

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:37 PM
 */

namespace App\Document;

use App\Config;
use App\Document\Content\ChapterPool;
use PhpOffice\PhpWord\PhpWord;

class Content
{
    public function __construct()
    {
        $this->chapterPool = new ChapterPool();
    }

    public function add(PhpWord $phpWord, Config $config, $data)
    {
        $this->chapterPool->get($data['type'], $config)->add($phpWord, $data);
    }
}