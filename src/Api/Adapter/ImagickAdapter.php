<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagickPixel;
use ImagickPixelException;

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

    function parseColorHex(string $colorHex): string {
        return preg_replace("/^(?!#)/", "#", $colorHex);
    }

    function isSimilarColor(string $color1, string $color2): bool {
        $color1 = $this->parseColorHex($color1);
        $color2 = $this->parseColorHex($color2);

        try {
            $colorPixel1 = new ImagickPixel($color1);
            $colorPixel2 = new ImagickPixel($color2);
        } catch(ImagickPixelException $ex) {
            return false;
        }
        return $colorPixel1->isPixelSimilar($colorPixel2, 0.1);
    }

    function getColorName(string $colorHex): SuccessResponse {

        foreach ($this->colors as $colorName => $colorValue) {
            $similarColorFound = $this->isSimilarColor($colorHex, $colorValue);
            if ($similarColorFound) {
                return new SuccessResponse($colorName);
            }
        }

        return new SuccessResponse(null);
    }
}