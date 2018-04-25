<?php

namespace App\Document\Data\JtlProvider\Jtl;

use App\Document\Data\JtlProvider\Jtl\Value\Avg;
use App\Document\Data\JtlProvider\Jtl\Value\Concurrent;
use App\Document\Data\JtlProvider\Jtl\Value\Count;
use App\Document\Data\JtlProvider\Jtl\Value\MetricInterface;
use App\Document\Data\JtlProvider\Jtl\Value\Percentile;

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
        'count' => Count::class,
        'concurrent' => Concurrent::class,
    ];

    public function get(array $data) : MetricInterface
    {
        $config = isset($data['config']) ? $data['config'] : [];
        return new $this->map[$data['type']]($config);
    }
}