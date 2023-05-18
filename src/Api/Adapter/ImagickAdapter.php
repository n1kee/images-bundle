<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagickPixel;
use ImagickPixelException;

/**
 * An adapter class for working with Imagick API.
 * 
 */
class ImagickAdapter implements ImagesApiInterface {
    /**
     * Color names.
     */
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

    /**
     * Normalizes the HEX value the color. 
     * 
     * @param string $colorHex HEX value of the color.
     * @return string Normalized HEX value.
     */
    function normalizeColorHex(string $colorHex): string {
        return preg_replace("/^(?!#)/", "#", $colorHex);
    }

    /**
     * Checks if colors are similar.
     * 
     * @param string $color1
     * @param string $color2
     * @return bool Returns true if colors are similar.
     */
    function isSimilarColor(string $color1, string $color2): bool {
        $color1 = $this->normalizeColorHex($color1);
        $color2 = $this->normalizeColorHex($color2);

        try {
            $colorPixel1 = new ImagickPixel($color1);
            $colorPixel2 = new ImagickPixel($color2);
        } catch(ImagickPixelException $ex) {
            return false;
        }
        return $colorPixel1->isPixelSimilar($colorPixel2, 0.1);
    }

    /**
     * Get's the name of the color.
     * 
     * @param string $colorHex HEX value of the color.
     * @return SuccessResponse Name of the color.
     */
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