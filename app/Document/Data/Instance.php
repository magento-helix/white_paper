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
    private $data = [];

    private $instanceConfig;
    private $dataConfig;

    private $dataSearchConfig = [];

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
                    $buildId = isset($measurement['build_id']) ? $measurement['build_id'] : $item['build']['id'];
                    $src = BP . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR
                        . $this->instanceConfig['type'] . DIRECTORY_SEPARATOR
                        . $profile['name'] . DIRECTORY_SEPARATOR
                        . $measurement['type'] . DIRECTORY_SEPARATOR
                        . $buildId . DIRECTORY_SEPARATOR
                        . $item['path'];
                    /** @var ProviderInterface $provider */
                    $provider = ProviderRegistry::getProvider($item['type'], $src);
                    $provider->setConfig($this->getDataConfig($item, $measurement['type']));
                    $provider->setInstance($this);
                    $provider->load($src);
                    if (isset($this->data[$profile['name'] . $measurement['type'] . $item['type']])) {
                        $this->data[$profile['name'] . $measurement['type'] . $item['type']]['full'] = array_merge_recursive(
                            $this->data[$profile['name'] . $measurement['type'] . $item['type']]['full'],
                            $provider->getReportData($src)
                        );
                        $this->data[$profile['name'] . $measurement['type'] . $item['type']]['filtered'] = array_merge_recursive(
                            $this->data[$profile['name'] . $measurement['type'] . $item['type']]['filtered'],
                            $provider->getData()
                        );
                    } else {
                        $this->data[$profile['name'] . $measurement['type'] . $item['type']] = [
                            'full' => $provider->getReportData($src),
                            'filtered' => $provider->getData()
                        ];
                    }
                }
            }
        }
    }

    public function getInstanceType(): string
    {
        return $this->instanceConfig['type'];
    }

    private function initializeJtlDataConfig($srcConfig, $measurementType) {
        $type = $srcConfig['type'];
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

    private function initializeConcurrencyJtlDataConfig($srcConfig, $measurementType) {
        $type = $srcConfig['type'];
        if (!isset($this->dataSearchConfig[$measurementType . $type])) {
            $this->initializeJtlDataConfig($srcConfig, $measurementType);
        }
        $cores = (int)str_replace("Pro", "", $this->getInstanceType());
        $maxThreadId = 0;
        $start = ceil($srcConfig['build']['threads']['start'] * $cores);
        for ($i = $srcConfig['build']['threads']['start']; $i  <= $srcConfig['build']['threads']['end']; $i += $srcConfig['build']['threads']['step']) {
            $maxThreadId += ceil($cores * $i);
        }
        $excepts = [];
        if (isset($srcConfig['build']['threads']['excepts'])) {
            foreach ($srcConfig['build']['threads']['excepts'] as $item) {
                $excepts[] = ceil($cores * $item);
            }
        }

        $this->dataSearchConfig[$measurementType . $type]['borders']['start'] = $start;
        $this->dataSearchConfig[$measurementType . $type]['borders']['maxThreadId'] = $maxThreadId;
        $this->dataSearchConfig[$measurementType . $type]['borders']['excepts'] = $excepts;
        $this->dataSearchConfig[$measurementType . $type]['borders']['field'] = 'allThreads';
    }

    private function initializeIndexerLogDataConfig($srcConfig, $measurementType) {
        $type = $srcConfig['type'];
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

    private function initializeJsonDataConfig($srcConfig, $measurementType)
    {
        $type = $srcConfig['type'];
        $this->dataSearchConfig[$measurementType . $type] = [];
    }

    private function initializeGrafanaDataConfig($srcConfig, $measurementType)
    {
        $type = $srcConfig['type'];
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

    private function getDataConfig($srcConfig, $measurementType): array
    {
        $type = $srcConfig['type'];
        if ($type == ProviderRegistry::CONCURRENCY_JTL) {
            $this->initializeConcurrencyJtlDataConfig($srcConfig, $measurementType);
        }
        if (!isset($this->dataSearchConfig[$measurementType . $type])) {
            if ($type == ProviderRegistry::JTL){
                $this->initializeJtlDataConfig($srcConfig, $measurementType);
            } elseif ($type == ProviderRegistry::JSON) {
                $this->initializeJsonDataConfig($srcConfig, $measurementType);
            } elseif ($type == ProviderRegistry::GRAFANA) {
                $this->initializeGrafanaDataConfig($srcConfig, $measurementType);
            } elseif ($type == ProviderRegistry::INDEXER_LOG) {
                $this->initializeIndexerLogDataConfig($srcConfig, $measurementType);
            }
        }

        return $this->dataSearchConfig[$measurementType . $type];
    }

    public function getData(string $key): array
    {
        return $this->data[$key];
    }
}