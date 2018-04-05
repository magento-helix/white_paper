<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:25 PM
 */

namespace App\Document\Content\Page\Block\Type;

use App\Document\Content\Page\Block\TypeInterface;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;

class Image implements TypeInterface
{
    /**
     * @var PhpWord
     */
    private $phpWord;

    public function __construct(PhpWord $phpWord)
    {
        $this->phpWord = $phpWord;
    }

    public function add(Section $section, $content)
    {
        $section->addImage(
            BP . $content,
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'width' => 450,
                'height' => 480,
            ],
            false
        );
    }
}