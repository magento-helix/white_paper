<?php

namespace App\Document\Content\Page\Block\Type\Table\Data;

use App\Document\Data\JtlProvider\AbstractJtl;
use App\Document\Data\JtlProvider\DataProviderInterface;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 10:54 AM
 */
class Jtl extends AbstractJtl implements DataProviderInterface
{
    const RANGE_COUNT = 1;
}