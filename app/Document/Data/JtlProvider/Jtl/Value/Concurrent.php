<?php

namespace App\Document\Data\JtlProvider\Jtl\Value;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Concurrent implements MetricInterface
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function calculate(array $list): float
    {
        $count = count($list);
        $first = $list[0];
        $last = $list[$count - 1];
        $duration = ($last - $first) / 1000 / 60 / 60; //in hours
        $duration = $duration == 0 ? 1 : $duration;

        return $count / $duration;
    }
}