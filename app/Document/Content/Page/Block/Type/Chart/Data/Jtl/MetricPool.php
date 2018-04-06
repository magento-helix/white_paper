<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data\Jtl;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Value\Avg;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Value\MetricInterface;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Value\Percentile;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class MetricPool
{
    private $map = [
        'avg' => Avg::class,
        'percentile' => Percentile::class,
    ];

    public function get(array $data) : MetricInterface
    {
        return new $this->map[$data['type']]($data['config']);
    }
}