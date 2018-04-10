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

    private $ssData = [];
    private $csData = [];
    private $apiData = [];

    private $instanceConfig;
    private $dataConfig;

    private $ssDataConfig = [];

    public function __construct($instanceConfig, $dataConfig)
    {
        $this->instanceConfig = $instanceConfig;
        $this->dataConfig = $dataConfig;

        $this->loadSSData();
    }

    private function loadSSData()
    {
        foreach ($this->instanceConfig['profiles'] as $profile) {
            foreach ($profile['measurements'] as $measurement) {
                $jtlProvider = new JtlProvider($this->getSSDataConfig());
                $src = BP . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR
                    . $this->instanceConfig['type'] . DIRECTORY_SEPARATOR
                    . $profile['name'] . DIRECTORY_SEPARATOR . $measurement['src'];
                $jtlProvider->load($src);
                $this->ssData[$profile['name'] . $measurement['type']] = [
                    'full' => $jtlProvider->getReportData($src),
                    'filtered' => $jtlProvider->getData()
                ];
            }
        }
    }

    private function getSSDataConfig(): array
    {
        if (empty($this->ssDataConfig)) {
            $needTags = [];
            $needFields = [];
            $patterns = ['/^[^0-9(]*/'];
            foreach ($this->dataConfig as $page) {
                if ($page['src'] == self::SS_TYPE) {
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

            $this->ssDataConfig['needTags'] = $needTags;
            $this->ssDataConfig['needFields'] = $needFields;
            $this->ssDataConfig['patterns'] = $patterns;

        }
        return $this->ssDataConfig;
    }

    public function getSSData(string $profile): array
    {
        return $this->ssData[$profile];
    }

    public function getCSData(string $profile): array
    {
        // TODO: Implement getCSProvider() method.
    }

    public function getAPIData(string $profile): array
    {
        // TODO: Implement getAPIProvider() method.
    }
}