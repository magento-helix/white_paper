<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:10 PM
 */

namespace App;

use App\Document\Data\JtlProvider;
use PhpOffice\PhpWord\PhpWord;
use App\Document\Content;
use App\Document\Settings;

class Document
{
    public function __construct(Config $config)
    {
        $this->phpWord = new PhpWord();
        $this->settings = new Settings();
        $this->content = new Content();
        $this->config = $config;
    }

    public function create()
    {
        $this->settings->setDefaultPageStyle($this->phpWord);
        $this->settings->setDefaultHeader($this->phpWord);
        $this->settings->setDefaultTableStyle($this->phpWord);
        $this->settings->setDefaultParagraphStyle($this->phpWord);
        $this->settings->setDefaultChapterTitleParagraphStyle($this->phpWord);
        $this->settings->setDefaultChartTitleStyleStyle($this->phpWord);

        $template = json_decode(file_get_contents('/magento/white_paper/resources/Content/templates.json'), true);

        foreach ($template as $item) {
            $this->content->add($this->phpWord, $this->config, $item);
        }

        $this->phpWord->save('aaa.docx');
    }
}