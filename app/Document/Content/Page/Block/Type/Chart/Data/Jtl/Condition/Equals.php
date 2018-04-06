<?php

namespace App\Document\Content\Page\Block\Type\Chart\Data\Jtl\Condition;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class Equals implements ConditionInterface
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
        return $data[$this->config['field']] == $this->config['value'];
    }
}