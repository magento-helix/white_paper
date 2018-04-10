<?php

namespace App\Document\Data\JtlProvider\Jtl\Value;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Avg implements MetricInterface
{
    /**
     * @inheritdoc
     */
    public function calculate(array $list): float
    {
         return array_sum($list) / count($list);
    }
}