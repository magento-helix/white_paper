<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/10/18
 * Time: 8:14 AM
 */

namespace App\Document\Data;


interface InstanceInterface
{
    public function getData(string $key) : array;
}