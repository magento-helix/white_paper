<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data;

use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Parser;
use App\Document\Content\Page\Block\Type\Chart\DataProviderInterface;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 10:54 AM
 */
class Jtl implements DataProviderInterface
{
    const TIME_FORMAT = 'H:i';

    const X_COUNT = 12;

    private $reportData = [];

    private $data = [];

    /**
     * @var int
     */
    private $maxValue;

    /**
     * @var int
     */
    private $minValue;

    /**
     * @var array
     */
    private $x = [];

    public function __construct()
    {
        $this->parser = new Parser();

    }

    /**
     * @param array $data
     * @param int $index
     * @return array
     */
    public function getData(array $data, int $index) : array
    {
        $result = [];
        if (empty($this->data)) {
            $this->loadData($data);
        }

        if (!isset($this->data[$data['data']['items'][$index]['title']])) {
            return $result;
        }

        $handleName = $data['data']['items'][$index]['title'];
        $handleCategory = $data['data']['category'];
        $handleValue = $data['data']['value'];
        $handle = $this->data[$handleName];

        $x = $this->getX($data);
        $start = 0;
        $end = count($handle);
        for($i = 0; $i < count($x); $i++) {
            $sum = 0;
            $count = 0;
            for($j = $start; $j < $end; $j++) {
                $item = $handle[$j];
                $timestamp = (int)$item[$handleCategory] / 1000;
                $time = date(self::TIME_FORMAT, $timestamp);
                if (isset($x[$i + 1]) && strtotime($time) > strtotime($x[$i])) {
                    $start = $j;
                    break;
                }
                $sum += (int)$item[$handleValue];
                $count++;
            }
            $result['values'][] = $sum > 0 ? $sum / $count : 0;
        }

        return $result;
    }

    public function loadData($config)
    {
        $reportData = $this->getReportData($config['data']['src']);

        $needTags = [];
        foreach ($config['data']['items'] as $item) {
            $needTags = array_merge($needTags, $item['tags']);
        }

        $needFields = [];
        foreach ($config['data']['items'] as $item) {
            $needFields[] = $item['tag_field'];
        }
        $needFields = array_unique($needFields);

        $this->minValue = (int)$reportData[0][$config['data']['value']];
        $this->maxValue = (int)$reportData[count($reportData) - 2][$config['data']['value']];

        foreach($reportData as $key => $item) {
            if (empty($item) || strpos($item['label'], 'SetUp') !== false) {
                continue;
            }
            foreach ($needFields as $needField) {
                preg_match('/^([^0-9]*)/', $item[$needField], $needle);
                if (in_array(trim($needle[0]), $needTags)) {
                    foreach ($config['data']['items'] as $itemConfig) {
                        if (in_array(trim($needle[0]), $itemConfig['tags'])) {
                            $this->data[$itemConfig['title']][] = $item;
                            if ($this->maxValue < $item[$config['data']['value']]) {
                                $this->maxValue = (int)$item[$config['data']['value']];
                            }
                        }
                    }
                }
            }
        }
    }

    public function getX(array $data) : array
    {
        if (empty($this->x)) {
            $result = [];
            $count = self::X_COUNT;
            $reportData = $this->getReportData($data['data']['src']);

            $maxCategory = (int)$reportData[count($reportData) - 2]['timeStamp'] / 1000;
            $minCategory = (int)$reportData[0]['timeStamp'] / 1000;

            $delta = $maxCategory - $minCategory;
            $step = $delta / $count;

            for ($i = 0; $i <= $count; $i++) {
                $result[] = date(self::TIME_FORMAT, ($minCategory + $i * $step));
            }
            $this->x = $result;
        }

        return $this->x;
    }

    public function getY(array $data) : array
    {
        $result = [];
        $count = self::X_COUNT;
        if (empty($this->data)) {
            $this->loadData($data);
        }

        $delta = $this->maxValue - $this->minValue;
        $step = $delta / $count;

        for ($i = 0; $i <= $count; $i++) {
            $result[] = (int)($this->minValue + $i * $step);
        }

        return $result;
    }

    private function getReportData($reportPath) : array
    {
        if (empty($this->reportData)) {
            $this->reportData = $this->parser->parse($reportPath);
        }

        return $this->reportData;
    }
}