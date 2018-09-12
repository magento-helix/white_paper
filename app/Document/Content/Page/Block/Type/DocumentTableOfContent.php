<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:25 PM
 */

namespace App\Document\Content\Page\Block\Type;

use App\Document\Content\Page\Block\TypeInterface;
use App\Document\Font;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;

class DocumentTableOfContent implements TypeInterface
{
    /**
     * @var PhpWord
     */
    private $phpWord;

    public function __construct(PhpWord $phpWord)
    {
        $this->phpWord = $phpWord;
    }

    public function add(Section $section, $content, $subTitle = false)
    {
        $section->addText(
            "Table of Contents",
            [
                'color' => '365F91',
                'size' => 18,
                'name' => 'Cambria (Headings)',
                'bold' => true
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
            ]
        );

        $section->addTextBreak();

        $section->addTOC(
            [
                'size' => 10,
                'name' => Font::DEFAULT_TITLE_FONT,
                'bold' => true,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0),
            ],
            [],
            1,
            1
        );
    }
}