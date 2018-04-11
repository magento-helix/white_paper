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
    const SS_TYPE = 'SS';
    const API_TYPE = 'API';
    const CS_SITESPEED_TYPE = 'CS-SITESPEED';
    const CS_GOOGLEPAGE_TYPE = 'CS-GOOGLEPAGE';

    private $data = [];

    private $instanceConfig;
    private $dataConfig;

    private $dataSearchConfig = [];

    private $dataProviderMap = [
        self::SS_TYPE => JtlProvider::class,
        self::API_TYPE => JtlProvider::class,
        self::CS_SITESPEED_TYPE => JSONProvider::class,
        self::CS_GOOGLEPAGE_TYPE => JSONProvider::class,
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
                /** @var ProviderInterface $provider */
                $provider = new $this->dataProviderMap[$measurement['type']]($this->getDataConfig($measurement['type']));
                $src = BP . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR
                    . $this->instanceConfig['type'] . DIRECTORY_SEPARATOR
                    . $profile['name'] . DIRECTORY_SEPARATOR
                    . $measurement['type'] . DIRECTORY_SEPARATOR
                    . $measurement['src'];
                $provider->load($src);
                $this->data[$profile['name'] . $measurement['type']] = [
                    'full' => $provider->getReportData($src),
                    'filtered' => $provider->getData()
                ];
            }
        }
    }

    private function initializeJtlDataConfig($type) {
        $needTags = [];
        $needFields = [];
        $patterns = ['/^[^0-9(]*/'];
        foreach ($this->dataConfig as $page) {
            if ($page['src'] == $type) {
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

        $this->dataSearchConfig[$type]['needTags'] = $needTags;
        $this->dataSearchConfig[$type]['needFields'] = $needFields;
        $this->dataSearchConfig[$type]['patterns'] = $patterns;
    }

    private function initializeJsonDataConfig($type)
    {
        $this->dataSearchConfig[$type] = [];
    }

    private function getDataConfig($type): array
    {
        if (!isset($this->dataSearchConfig[$type])) {
            if ($type == self::SS_TYPE || $type == self::API_TYPE){
                $this->initializeJtlDataConfig($type);
            } elseif ($type == self::CS_SITESPEED_TYPE || $type == self::CS_GOOGLEPAGE_TYPE) {
                $this->initializeJsonDataConfig($type);
            }
        }

        return $this->dataSearchConfig[$type];
    }

    public function getData(string $key): array
    {
        return $this->data[$key];
    }
}