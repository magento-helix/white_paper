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

class GroupJTLTable implements TypeInterface
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
                'Load',
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

        foreach ($dataProvider->getRange($content) as $index => $load) {
            $table->addRow(Font::DEFAULT_TABLE_ROW_HEIGHT / 2);
            $table->addCell($cellSize, $cellRowSpan)
                ->addText(
                    "{$load}",
                    [
                        'name' => Font::DEFAULT_TABLE_FONT,
                        'size' => Font::DEFAULT_TABLE_TEXT_SIZE,
                        'bold' => true,
                    ],
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
                );
            foreach ($cellData as $cellIndex => $item) {
                $max = isset($content['data']['items'][$cellIndex]['max'])
                    ? $content['data']['items'][$cellIndex]['max']
                    : (isset($content['max']) ? $content['max'] : null);
                $min = isset($content['data']['items'][$cellIndex]['min'])
                    ? $content['data']['items'][$cellIndex]['min']
                    : (isset($content['min']) ? $content['min'] : null);
                $border = isset($content['data']['items'][$cellIndex]['border'])
                    ? $content['data']['items'][$cellIndex]['border']
                    : (isset($content['border']) ? $content['border'] : 1000);
                $value = $item['values'][0][$index];
                $color = '00B050';
                if ((null !== $min && $value < $min && $min - $value < $border)|| (null !== $max && $value > $max && $value - $max < $border)) {
                    $color = 'FF9600';
                } elseif ((null !== $min && $min - $value > $border) || (null !== $max && $value - $max > $border)) {
                    $color = 'FF0000';
                }
                $table->addCell($cellSize, $cellRowSpan)
                    ->addText(
                        $value,
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