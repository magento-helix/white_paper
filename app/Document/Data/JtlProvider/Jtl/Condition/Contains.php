<?php

namespace App\Document\Data\JtlProvider\Jtl\Condition;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Contains implements ConditionInterface
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function check(array $data): bool
    {
        return strpos($data[$this->config['field']], $this->config['value']) !== false;
    }
}