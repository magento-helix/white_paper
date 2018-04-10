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
                $jtlProvider = new JtlProvider($this->getDataConfig($measurement['type']));
                $src = BP . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR
                    . $this->instanceConfig['type'] . DIRECTORY_SEPARATOR
                    . $profile['name'] . DIRECTORY_SEPARATOR
                    . $measurement['type'] . DIRECTORY_SEPARATOR
                    . $measurement['src'];
                $jtlProvider->load($src);
                $this->data[$profile['name'] . $measurement['type']] = [
                    'full' => $jtlProvider->getReportData($src),
                    'filtered' => $jtlProvider->getData()
                ];
            }
        }
    }

    private function getDataConfig($type): array
    {
        if (!isset($this->dataSearchConfig[$type])) {
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

        return $this->dataSearchConfig[$type];
    }

    public function getData(string $key): array
    {
        return $this->data[$key];
    }
}