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
use PhpOffice\PhpWord\Shared\Converter;

class AbstractChart implements TypeInterface
{
    const TYPE = '';

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
        if(isset($content['title'])) {
            $title = $content['title'];
            $section->addText(
                $title,
                Font::getChartTitleStyle(),
                Font::DEFAULT_CHART_TITLE
            );
        }

        $style = [
            'width' => Converter::cmToEmu(18),
            'height' => Converter::cmToEmu(8),
            'showAxisLabels' => true,
            'enableLegend' => true,
        ];

        $dataProvider = $this->dataProviderPool->get($content['type'], $this->instance, $this->measurementConfig);
        $categories = $dataProvider->getRange($content);
        $data = $dataProvider->getData($content, 0);
        $chart = $section->addChart(
            static::TYPE,
            $categories,
            $data['values'][0],
            $style,
            $dataProvider->getSeriesTitle($content, 0)
        );
        for ($i = 1; $i < $dataProvider->getCount(); $i++) {
            $data = $dataProvider->getData($content, $i);
            $chart->addSeries(
                $categories,
                $data['values'][0],
                $dataProvider->getSeriesTitle($content, $i)
            );
        }
    }
}