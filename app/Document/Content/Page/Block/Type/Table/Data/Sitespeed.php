<?php

namespace App\Document\Content\Page\Block\Type\Table\Data;

use App\Document\Data\Instance;
use App\Document\Data\InstanceInterface;
use App\Document\Data\JtlProvider\DataProviderInterface;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 10:54 AM
 */
class Sitespeed implements DataProviderInterface
{
    private $instance;

    private $measurementConfig;

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
        $result = [];
        $reportData = $this->instance->getData($this->measurementConfig['profile'] . $this->measurementConfig['type'] . Instance::JSON)['full'];

        foreach ($reportData['scenarios'] as $key => $scenario) {
            $result[0][] = $key;
            $result[1][] = $scenario['pageTimings']['frontEndTime']['median'];
            $result[2][] = $scenario['pageTimings']['frontEndTime']['p90'];
        }

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getRange(array $data): array
    {
        return [];
    }

    public function getSeriesTitle(array $data, int $index): string
    {
        // TODO: Implement getSeriesTitle() method.
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        // TODO: Implement getCount() method.
    }
}