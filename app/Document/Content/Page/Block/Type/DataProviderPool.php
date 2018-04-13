<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:31 PM
 */

namespace App\Document\Content\Page\Block\Type;

use App\Document\Content\Page\Block\Type\Chart\Data\Inline;
use App\Document\Content\Page\Block\Type\Table\Data\Jtl as TableJTL;
use App\Document\Content\Page\Block\Type\Table\Data\Sitespeed as TableSitespeed;
use App\Document\Content\Page\Block\Type\Table\Data\Googlepage as TableGooglepage;
use App\Document\Content\Page\Block\Type\Chart\Data\Jtl as ChartJTL;
use App\Document\Content\Page\Block\Type\Chart\Data\Gragana as ChartGrafana;
use App\Document\Data\InstanceInterface;
use App\Document\Data\JtlProvider\DataProviderInterface;

class DataProviderPool
{
    private $map = [
        'chart_jtl' => ChartJTL::class,
        'chart_grafana' => ChartGrafana::class,
        'chart_inline' => Inline::class,
        'table_jtl' => TableJTL::class,
        'table_sitespeed' => TableSitespeed::class,
        'table_googlepage' => TableGooglepage::class,
    ];

    public function get($type, InstanceInterface $instance = null, array $measurementConfig = []) : DataProviderInterface
    {
        return new $this->map[$type]($instance, $measurementConfig);
    }
}