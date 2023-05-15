<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Response\SuccessResponse;
use \ImagickPixel;

class ImagickAdapter implements ImagesApiInterface {
    protected $colors = [
        "Blue" => "#0000FF",
        "Red" => "#FF0000",
        "Green" => "#00FF00",
        "Black" => "#000000",
        "White" => "#FFFFFF",
        "Yellow" => "#FFFF00",
        "Gray" => "#808080",
        "Purple" => "#8000FF",
        "Orange" => "#FF8000",
    ];

    function getColorName(string $colorHex): SuccessResponse {

        foreach ($this->colors as $colorName => $colorValue) {
            $similarColorFound = (new ImagickPixel($colorValue))
                ->isPixelSimilar(
                    new ImagickPixel($colorHex), 0.1
                );
            if ($similarColorFound) {
                return new SuccessResponse($colorName);
            }
        }

        return new SuccessResponse(null);
    }
}