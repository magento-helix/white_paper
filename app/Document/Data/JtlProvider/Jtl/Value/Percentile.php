<?php

namespace App\Document\Data\JtlProvider\Jtl\Value;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Percentile implements MetricInterface
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
     * @param array $list
     * @return float
     */
    public function calculate(array $list): float
    {
        $key = floor((count($list) - 1) * $this->config['lvl'] / 100);
        $list = array_filter($list);
        sort($list);
        return isset($list[$key]) ? $list[$key] : $list[floor((count($list) - 1) * $this->config['lvl'] / 100)];
    }
}