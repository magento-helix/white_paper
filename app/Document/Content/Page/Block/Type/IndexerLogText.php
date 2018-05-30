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

class IndexerLogText implements TypeInterface
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

        $section->addText(
            "{$content['title']}:",
            array_merge(Font::getChapterContentStyle(), ['bold' => true]),
            [

                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(1),
                'spacing' => 120,
            ]
        );

        $section->addText(
            str_replace("\n", "<w:br/>", $dataProvider->getData($content, 0)),
            array_merge(Font::getChapterContentStyle(), ['name' => 'Calibri', 'italic' => true]),
            [

                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                'spacing' => -45,
            ]
        );
    }
}