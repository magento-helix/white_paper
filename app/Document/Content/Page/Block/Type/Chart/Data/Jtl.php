<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data;

use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\ConditionPool;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\MetricPool;
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

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var MetricPool
     */
    private $metricPool;

    /**
     * @var ConditionPool
     */
    private $conditionPool;

    public function __construct()
    {
        $this->parser = new Parser();
        $this->metricPool = new MetricPool();
        $this->conditionPool = new ConditionPool();
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
        $metricConfig = isset($data['data']['items'][$index]['metric'])
            ? $data['data']['items'][$index]['metric']
            : $data['metric'];

        $x = $this->getX($data);
        $start = 0;
        $end = count($handle);
        for($i = 0; $i < count($x); $i++) {
            $list = [];
            for($j = $start; $j < $end; $j++) {
                $item = $handle[$j];
                $timestamp = (int)$item[$handleCategory] / 1000;
                $time = date(self::TIME_FORMAT, $timestamp);
                if (isset($x[$i + 1]) && strtotime($time) > strtotime($x[$i])) {
                    $start = $j;
                    break;
                }
                $list[] = (int)$item[$handleValue];
            }
            $result['values'][] = count($list) ? $this->metricPool->get($metricConfig)->calculate($list) : 0;
        }

        return $result;
    }

    public function loadData($config)
    {
        $reportData = $this->getReportData($config['data']['src']);

        $includeSetup = isset($config['data']['includeSetup']) ? $config['data']['includeSetup'] : false;
        $needTags = [];
        $needFields = [];
        $patterns = ['/^[^0-9(]*/'];
        foreach ($config['data']['items'] as $item) {
            $needTags = array_merge($needTags, $item['tags']);
            $needFields[] = $item['tag_field'];
            if (isset($item['pattern'])) {
                $patterns[] = $item['pattern'];
            }
        }

        $needFields = array_unique($needFields);
        $patterns = array_unique($patterns);

        $this->minValue = (int)$reportData[0][$config['data']['value']];
        $this->maxValue = (int)$reportData[count($reportData) - 2][$config['data']['value']];

        foreach($reportData as $key => $item) {
            if (empty($item) || !isset($item['timeStamp'])
                || (!$includeSetup && (strpos($item['label'], 'SetUp') !== false || strpos($item['threadName'], 'setUp') !== false))
            ) {
                continue;
            }

            foreach ($needFields as $needField) {
                $needles = [];
                foreach ($patterns as $pattern) {
                    preg_match($pattern, $item[$needField], $needle);
                    if(isset($needle[0])) {
                        $needles[] = trim($needle[0]);
                    }
                }

                if (array_intersect($needTags, $needles) !== []) {
                    foreach ($config['data']['items'] as $itemConfig) {
                        $isConditionPass = true;
                        if(isset($itemConfig['conditions'])) {
                            foreach ($itemConfig['conditions'] as $condition) {
                                if(!$this->conditionPool->get($condition)->check($item)){
                                    $isConditionPass = false;
                                    break;
                                }
                            }
                        }
                        if (array_intersect($itemConfig['tags'], $needles) !== [] && $isConditionPass) {
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

    private function getReportData($reportPath) : array
    {
        if (empty($this->reportData)) {
            $this->reportData = $this->parser->parse($reportPath);
        }

        return $this->reportData;
    }
}