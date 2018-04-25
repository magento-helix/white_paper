<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/12/18
 * Time: 9:33 AM
 */

define('BP', __DIR__);
include BP . '/../bootstrap.php';
define('SSH_CONFIG', '~/.ssh/config');
date_default_timezone_set('UTC');

$config = new \App\Config(BP . '/../resources/config.json');

foreach ($config->getInstances() as $item) {
    if (!$item['include']) {
        continue;
    }

    foreach ($item['profiles'] as $profile) {
        foreach ($profile['measurements'] as $measurement) {
            $sourceFile = " {$item['jenkins']}:/var/lib/jenkins/jobs/{$item['jenkins_folder']}/builds/{$measurement['build_id']}/archive/{$measurement['src']} ";
            $destinationFile = str_replace(' ', '\ ', BP . "/../temp/{$item['type']}/{$profile['name']}/{$measurement['type']}/{$measurement['src']}");
            `rm -rf $destinationFile`;
            $destinationDirName = dirname($destinationFile);
            if (!file_exists($destinationDirName)) {
                `mkdir -p $destinationDirName`;
            }
            $cmd = "scp -r -F " . SSH_CONFIG . $sourceFile . " ". $destinationFile;
            echo $cmd . "\n";
            exec($cmd, $output, $result);
            if ($result == 0) {
                echo "COMPLETE \n";
            } else {
                echo "FAIL \n";
            }
        }
    }
}