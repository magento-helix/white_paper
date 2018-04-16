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

class LoadGenerationFlowChapter extends AbstractChapter implements ChapterInterface
{
    private $map = [
        Instance::SS_TYPE => 'Server Side',
        Instance::API_TYPE => 'API',
        Instance::CS_SITESPEED_TYPE => 'Client Side load tests',
        Instance::CS_GOOGLEPAGE_TYPE => 'Client Side rate test',
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
//            $this->addTitle($section, $instance['type']);
            foreach ($instance['profiles'] as $key => $profileConfig) {
//                $this->addTitle($section, $profileConfig['name'] . ' profile');
                foreach ($profileConfig['measurements'] as $measurementKey => $item) {
                    $this->addTitle($section, "{$instance['type']} instance with {$profileConfig['name']} profile for {$this->map[$item['type']]}");
                    $this->addPages($phpWord, $section, $content['pages'], $instanceObject, $item);

                    if (isset($profileConfig['measurements'][$measurementKey + 1])) {
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