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

class Table implements TypeInterface
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
        $table = $section->addTable(Font::DEFAULT_TABLE_STYLE);
        $cellRowSpan = ['alignment' => 'center', 'vMerge' => 'restart', 'valign' => 'center'];
        $cellRowContinue = ['alignment' => 'center', 'gridSpan' => 4, 'valign' => 'center'];

        foreach ($content['rows'] as $row) {
            $table->addRow();
            foreach ($row as $index => $item) {
                if (null == $item) {
                    continue;
                }
                $cellRow = $cellRowSpan;
                $width = 2300;
                if (array_key_exists($index + 1, $row) && null === $row[$index + 1]) {
                    $cellRow = $cellRowContinue;
                    $width = 9200;
                }
                $table->addCell($width, $cellRow)
                    ->addText(
                        $item,
                        [
                            'name' => Font::DEFAULT_FONT,
                            'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                            'bold' => true,
                        ]
                    );
            }

        }
    }
}