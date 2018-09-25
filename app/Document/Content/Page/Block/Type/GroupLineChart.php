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

class GroupLineChart extends LineChart implements TypeInterface
{
    const TYPE = 'line';

    public function add(Section $section, $content, $subTitle = false)
    {
        $title = $content['title'] . (isset($content['max']) ? " ({$content['max']}ms - threshold)" : '');
        $section->addText(
            "<w:br/>" . $title,
            Font::getChartTitleStyle(),
            Font::DEFAULT_CHART_TITLE
        );
        $style = [
            'width' => Converter::cmToEmu(18),
            'height' => Converter::cmToEmu(7),
            'showAxisLabels' => true,
            'showGridY' => true,
            'enableLegend' => true,
        ];

        $dataProvider = $this->dataProviderPool->get($content['type'], $this->instance, $this->measurementConfig);
        $categories = $dataProvider->getRange($content);
        $cores = (float)str_replace("Pro", "", $this->instance->getInstanceType());
        foreach ($categories as $key => $category) {
            $categories[$key] = ceil($category * $cores);
        }
        $data = $dataProvider->getData($content, 0);
        $chart = $section->addChart(
            self::TYPE,
            $categories,
            $data['values'][0],
            $style,
            $content['data']['items'][0]['title']
        );
        for ($i = 1; $i < count($content['data']['items']); $i++) {
            $data = $dataProvider->getData($content, $i);
            if (isset($data['values'])) {
                $chart->addSeries(
                    $categories,
                    $data['values'][0],
                    $content['data']['items'][$i]['title']
                );
            }
        }
    }
}