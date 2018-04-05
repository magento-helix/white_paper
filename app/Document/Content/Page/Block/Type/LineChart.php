<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:25 PM
 */

namespace App\Document\Content\Page\Block\Type;

use App\Document\Content\Page\Block\Type\Chart\DataProviderPool;
use App\Document\Content\Page\Block\TypeInterface;
use App\Document\Font;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;

class LineChart implements TypeInterface
{
    const TYPE = 'line';

    /**
     * @var PhpWord
     */
    private $phpWord;

    /**
     * @var DataProviderPool
     */
    private $dataProviderPool;

    public function __construct(PhpWord $phpWord)
    {
        $this->phpWord = $phpWord;
        $this->dataProviderPool = new DataProviderPool();
    }

    public function add(Section $section, $content)
    {
        $title = $content['title'];
        $section->addText(
            $title . '<w:br/>',
            Font::getChartTitleStyle(),
            Font::DEFAULT_CHART_TITLE
        );
        $style = [
            'width' => Converter::cmToEmu(18),
            'height' => Converter::cmToEmu(8),
            'showAxisLabels' => true,
            'enableLegend' => true,
        ];

        $categories = $this->dataProviderPool->get($content['type'])->getX($content);
        $data = $this->dataProviderPool->get($content['type'])->getData($content, 0);
        $chart = $section->addChart(
            self::TYPE,
            $categories,
            $data['values'],
            $style,
            $content['data']['items'][0]['title']
        );
        for ($i = 1; $i < count($content['data']['items']); $i++) {
            $data = $this->dataProviderPool->get($content['type'])->getData($content, $i);
            $chart->addSeries(
                $categories,
                $data['values'],
                $content['data']['items'][$i]['title']
            );
        }
    }
}