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
use PhpOffice\PhpWord\Style\ListItem;

class MarkedList implements TypeInterface
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
        $content = str_replace(
            '<B>',
            '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t xml:space="preserve">',
            $content
        );

        $content = str_replace('</B>', '</w:t></w:r><w:r><w:t>', $content);

        $content = explode('<li>', $content);
        foreach ($content as $item) {
            $section->addListItem(
                $item,
                0,
                Font::getChapterContentStyle(),
                ListItem::TYPE_BULLET_FILLED,
                [
                    'spaceBefore' => 100,
                    'spaceAfter' => 100,
                    'spacing' => 1,
                    'kerning' => 1,
                ]
            );
        }
    }
}