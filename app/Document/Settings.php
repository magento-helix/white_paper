<?php

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:37 PM
 */

namespace App\Document;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use App\Document\Settings\Header;

class Settings
{
    /**
     * @var Header
     */
    private $header;

    public function __construct()
    {
        $this->header = new Header();
    }

    public function setDefaultPageStyle(PhpWord $phpWord)
    {
        $PidPageSettings = array(
            'headerHeight'=> \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.01),
            'footerHeight'=> \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.2),
            'marginLeft'  => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.75),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.75),
            'marginTop'   => 0,
            'marginBottom'=> 0,
        );
        $phpWord->addSection($PidPageSettings);
    }

    public function setDefaultHeader(PhpWord $phpWord)
    {
        $section = $phpWord->getSections()[0];
        $this->header->addHeader($section);
    }

    public function setDefaultParagraphStyle(PhpWord $phpWord)
    {
        $phpWord->addParagraphStyle(Font::DEFAULT_CHAPTER_CONTENT,
            [

                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                'spacing' => 120,
            ]
        );
    }

    public function setDefaultChapterTitleParagraphStyle(PhpWord $phpWord)
    {
        $phpWord->addParagraphStyle(Font::DEFAULT_CHAPTER_TITLE,
            [

                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
            ]
        );
    }

    public function setDefaultChartTitleStyleStyle(PhpWord $phpWord)
    {
        $phpWord->addParagraphStyle(Font::DEFAULT_CHART_TITLE,
            [

                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]
        );
    }

    public function setDefaultTableStyle(PhpWord $phpWord)
    {
        $phpWord->addTableStyle(
            Font::DEFAULT_TABLE_STYLE,
            [
                'borderSize' => 6,
                'borderColor' => '006699',
                'cellMargin' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                'cellSpacing' => 50,
                'valign' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ],
            [
                'borderBottomSize' => 18,
                'borderBottomColor' => '0000FF',
                'bgColor' => '66BBFF',
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                'valign' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]
        );
    }
}