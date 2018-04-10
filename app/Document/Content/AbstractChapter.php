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

    public function addTitle(Section $section, $title)
    {
        $section->addText(
            $title . '<w:br/>',
            Font::getChapterTitleStyle(),
            Font::DEFAULT_CHAPTER_TITLE
        );
    }

    public function addPages(
        PhpWord $phpWord,
        Section $section,
        array $pages,
        InstanceInterface $instance = null,
        array $measurementConfig = []
    ) {
        foreach ($pages as $index => $page) {
            if (isset($page['src']) && $page['src'] !== $measurementConfig['type']) {
                continue;
            }
            foreach ($page['blocks'] as $block) {
                $this->blockTypePool
                    ->getPage($block['type'], $phpWord, $instance, $measurementConfig)
                    ->add($section, $block['data']);
            }
            if (isset($pages[$index + 1])) {
                $section = $this->addPage($phpWord);
            }
        }
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