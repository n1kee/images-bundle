<?php

namespace ImagesBundle\Api\Adapter;

use ourcodeworld\NameThatColor\ColorInterpreter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Interface\ResponseInterface;
use ImagesBundle\Api\Response\SuccessResponse;

class NameThatColorAdapter implements ImagesApiInterface {
    function getColorName(string $colorHex): ResponseInterface {
        $interpreter = new ColorInterpreter;

        $colorName = $interpreter->name($colorHex)["name"];

        return new SuccessResponse($colorName);
    }
}