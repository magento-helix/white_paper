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

class DocumentTitle implements TypeInterface
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
        $section = $this->phpWord->getSections()[0];
        $paragraphStyleName = 'pStyle';
        $this->phpWord->addParagraphStyle($paragraphStyleName,
            [

                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 50
            ]
        );
        $fontStyle = [
            'name' => Font::DEFAULT_FONT,
            'size' => Font::DEFAULT_BIG_TITLE_SIZE,
            'bold' => true,
            'color' => 'ED7D31'
        ];

        $section->addText(
            "<w:br/><w:br/><w:br/><w:br/><w:br/>"
            . $content
            . "<w:br/><w:br/><w:br/><w:br/>",
            $fontStyle,
            $paragraphStyleName
        );
    }
}