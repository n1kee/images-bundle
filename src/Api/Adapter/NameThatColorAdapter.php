<?php

namespace ImagesBundle\Api\Adapter;

use ourcodeworld\NameThatColor\ColorInterpreter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;

/**
 * An adapter class for NameThatColor database of colors.
 */
class NameThatColorAdapter implements ImagesApiInterface {
    /**
     * Get's the color name.
     * 
     * @param string $colorHex HEX value of the color.
     * @return Response The name of the color.
     */
    function getColorName(string $colorHex): Response {
        $interpreter = new ColorInterpreter;

        $colorName = $interpreter->name($colorHex)["name"];

        return new SuccessResponse($colorName);
    }
}