<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:04 PM
 */

include 'bootstrap.php';
define('BP', __DIR__);
date_default_timezone_set('UTC');

if (!isset($argv[1]) || !isset($argv[2])) {
    die("\nPlease set magento version and edition like: php app.php 2.2.4 ee\n");
}

$config = new \App\Config("./resources/${argv[1]}/${argv[2]}/config.json");

$doc = new App\Document($config);

$doc->create();