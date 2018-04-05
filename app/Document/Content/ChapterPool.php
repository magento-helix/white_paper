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
//        'introduction' => IntroductionChapter::class,
        'environment' => EnvironmentChapter::class,
        'condition' => ConditionChapter::class,
        'emulationDetails' => EmulationDetailsChapter::class,
    ];

    public function get($type, Config $config) : ChapterInterface
    {
        return new Chapter($config);
    }
}