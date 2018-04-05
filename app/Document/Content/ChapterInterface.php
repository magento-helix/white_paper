<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:16 PM
 */

namespace App\Document\Content;

use PhpOffice\PhpWord\PhpWord;

interface ChapterInterface
{
    const TEMPLATE_PATH = BP . '/resources/Content/templates.json';

    public function add(PhpWord $phpWord, $content);
}