<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data\Jtl;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Condition\ConditionInterface;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Condition\Contains;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Condition\Equals;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Condition\NotContains;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class ConditionPool
{
    private $map = [
        'equals' => Equals::class,
        'notcontains' => NotContains::class,
        'contains' => Contains::class,
    ];

    public function get(array $data) : ConditionInterface
    {
        return new $this->map[$data['type']]($data);
    }
}