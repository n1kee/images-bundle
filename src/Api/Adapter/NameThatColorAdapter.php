<?php

namespace ImagesBundle\Api\Adapter;

use ourcodeworld\NameThatColor\ColorInterpreter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;

class NameThatColorAdapter implements ImagesApiInterface {
    function getColorName(string $colorHex): Response {
        $interpreter = new ColorInterpreter;

        $colorName = $interpreter->name($colorHex)["name"];

        return new SuccessResponse($colorName);
    }
}