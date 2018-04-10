<?php

namespace App\Document\Data\JtlProvider\Jtl;

use App\Document\Data\JtlProvider\Jtl\Condition\ConditionInterface;
use App\Document\Data\JtlProvider\Jtl\Condition\Contains;
use App\Document\Data\JtlProvider\Jtl\Condition\Equals;
use App\Document\Data\JtlProvider\Jtl\Condition\NotContains;
use App\Document\Data\JtlProvider\Jtl\Condition\NotEquals;


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
        'notequals' => NotEquals::class,
        'notcontains' => NotContains::class,
        'contains' => Contains::class,
    ];

    public function get(array $data) : ConditionInterface
    {
        return new $this->map[$data['type']]($data);
    }
}