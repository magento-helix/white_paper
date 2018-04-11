<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/10/18
 * Time: 7:38 AM
 */

namespace App\Document\Data;

interface ProviderInterface
{
    public function load(string $src);

    public function getData() : array;

    public function getReportData(string $src): array;
}