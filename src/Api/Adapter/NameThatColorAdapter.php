<?php

namespace ImagesBundle\Api\Adapter;

use ourcodeworld\NameThatColor\ColorInterpreter;
use ImagesBundle\Api\ImagesApi;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagesBundle\Pixel;

/**
 * An adapter class for NameThatColor database of colors.
 */
class NameThatColorAdapter extends ImagesApi
{
    /**
     * Creates a list of approximate color names.
     *
     * @param string $colorHex HEX value of the color.
     * @return Response The name of the color.
     */
    public function guessColorName(string $colorHex): Response
    {
        $interpreter = new ColorInterpreter();
        $colorData = $interpreter->name($colorHex);
        $result = [
            $colorData["name"] => Pixel::getDistance($colorHex, $colorData["hex"])
        ];

        return new SuccessResponse($result);
    }
}
