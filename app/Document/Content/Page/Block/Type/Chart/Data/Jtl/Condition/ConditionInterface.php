<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Condition;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 10:54 AM
 */
interface ConditionInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function check(array $data) : bool;
}