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
    const DEFAULT_FONT = 'Times New Roman';
    const DEFAULT_TABLE_TEXT_SIZE = 10;
    const DEFAULT_TABLE_STYLE = 'defaultTableStyle';
    const DEFAULT_REGULAR_SIZE = 12;
    const DEFAULT_TITLE_SIZE = 12;
    const DEFAULT_BIG_TITLE_SIZE = 18;
    const DEFAULT_CHAPTER_TITLE_SIZE = 13;
    const DEFAULT_CHAPTER_CONTENT = 'defaultChapterContent';
    const LEFT_ORIENTED_CHAPTER_CONTENT = 'leftOrientedChapterContent';
    const DEFAULT_CHAPTER_TITLE = 'defaultChapterTitle';
    const DEFAULT_CHART_TITLE = 'defaultChartTitle';

    /**
     * @return array
     */
    public static function getDefaultTitleStyle()
    {
        return [
            'name' => self::DEFAULT_FONT,
            'size' => self::DEFAULT_TITLE_SIZE,
        ];
    }

    public static function getChapterTitleStyle()
    {
        return [
            'name' => self::DEFAULT_FONT,
            'size' => self::DEFAULT_CHAPTER_TITLE_SIZE,
            'color' => 'ED7D31'
        ];
    }

    public static function getChartTitleStyle()
    {
        return [
            'name' => self::DEFAULT_FONT,
            'size' => self::DEFAULT_TITLE_SIZE,
        ];
    }

    public static function getChapterContentStyle()
    {
        return [
            'name' => self::DEFAULT_FONT,
            'size' => self::DEFAULT_REGULAR_SIZE,
            'space' => ['line' => 1000]
        ];
    }
}