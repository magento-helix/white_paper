<?php

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:37 PM
 */

namespace App\Document;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Element\Section;

class LineChart
{
    const TYPE = 'line';

    /**
     * @param PhpWord $doc
     * @param string $title
     * @param array $timeLine
     * @param array $data
     */
    public function add(PhpWord $doc, string $title, array $timeLine, array $data)
    {
        $section = $doc->addSection();
        $this->addTitle($doc, $section, $title);
        $chart = $section->addChart(self::TYPE, $timeLine, $data[0]);
        $chart->getStyle()
            ->setWidth(Converter::inchToEmu(5.31))
            ->setHeight(Converter::inchToEmu(1.67));
        for ($i = 1; $i < count($data); $i++) {
            $chart->addSeries($timeLine, $data[$i], 'aaa');
        }
    }

    /**
     * @param PhpWord $doc
     * @param Section $section
     * @param string $name
     * @return void
     */
    private function addTitle(PhpWord $doc, Section $section, string $name)
    {
        $paragraphStyleName = 'pStyle';
        $doc->addParagraphStyle($paragraphStyleName,
            [

                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 50
            ]
        );

        $section->addText($name, Font::getDefaultTitleStyle(), $paragraphStyleName);
    }
}