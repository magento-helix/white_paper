<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data;

use App\Document\Data\JtlProvider\DataProviderInterface;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 10:54 AM
 */
class Inline implements DataProviderInterface
{
    /**
     * @param array $data
     * @param int $index
     * @return array
     */
    public function getData(array $data, int $index) : array
    {
        return $data['data']['items'][$index];
    }

    public function getRange(array $data) : array
    {
        return $data['categories'];
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

    /**
     * @return int
     */
    public function getCount(): int
    {
        // TODO: Implement getCount() method.
    }
}