<?php

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:37 PM
 */

namespace App\Document\Content;

use App\Config;
use App\Document\Content\Page\Block\TypePool;
use App\Document\Data\InstanceInterface;
use App\Document\Font;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;

class AbstractChapter implements ChapterInterface
{
    const IMAGE_PATH = BP . '/resources/Content/Environment.png';

    const INDEX = null;

    /**
     * @var TypePool
     */
    protected $blockTypePool;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->blockTypePool = new TypePool();
    }

    public function addTitle(Section $section, $title, $depth = 1)
    {
        $section->addTitle($title, $depth);
    }

    public function addPages(
        PhpWord $phpWord,
        Section $section,
        array $pages,
        InstanceInterface $instance = null,
        array $measurementConfig = []
    ) {
        $newPage = false;
        foreach ($pages as $index => $page) {
            if (isset($page['type'])
                && ($page['type'] !== $measurementConfig['type']
                || !$this->isSetSRC($page['src'], $measurementConfig['src']))
            ) {
                continue;
            }
            if ($newPage) {
                $section = $this->addPage($phpWord);
            }
            foreach ($page['blocks'] as $block) {
                $subTitle = isset($block['title']) ? $block['title'] : false;

                $this->blockTypePool
                    ->getBlock($block['type'], $phpWord, $instance, $measurementConfig)
                    ->add($section, $block['data'], $subTitle);
            }
            if(isset($pages[$index + 1])) {
                $newPage = true;
            }
        }
    }

    private function isSetSRC($needle, $haystack)
    {
        $isset = false;

        foreach ($haystack as $item) {
            if ($item['type'] == $needle) {
                $isset = true;
                break;
            }
        }

        return $isset;
    }

    public function add(PhpWord $phpWord, $content)
    {
        $section = $this->addPage($phpWord);

        if (isset($content['title'])) {
            $this->addTitle($section, $content['title']);
        }

        $this->addPages($phpWord, $section, $content['pages']);
    }

    protected function addPage(PhpWord $phpWord)
    {
        return $phpWord->addSection();
    }
}