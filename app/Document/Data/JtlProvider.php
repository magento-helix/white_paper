<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/10/18
 * Time: 7:40 AM
 */

namespace App\Document\Data;


use App\Document\Data\Parser\Jtl;

class JtlProvider implements ProviderInterface
{
    /**
     * @var Jtl
     */
    private $parser;

    /**
     * @var array
     */
    private $reportData;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $config;

    public function __construct()
    {
        $this->parser = new Jtl();
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    public function setInstance(InstanceInterface $instance)
    {
        $this->instance = $instance;
    }

    public function load(string $src)
    {
        $reportData = $this->getReportData($src);

        $needTags = $this->config['needTags'];
        $needFields = $this->config['needFields'];
        $patterns = $this->config['patterns'];

        foreach($reportData as $key => $item) {
            if (empty($item) || !isset($item['timeStamp'])) {
                continue;
            }

            if (!isset($item['label']) || !isset($item['threadName'])) {
                continue;
            }

            if (strpos($item['label'], 'SetUp') !== false || strpos($item['threadName'], 'setUp') !== false) {
                continue;
            }

            foreach ($needFields as $needField) {
                $needles = [];
                foreach ($patterns as $pattern) {
                    preg_match($pattern, $item[$needField], $needle);
                    if(isset($needle[0])) {
                        $needles[] = trim($needle[0]);
                    }
                }

                $tags = array_intersect($needTags, $needles);
                if ($tags !== []) {
                    $this->data['all'][] = $item;
                    foreach ($tags as $tag) {
                        $this->data['by_tags'][$tag][] = $item;
                    }
                }
            }
        }
    }

    public function getReportData(string $src)
    {
        $key = md5($src);
        if (!isset($this->reportData[$key])) {
            $this->reportData[$key] = $this->parser->parse($src);
        }

        return $this->reportData[$key];
    }

    public function getData() : array
    {
        return $this->data;
    }
}