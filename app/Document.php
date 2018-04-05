<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:10 PM
 */

namespace App;

use PhpOffice\PhpWord\PhpWord;
use App\Document\Content;
use App\Document\LineChart;
use App\Document\Settings;

class Document
{
    public function __construct(Config $config)
    {
        $this->phpWord = new PhpWord();
        $this->chart = new LineChart();
        $this->settings = new Settings();
        $this->content = new Content();
        $this->config = $config;
    }

    public function create()
    {
        $section = $this->phpWord->addSection();

        $this->settings->setDefaultHeader($this->phpWord);
        $this->settings->setDefaultTableStyle($this->phpWord);
        $this->settings->setDefaultParagraphStyle($this->phpWord);
        $this->settings->setDefaultChapterTitleParagraphStyle($this->phpWord);
        $this->settings->setDefaultChartTitleStyleStyle($this->phpWord);

        $template = json_decode(file_get_contents('/magento/white_paper/resources/Content/templates.json'), true);

        foreach ($template as $item) {
            $this->content->add($this->phpWord, $this->config, $item);
        }

//        $categories = array(10, 20, 30, 40, 50, 60, 70);
//        $series1 = array(1, 3, 2, 5, 4, 0, 0, 0);
//        $series3 = array(0, 0, 0, 8, 3, 2, 5, 4);
//        $this->chart->add($this->phpWord, '2D charts', $categories, [$series1, $series3]);


        $this->phpWord->save('aaa.docx');
    }
}