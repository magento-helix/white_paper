<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 10:54 AM
 */

namespace App\Document\Content\Page\Block\Type\Text\Data;

use App\Document\Data\Instance;
use App\Document\Data\InstanceInterface;
use App\Document\Data\JtlProvider\DataProviderInterface;

class IndexerLog implements DataProviderInterface
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
        return $this->instance->getData(
            $this->measurementConfig['profile'] . $this->measurementConfig['type'] . Instance::INDEXER_LOG
        )['filtered']['by_tags'][$data['pattern']];
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