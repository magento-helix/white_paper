<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 9:24 AM
 */

namespace App\Document\Content;

use App\Config;

class ChapterPool
{
    private $map = [
        'title' => TitleChapter::class,
        'compositeChapter' => CompositeChapter::class,
    ];

    public function get($type, Config $config) : ChapterInterface
    {
        return isset($this->map[$type]) ? new $this->map[$type]($config) : new Chapter($config);
    }
}