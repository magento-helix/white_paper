<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:31 PM
 */

namespace App\Document\Content\Page\Block;

use App\Document\Content\Page\Block\Type\ColumnChart;
use App\Document\Content\Page\Block\Type\CompositeColumnChart;
use App\Document\Content\Page\Block\Type\DocumentAuthors;
use App\Document\Content\Page\Block\Type\DocumentTableOfContent;
use App\Document\Content\Page\Block\Type\DocumentTitle;
use App\Document\Content\Page\Block\Type\JSONTable;
use App\Document\Content\Page\Block\Type\JTLTable;
use App\Document\Content\Page\Block\Type\JTLText;
use App\Document\Content\Page\Block\Type\LineChart;
use App\Document\Content\Page\Block\Type\PieChart;
use App\Document\Content\Page\Block\Type\Table;
use App\Document\Content\Page\Block\Type\Text;
use App\Document\Content\Page\Block\Type\Image;
use App\Document\Data\InstanceInterface;
use PhpOffice\PhpWord\PhpWord;

class TypePool
{
    private $map = [
        'documentTitle' => DocumentTitle::class,
        'documentAuthors' => DocumentAuthors::class,
        'documentTableOfContent' => DocumentTableOfContent::class,
        'text' => Text::class,
        'image' => Image::class,
        'inlineTable' => Table::class,
        'jtlTable' => JTLTable::class,
        'jsonTable' => JSONTable::class,
        'jtlText' => JTLText::class,
        'pieChart' => PieChart::class,
        'lineChart' => LineChart::class,
        'columnChart' => ColumnChart::class,
        'compositeColumnChart' => CompositeColumnChart::class,
    ];

    public function getPage($type, PhpWord $phpWord, InstanceInterface $instance = null, array $measurementConfig = []) : TypeInterface
    {
        return new $this->map[$type]($phpWord, $instance, $measurementConfig);
    }
}