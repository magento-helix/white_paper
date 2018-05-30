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

class Text implements TypeInterface
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
        if (false !== $subTitle) {
            $section->addTitle(
                $subTitle,
                2
            );
        }

        $content = str_replace(
            '<B>',
            '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t xml:space="preserve">',
            $content
        );

        $content = str_replace('</B>', '</w:t></w:r><w:r><w:t>', $content);

        $content = explode('<w:br/>', $content);
        foreach ($content as $item) {
            $section->addText(
                $item,
                Font::getChapterContentStyle(),
                Font::DEFAULT_CHAPTER_CONTENT
            );
        }
    }
}