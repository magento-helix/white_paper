<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/10/18
 * Time: 7:40 AM
 */

namespace App\Document\Data;

use App\Document\Data\Parser\Log;

class IndexerLogProvider implements ProviderInterface
{
    /**
     * @var Log
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

    /**
     * @var InstanceInterface
     */
    private $instance;

    /**
     * @var \App\Config
     */
    private $appConfig;

    public function __construct()
    {
        $this->parser = new Log();
        $this->appConfig = new \App\Config(BP . '/resources/config.json');;
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
        $patterns = $this->config['patterns'];

        foreach ($patterns as $pattern) {
            preg_match($pattern, $reportData, $needle);
            if(isset($needle[1])) {
                $item = trim($needle[1]);
                $this->data['all'][] = $item;
                $this->data['by_tags'][$pattern] = $item;
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
