<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 6/13/18
 * Time: 9:49 AM
 */

namespace App\Document\Data;


class ProviderRegistry
{
    const JTL = 'jtl';
    const CONCURRENCY_JTL = 'concurrencyJtl';
    const GRAFANA = 'grafana';
    const JSON = 'json';
    const INDEXER_LOG = 'indexerLog';

    public static $providerPool = [];

    public static $dataProviderMap = [
        self::JTL => JtlProvider::class,
        self::CONCURRENCY_JTL => ConcurrencyJtlProvider::class,
        self::JSON => JSONProvider::class,
        self::GRAFANA => GrafanaProvider::class,
        self::INDEXER_LOG => IndexerLogProvider::class,
    ];

    public static function getProvider(string $type, string $src) : ProviderInterface
    {
        if (!isset(self::$providerPool[$src])) {
            self::$providerPool[$src] = new self::$dataProviderMap[$type]();
        }

        return self::$providerPool[$src];
    }
}