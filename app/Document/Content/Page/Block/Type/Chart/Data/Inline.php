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
}