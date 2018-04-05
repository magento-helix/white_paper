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

class DocumentAuthors implements TypeInterface
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

        $authorParagraphStyleName = 'authors';
        $this->phpWord->addParagraphStyle($authorParagraphStyleName,
            [

                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
                'space' => array('before' => 0, 'after' => 0),
                'indentation' => array('left' => 5500, 'right' => 0)
            ]
        );
        $section->addText(
            $content,
            Font::DEFAULT_TITLE_SIZE,
            $authorParagraphStyleName
        );
    }
}