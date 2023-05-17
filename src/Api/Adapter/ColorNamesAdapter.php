<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\Abstract\ImagesLocalDbAbstract;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;
use FilesBundle\File;
use ImagesBundle\Api\Adapter\ImagickAdapter;

class ColorNamesAdapter extends ImagesLocalDbAbstract{

    protected string $dbPath = __DIR__ . "/../data/colornames.txt";

    protected function dbParseLine(string $line): array {
        $lineArray = explode(",", $line);
        return [
            "hex" => $lineArray[0],
            "name" => $lineArray[1],
        ];
    }
}