<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/10/18
 * Time: 7:40 AM
 */

namespace App\Document\Data;

use App\Document\Data\Parser\RemoteJson;

class GrafanaProvider implements ProviderInterface
{
    /**
     * @var RemoteJson
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

    public function __construct($config, InstanceInterface $instance)
    {
        $this->instance = $instance;
        $this->parser = new RemoteJson();
        $this->config = $config;
        $this->appConfig = new \App\Config(BP . '/resources/config.json');;
    }

    public function load(string $src)
    {

        $this->data = $this->getReportData($src);
    }


    private function prepareURL($path)
    {
        $result = [];
        $template = $this->appConfig->getData()['grafana']['base_url']
            . $this->appConfig->getData()['grafana']['db_sub_url'];
        $path = preg_replace("/.*{$this->instance->getInstanceType()}\//", "", $path);
        $parts = explode('/', $path);
        $field = array_pop($parts);
        $instanceData = $this->instance->getData(implode("",$parts));
        $startTime = $instanceData['full'][0][$field];
        $endTime = $instanceData['full'][count($instanceData['full']) - 2][$field];

        foreach ($this->config['queries']['CPU'] as $query) {
            $query = str_replace('%start_time%', $startTime, $query);
            $query = str_replace('%end_time%', $endTime, $query);
            $result[] = sprintf($template, urlencode($query));
        }

        return $result;
    }

    public function getReportData(string $src) : array
    {
        $key = md5($src);
        if (!isset($this->reportData[$key])) {
            $urls = $this->prepareURL($src);
            foreach ($urls as $url) {
                $this->reportData[$key][] = $this->parser->parse($url);
            }
        }

        return $this->reportData[$key];
    }

    public function getData() : array
    {
        return $this->data;
    }
}
