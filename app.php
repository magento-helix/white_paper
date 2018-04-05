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

$config = new \App\Config('./resources/config.json');

$doc = new App\Document($config);

$doc->create();