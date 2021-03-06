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

class JTLTable implements TypeInterface
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

    public function add(Section $section, $content, $subTitle = false)
    {
        $title = $content['title'];
        $section->addText(
            $title . '<w:br/>',
            Font::getChartTitleStyle(),
            Font::DEFAULT_CHART_TITLE
        );
        $table = $section->addTable(Font::DEFAULT_TABLE_STYLE);
        $table->setWidth(Font::DEFAULT_TABLE_WIDTH + 1000);
        $cellRowSpan = ['valign' => 'center'];

        $dataProvider = $this->dataProviderPool->get($content['type'], $this->instance, $this->measurementConfig);
        $cellData = [];

        $cellSize = 1500;

        $table->addRow(Font::DEFAULT_TABLE_ROW_HEIGHT);
        $table->addCell($cellSize, $cellRowSpan)
            ->addText(
                'Percentile',
                [
                    'name' => Font::DEFAULT_TABLE_FONT,
                    'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                    'color' => 'ffffff',
                    'bold' => true
                ],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );
        foreach ($content['data']['items'] as $index => $item) {
            $cellData[$index] = $dataProvider->getData($content, $index);

            $table->addCell($cellSize, $cellRowSpan)
                ->addText(
                    $item['title'],
                    [
                        'name' => Font::DEFAULT_TABLE_FONT,
                        'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                        'bold' => true,
                        'color' => 'ffffff'
                    ],
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
                );
        }

        foreach ($content['metrics'] as $index => $metric) {
            $table->addRow(Font::DEFAULT_TABLE_ROW_HEIGHT / 2);
            $table->addCell($cellSize, $cellRowSpan)
                ->addText(
                    "{$metric['config']['lvl']}",
                    [
                        'name' => Font::DEFAULT_TABLE_FONT,
                        'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                        'bold' => true,
                    ],
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
                );
            foreach ($cellData as $cellDatum) {
                $value = $cellDatum['values'][$index][1];
                $color = '00B050';
                if ($value >= $content['max'] && $value < $content['max'] + $content['deviation']) {
                    $color = 'FF9600';
                } elseif ($value >= $content['max'] + $content['deviation']) {
                    $color = 'FF0000';
                }
                echo "value = $value||||| color = $color";
                $table->addCell($cellSize, $cellRowSpan)
                    ->addText(
                        $cellDatum['values'][$index][1],
                        [
                            'name' => Font::DEFAULT_TABLE_FONT,
                            'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                            'color' => $color,
                        ],
                        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
                    );
            }
        }
    }
}