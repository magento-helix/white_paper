<?php

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:37 PM
 */

namespace App\Document\Content;

use App\Document\Data\Instance;
use PhpOffice\PhpWord\PhpWord;

class CompositeChapter extends AbstractChapter implements ChapterInterface
{
    private $map = [
        'SS' => ' for benchmarking',
        'Concurrency-SS' => ' for concurrency benchmarking',
        'API' => ' (API performance)',
        'CS-SITESPEED' => ' (client-side snapshot)',
        'CS-GOOGLEPAGE' => ' (client-side snapshot)',
        'GRAFANA' => ' server loading',
    ];

    public function add(PhpWord $phpWord, $content)
    {
        $section = $this->addPage($phpWord);

        if (isset($content['title'])) {
            $this->addTitle($section, $content['title']);
        }
        $newPage = false;
        $instances = $this->config->getInstances();
        foreach ($instances as $instanceKey => $instance) {
            if (!$instance['include']) {
                continue;
            }
            if ($newPage) {
                $section = $this->addPage($phpWord);
            }
            $instanceObject = new Instance($instance, $content['pages']);
            foreach ($instance['profiles'] as $key => $profileConfig) {
                foreach ($profileConfig['measurements'] as $measurementKey => $item) {
                    $pagesSRC = [];
                    foreach ($content['pages'] as $page) {
                        $pagesSRC[] = $page['src'];
                    }
                    $measurementSRC = [];
                    foreach ($item['src'] as $measurement) {
                        $measurementSRC[] = $measurement['type'];
                    }
                    $countSRC = count(array_intersect($pagesSRC, $measurementSRC));
                    if ($countSRC == 0) {
                        continue;
                    }

                    $this->addTitle(
                        $section,
                        "{$instance['type']} instance with "
                            . ucfirst($profileConfig['name']) . " profile{$content['sub_titles'][$item['type']]}",
                        1
                    );
                    $this->addPages($phpWord, $section, $content['pages'], $instanceObject, $item);

                    if (isset($profileConfig['measurements'][$measurementKey + 1]) && --$countSRC > 0) {
                        $section = $this->addPage($phpWord);
                    }
                }

                if (isset($instance['profiles'][$key + 1])) {
                    $section = $this->addPage($phpWord);
                }
            }

            if (isset($instances[$instanceKey + 1])) {
                $newPage = true;
            }
        }
    }
}