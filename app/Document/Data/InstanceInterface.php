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
    public function getSSData(string $profile) : array;
    public function getCSData(string $profile) : array;
    public function getAPIData(string $profile) : array;
}