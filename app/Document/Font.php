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

class Font
{
    const DEFAULT_TITLE_FONT = 'Calibri (Body)';
    const DEFAULT_TITLE_SIZE = 18;
    const DEFAULT_CHART_TITLE_SIZE = 14;
    const DEFAULT_TEXT_SIZE = 11;
    const DEFAULT_TEXT_FONT = 'Arial';
    const DEFAULT_TABLE_TEXT_SIZE = 11;
    const DEFAULT_TABLE_FONT = 'Calibri (Body)';
    const DEFAULT_TABLE_TH_SIZE = 14;
    const DEFAULT_TABLE_STYLE = 'defaultTableStyle';
    const DEFAULT_TABLE_WIDTH = 4000;
    const DEFAULT_TABLE_ROW_HEIGHT = 500;
    const DEFAULT_TABLE_TH_HEIGHT = 300;
    const DEFAULT_TABLE_TH_COLOR = 'ffffff';
    const DEFAULT_TABLE_CELL_COLOR = '000000';
    const DEFAULT_BIG_TITLE_SIZE = 18;
    const DEFAULT_CHAPTER_TITLE_SIZE = 13;
    const DEFAULT_CHAPTER_CONTENT = 'defaultChapterContent';
    const DEFAULT_CHAPTER_TEXT_COLOR = 'ED7D31';
    const LEFT_ORIENTED_CHAPTER_CONTENT = 'leftOrientedChapterContent';
    const DEFAULT_CHAPTER_TITLE = 'defaultChapterTitle';
    const DEFAULT_CHART_TITLE = 'defaultChartTitle';

    /**
     * @return array
     */
    public static function getDefaultTitleStyle()
    {
        return [
            'name' => self::DEFAULT_TEXT_FONT,
            'size' => self::DEFAULT_TITLE_SIZE,
        ];
    }

    public static function getChapterTitleStyle()
    {
        return [
            'name' => self::DEFAULT_TEXT_FONT,
            'size' => self::DEFAULT_CHAPTER_TITLE_SIZE,
            'color' => self::DEFAULT_CHAPTER_TEXT_COLOR
        ];
    }

    public static function getChartTitleStyle()
    {
        return [
            'name' => self::DEFAULT_TITLE_FONT,
            'size' => self::DEFAULT_CHART_TITLE_SIZE,
            'color' => '000000'
        ];
    }

    public static function getChapterContentStyle()
    {
        return [
            'name' => self::DEFAULT_TEXT_FONT,
            'size' => self::DEFAULT_TEXT_SIZE,
            'space' => ['line' => 1000],
            'lineHeight' => 1,
        ];
    }
}