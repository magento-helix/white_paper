<?php

namespace App\Document\Data\Parser;

use App\Util\Protocol\CurlInterface;
use App\Util\Protocol\CurlTransport\UserDecorator;

/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/5/18
 * Time: 11:20 AM
 */
class RemoteJson
{
    /**
     * @var CurlInterface
     */
    private $userDecorator;

    public function __construct()
    {
        $this->userDecorator = new UserDecorator();
    }

    public function parse($path): array
    {
        $this->userDecorator->write($path);
        $data = $this->userDecorator->read();

        return json_decode($data, true);
    }

}