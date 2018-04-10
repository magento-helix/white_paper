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

    private $minValue;
    private $maxValue;

    public function __construct($config)
    {
        $this->parser = new Jtl();
        $this->config = $config;
    }

    public function load(string $src)
    {
        $reportData = $this->getReportData($src);

//        $config = $this->config['config'];
        $needTags = $this->config['needTags'];
        $needFields = $this->config['needFields'];
        $patterns = $this->config['patterns'];

//        $includeSetup = isset($config['data']['includeSetup']) ? $config['data']['includeSetup'] : false;

        foreach($reportData as $key => $item) {
            if (empty($item) || !isset($item['timeStamp'])) {
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

    public function getReportData($reportPath) : array
    {
        $key = md5($reportPath);
        if (!isset($this->reportData[$key])) {
            $this->reportData[$key] = $this->parser->parse($reportPath);
        }

        return $this->reportData[$key];
    }

    public function getData() : array
    {
        return $this->data;
    }
}