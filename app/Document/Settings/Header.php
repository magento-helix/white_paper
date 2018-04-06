<?php

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:37 PM
 */

namespace App\Document\Settings;

use PhpOffice\PhpWord\Element\Section;

class Header
{
    const DEFAULT_HEADER_IMAGE = 'resources/Settings/Top.png';
    const DEFAULT_LOGO_IMAGE = 'resources/Settings/Logo.png';

    public function addHeader(Section $section)
    {
        $header = $section->addHeader();
        $header->addImage(self::DEFAULT_HEADER_IMAGE,
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'width' => 595,
                'height' => 200,
                'marginTop' => -100,
                'marginLeft' => 0,
                'wrappingStyle' => 'square',
                'positioning' => 'absolute',
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontalRel' => 'page',
                'posVertical' =>  \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVerticalRel' => 'page',
            ],
            false
        );
        $header->addImage(self::DEFAULT_LOGO_IMAGE,
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'width' => 100,
                'height' => 30,
                'marginTop' => 30,
                'marginLeft' => 160,
                'wrappingStyle' => 'square',
                'positioning' => 'absolute',
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontalRel' => 'column',
                'posVertical' =>  \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVerticalRel' => 'top-margin-area',
            ],
            false
        );
    }
}