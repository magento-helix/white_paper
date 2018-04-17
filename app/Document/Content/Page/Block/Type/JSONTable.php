<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:25 PM
 */

namespace App\Document\Content\Page\Block\Type;

use App\Document\Content\Page\Block\TypeInterface;
use App\Document\Data\InstanceInterface;
use App\Document\Font;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;

class JSONTable implements TypeInterface
{
    /**
     * @var PhpWord
     */
    private $phpWord;

    /**
     * @var DataProviderPool
     */
    private $dataProviderPool;

    /**
     * @var InstanceInterface
     */
    private $instance;

    /**
     * @var array
     */
    private $measurementConfig;

    public function __construct(PhpWord $phpWord, InstanceInterface $instance, array $measurementConfig)
    {
        $this->phpWord = $phpWord;
        $this->dataProviderPool = new DataProviderPool();
        $this->instance = $instance;
        $this->measurementConfig = $measurementConfig;
    }

    public function add(Section $section, $content)
    {
        $title = $content['title'];
        $section->addText(
            $title,
            Font::getChartTitleStyle(),
            Font::DEFAULT_CHART_TITLE
        );
        $table = $section->addTable(Font::DEFAULT_TABLE_STYLE);
        $cellRowSpan = ['vMerge' => 'restart', 'valign' => 'center'];

        $dataProvider = $this->dataProviderPool->get($content['type'], $this->instance, $this->measurementConfig);
        $cellSize = 1500;

        $table->addRow();
        $table->addCell($cellSize, $cellRowSpan)
            ->addText(
                'Percentile',
                [
                    'name' => Font::DEFAULT_FONT,
                    'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                    'bold' => true,
                ],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );
        $data = $dataProvider->getData($content, -1);
        $head = array_shift($data);
        foreach ($head as $item) {
            $table->addCell($cellSize, $cellRowSpan)
                ->addText(
                    $item,
                    [
                        'name' => Font::DEFAULT_FONT,
                        'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                        'bold' => true,
                    ],
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
                );
        }

        foreach ($content['metrics'] as $index => $metric) {
            $table->addRow();
            $table->addCell($cellSize, $cellRowSpan)
                ->addText(
                    $metric['type'],
                    [
                        'name' => Font::DEFAULT_FONT,
                        'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                        'bold' => true,
                    ],
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
                );
            foreach ($data[$index] as $value) {
                $color = '00B050';
                if ($value - $content['max'] > 0 && $value - $content['max'] < 500) {
                    $color = 'FF9600';
                } elseif ($value - $content['max'] > 500) {
                    $color = 'FF0000';
                }
                $table->addCell($cellSize, $cellRowSpan)
                    ->addText(
                        $value,
                        [
                            'name' => Font::DEFAULT_FONT,
                            'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                            'color' => $color,
                        ],
                        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
                    );
            }
        }
    }
}