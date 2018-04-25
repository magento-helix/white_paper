<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:10 PM
 */

namespace App;

class Config
{
    public function __construct($configPath)
    {
        $this->config = json_decode(file_get_contents($configPath), true);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->config;
    }

    public function getInstances() : array
    {
        return $this->config['instances'];
    }

    public function getMagentoEdition() : string
    {
        return $this->config['edition'];
    }

    public function getMagentoVersion() : string
    {
        return $this->config['version'];
    }
}