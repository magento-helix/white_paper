<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:25 PM
 */

namespace App\Document\Content\Page\Block\Type;

use App\Document\Content\Page\Block\TypeInterface;
use App\Document\Data\InstanceInterface;
use App\Document\Font;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;

class CompositeColumnChart extends ColumnChart implements TypeInterface
{
    public function add(Section $section, $content, $subTitle = false)
    {
        $content['index'] = 0;
        parent::add($section, $content, $subTitle);
    }
}