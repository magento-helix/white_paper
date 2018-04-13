<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data;

use App\Document\Data\Instance;
use App\Document\Data\InstanceInterface;
use App\Document\Data\JtlProvider\DataProviderInterface;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 10:54 AM
 */
class Gragana implements DataProviderInterface
{
    const TIME_FORMAT = 'H:i';

    const RANGE_COUNT = 12;

    private $instance;

    private $measurementConfig;

    /**
     * @var array
     */
    private $range;

    /**
     * @var int
     */
    private $count;

    public function __construct(InstanceInterface $instance = null, array $measurementConfig = [])
    {
        $this->instance = $instance;
        $this->measurementConfig = $measurementConfig;
    }

    /**
     * @param array $data
     * @param int $index
     * @return array
     */
    public function getData(array $data, int $index): array
    {
        $reportData = $this->instance->getData($this->measurementConfig['profile'] . $this->measurementConfig['type'] . Instance::GRAFANA)['full'];
        $reportData = $reportData[$index][0]['series'][0];

        return $reportData;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getRange(array $data): array
    {
        if (empty($this->range)) {
            $result = [];
            $count = static::RANGE_COUNT;
            $reportData = $this->instance->getData($this->measurementConfig['profile'] . $this->measurementConfig['type'] . Instance::GRAFANA)['full'];
            $reportData = $reportData[0]['results'][0]['series'][0]['values'];

            $this->count = count($reportData);
            $maxCategory = (int)$reportData[count($reportData) - 1][0] / 1000;
            $minCategory = (int)$reportData[0][0] / 1000;

            $delta = $maxCategory - $minCategory;
            $step = $delta / $count;

            for ($i = 0; $i <= $count; $i++) {
                $result[] = date(self::TIME_FORMAT, ($minCategory + $i * $step));
            }
            $this->range = $result;
        }

        return $this->range;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param array $data
     * @param int $index
     * @return string
     */
    public function getSeriesTitle(array $data, int $index): string
    {
        // TODO: Implement getSeriesTitle() method.
    }
}