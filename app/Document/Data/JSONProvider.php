<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/10/18
 * Time: 7:40 AM
 */

namespace App\Document\Data;

use App\Document\Data\Parser\Json;

class JSONProvider implements ProviderInterface
{
    /**
     * @var Json
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

    public function __construct($config)
    {
        $this->parser = new Json();
        $this->config = $config;
    }

    public function load(string $src)
    {
        $this->data = $this->getReportData($src);
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