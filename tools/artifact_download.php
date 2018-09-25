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
            foreach ($measurement['src'] as $src) {
                $sourceMethod = 'get' . ucfirst($src['type']) . 'SourcePath';
                $sourceFile = $sourceMethod($item, $src, $measurement, $config);
                $buildId = isset($measurement['build_id']) ? $measurement['build_id'] : $src['build']['id'];
                $destinationFile = str_replace(' ', '\ ', BP . "/../temp/{$item['type']}/{$profile['name']}/{$measurement['type']}/${buildId}/{$src['path']}");
                #`rm -rf $destinationFile`;
                $destinationDirName = dirname($destinationFile);
                if (!file_exists($destinationDirName)) {
                    `mkdir -p $destinationDirName`;
                }
                $cmd = "aws s3 cp " . $sourceFile . " ". $destinationFile;
                if (!file_exists($destinationFile)) {
                    exec($cmd, $output, $result);
                    if ($result == 0) {
                        #echo "COMPLETE \n";
                    } else {
                        echo $cmd . "\n";
                        echo "FAIL \n";
                    }
                }
            }
        }
    }
}

function getSourceLinkMap() {
    return    [
        'jtl' => ' s3://helix-cmi-backup-storage/jenkins_artifacts/%s/%s/%s ',
        'concurrencyJtl' => ' s3://helix-cmi-backup-storage/jenkins_artifacts/%s/%s/%s ',
        'json' => ' s3://helix-cmi-backup-storage/jenkins_artifacts/%s/%s/%s ',
        'indexerLog' => ' %s:/var/lib/jenkins/workspace/%s/var/nohup/%s/%s/%s',
    ];
}

function getJtlSourcePath(array $instance, array $src, array $measurement, \App\Config $config)
{
    $buildId = isset($src['build_id']) ? $src['build_id'] : $measurement['build_id'];

    return  sprintf(getSourceLinkMap()[$src['type']], $instance['jenkins_folder'], $buildId, $src['path']);
}

function getConcurrencyJtlSourcePath(array $instance, array $src, array $measurement, \App\Config $config)
{
    $buildId = isset($src['build']['id']) ? $src['build']['id'] : $measurement['build_id'];

    return  sprintf(getSourceLinkMap()[$src['type']], $instance['jenkins_folder'], $buildId, $src['path']);
}

function getJsonSourcePath(array $instance, array $src, array $measurement, \App\Config $config)
{
    $buildId = isset($src['build_id']) ? $src['build_id'] : $measurement['build_id'];

    return  sprintf(getSourceLinkMap()[$src['type']], $instance['jenkins_folder'], $buildId, $src['path']);
}

function getIndexerLogSourcePath(array $instance, array $src, array $measurement, \App\Config $config)
{
    return  sprintf(getSourceLinkMap()[$src['type']], $instance['jenkins_folder'], $config->getMagentoEdition(), $config->getMagentoVersion(), $src['path']);
}