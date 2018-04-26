<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/10/18
 * Time: 8:15 AM
 */

namespace App\Document\Data;

class Instance implements InstanceInterface
{
    const JTL = 'jtl';
    const JSON = 'json';
    const GRAFANA = 'grafana';
    const INDEXER_LOG = 'indexerLog';

    private $data = [];

    private $instanceConfig;
    private $dataConfig;

    private $dataSearchConfig = [];

    private $dataProviderMap = [
        self::JTL => JtlProvider::class,
        self::JSON => JSONProvider::class,
        self::GRAFANA => GrafanaProvider::class,
        self::INDEXER_LOG => IndexerLogProvider::class,
    ];

    public function __construct($instanceConfig, $dataConfig)
    {
        $this->instanceConfig = $instanceConfig;
        $this->dataConfig = $dataConfig;

        $this->loadData();
    }

    private function loadData()
    {
        foreach ($this->instanceConfig['profiles'] as $profile) {
            foreach ($profile['measurements'] as $measurement) {
                foreach ($measurement['src'] as $item) {
                    /** @var ProviderInterface $provider */
                    $provider = new $this->dataProviderMap[$item['type']]($this->getDataConfig($item['type'], $measurement['type']), $this);
                    $src = BP . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR
                        . $this->instanceConfig['type'] . DIRECTORY_SEPARATOR
                        . $profile['name'] . DIRECTORY_SEPARATOR
                        . $measurement['type'] . DIRECTORY_SEPARATOR
                        . $item['path'];
                    $provider->load($src);
                    $this->data[$profile['name'] . $measurement['type'] . $item['type']] = [
                        'full' => $provider->getReportData($src),
                        'filtered' => $provider->getData()
                    ];
                }
            }
        }
    }

    public function getInstanceType(): string
    {
        return $this->instanceConfig['type'];
    }

    private function initializeJtlDataConfig($type, $measurementType) {
        $needTags = [];
        $needFields = [];
        $patterns = ['/^[^0-9(]*/'];
        foreach ($this->dataConfig as $page) {
            if ($page['type'] == $measurementType && $page['src'] == $type) {
                foreach ($page['blocks'] as $block) {
                    foreach ($block['data']['data']['items'] as $item) {
                        $needTags = array_merge($needTags, $item['tags']);
                        $needFields[] = $item['tag_field'];
                        if (isset($item['pattern'])) {
                            $patterns[] = $item['pattern'];
                        }
                    }

                    $needTags = array_unique($needTags);
                    $needFields = array_unique($needFields);
                    $patterns = array_unique($patterns);
                }
            }
        }

        $this->dataSearchConfig[$measurementType . $type]['needTags'] = $needTags;
        $this->dataSearchConfig[$measurementType . $type]['needFields'] = $needFields;
        $this->dataSearchConfig[$measurementType . $type]['patterns'] = $patterns;
    }

    private function initializeIndexerLogDataConfig($type, $measurementType) {
        $patterns = [];
        foreach ($this->dataConfig as $page) {
            if ($page['type'] == $measurementType && $page['src'] == $type) {
                foreach ($page['blocks'] as $block) {
                    $patterns[] = $block['data']['pattern'];
                }
            }
        }
        $this->dataSearchConfig[$measurementType . $type]['patterns'] = array_unique($patterns);
    }

    private function initializeJsonDataConfig($type, $measurementType)
    {
        $this->dataSearchConfig[$measurementType . $type] = [];
    }

    private function initializeGrafanaDataConfig($type, $measurementType)
    {
        $queries= [];
        foreach ($this->dataConfig as $page) {
            if ($page['src'] == $type) {
                foreach ($page['blocks'] as $block) {
                    foreach ($this->instanceConfig['sub_hosts'] as $subHost) {
                        $query = str_replace('%cluster_id%', $this->instanceConfig['id'], $block['data']['query']);
                        $query = str_replace('%sub_host_name%', $subHost['name'], $query);
                        $queries[$block['data']['title']][] = $query;
                    }
                }
            }
        }
        $queries = array_unique($queries);
        $this->dataSearchConfig[$measurementType . $type]['queries'] = $queries;
    }

    private function getDataConfig($type, $measurementType): array
    {
        if (!isset($this->dataSearchConfig[$measurementType . $type])) {
            if ($type == self::JTL){
                $this->initializeJtlDataConfig($type, $measurementType);
            } elseif ($type == self::JSON) {
                $this->initializeJsonDataConfig($type, $measurementType);
            } elseif ($type == self::GRAFANA) {
                $this->initializeGrafanaDataConfig($type, $measurementType);
            } elseif ($type == self::INDEXER_LOG) {
                $this->initializeIndexerLogDataConfig($type, $measurementType);
            }
        }

        return $this->dataSearchConfig[$measurementType . $type];
    }

    public function getData(string $key): array
    {
        return $this->data[$key];
    }
}