<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:16 PM
 */

namespace App\Document\Content\Page\Block;

use PhpOffice\PhpWord\Element\Section;

interface TypeInterface
{
    public function add(Section $section, $content, $subTitle = false);
}