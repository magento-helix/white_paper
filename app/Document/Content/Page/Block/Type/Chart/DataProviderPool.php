<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:31 PM
 */

namespace App\Document\Content\Page\Block\Type\Chart;

use App\Document\Content\Page\Block\Type\Chart\Data\Inline;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl;

class DataProviderPool
{
    private $map = [
        'inline' => Inline::class,
        'jtl' => Jtl::class,
    ];

    public function get($type) : DataProviderInterface
    {
        return new $this->map[$type]();
    }
}