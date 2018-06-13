<?php

namespace App\Document\Content\Page\Block\Type\Table\Data;

use App\Document\Data\Instance;
use App\Document\Data\InstanceInterface;
use App\Document\Data\JtlProvider\DataProviderInterface;
use App\Document\Data\ProviderRegistry;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 10:54 AM
 */
class Googlepage implements DataProviderInterface
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
    public function getData(array $data, int $index)
    {
        $result = [];
        $reportData = $this->instance->getData($this->measurementConfig['profile'] . $this->measurementConfig['type'] . ProviderRegistry::JSON)['full'];

        foreach ($reportData as $key => $scenario) {
            $result[0][] = $key;
            $result[1][] = $scenario['ruleGroups']['SPEED']['score'];
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