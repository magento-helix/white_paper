<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 8:31 PM
 */

namespace App\Document\Content\Page\Block;

use App\Document\Content\Page\Block\Type\DocumentAuthors;
use App\Document\Content\Page\Block\Type\DocumentTitle;
use App\Document\Content\Page\Block\Type\LineChart;
use App\Document\Content\Page\Block\Type\PieChart;
use App\Document\Content\Page\Block\Type\Table;
use App\Document\Content\Page\Block\Type\Text;
use App\Document\Content\Page\Block\Type\Image;
use PhpOffice\PhpWord\PhpWord;

class TypePool
{
    private $map = [
        'documentTitle' => DocumentTitle::class,
        'documentAuthors' => DocumentAuthors::class,
        'text' => Text::class,
        'image' => Image::class,
        'table' => Table::class,
        'pieChart' => PieChart::class,
        'lineChart' => LineChart::class,
    ];

    public function getPage($type, PhpWord $phpWord) : TypeInterface
    {
        return new $this->map[$type]($phpWord);
    }
}