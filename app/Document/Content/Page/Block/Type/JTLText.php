<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:25 PM
 */

namespace App\Document\Content\Page\Block\Type;

use App\Document\Content\Page\Block\TypeInterface;
use App\Document\Data\InstanceInterface;
use App\Document\Font;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;

class JTLText implements TypeInterface
{
    /**
     * @var PhpWord
     */
    private $phpWord;

    /**
     * @var DataProviderPool
     */
    private $dataProviderPool;

    /**
     * @var InstanceInterface
     */
    private $instance;

    /**
     * @var array
     */
    private $measurementConfig;

    public function __construct(PhpWord $phpWord, InstanceInterface $instance, array $measurementConfig)
    {
        $this->phpWord = $phpWord;
        $this->dataProviderPool = new DataProviderPool();
        $this->instance = $instance;
        $this->measurementConfig = $measurementConfig;
    }

    public function add(Section $section, $content, $subTitle = false)
    {
        $dataProvider = $this->dataProviderPool->get($content['type'], $this->instance, $this->measurementConfig);

        foreach ($content['data']['items'] as $index => $item) {
            $value = $dataProvider->getData($content, $index);
            $section->addText(
                "{$item['title']}: " . ceil($value['values'][0][1]),
                Font::getChapterContentStyle(),
                Font::DEFAULT_CHAPTER_CONTENT
            );
        }
    }
}