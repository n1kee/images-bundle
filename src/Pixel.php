<?php

namespace ImagesBundle;

use ImagickPixel;
use ImagickPixelException;

class Pixel
{
    public function __construct(string $hexColor)
    {
        $this->imgPixel = new ImagickPixel($hexColor);
    }

    public function __destruct()
    {
        $this->imgPixel = null;
    }

    public function __call($name, $args)
    {
        return $this->imgPixel->{$name}(...$args);
    }

    /**
     * Normalizes the HEX value the color.
     *
     * @param string $colorHex HEX value of the color.
     * @return string Normalized HEX value.
     */
    public static function normalizeColorHex(string $colorHex): string
    {
        return preg_replace("/^(?!#)/", "#", $colorHex);
    }

    /**
     * Checks if colors are similar.
     *
     * @param string $color1
     * @param string $color2
     * @return bool Returns true if colors are similar.
     */
    public static function compareColors(string $color1, string $color2): array
    {
        $color1 = self::normalizeColorHex($color1);
        $color2 = self::normalizeColorHex($color2);

        try {
            $colorPixel1 = new ImagickPixel($color1);
            $colorPixel2 = new ImagickPixel($color2);
        } catch(ImagickPixelException $ex) {
            return false;
        }
        $isSimilar = $colorPixel1->isPixelSimilar($colorPixel2, 0.1);
        return [
            "similar" => $isSimilar,
            "distance" => self::getDistance($color1, $color2),
        ];
    }

    /**
     * Get's distance between the two colors.
     *
     * @param string | Pixel $color1 HEX value of the seond color.
     * @param string | Pixel $color2 HEX value of the seond color.
     * @return float Distance between the colors.
     */
    public static function getDistance(string $color1, string $color2)
    {
        $color1 = self::normalizeColorHex($color1);
        $color2 = self::normalizeColorHex($color2);

        $red1   = hexdec(substr($color1, 1, 2));
        $green1 = hexdec(substr($color1, 3, 2));
        $blue1  = hexdec(substr($color1, 5, 2));

        $red2   = hexdec(substr($color2, 1, 2));
        $green2 = hexdec(substr($color2, 3, 2));
        $blue2  = hexdec(substr($color2, 5, 2));

        return pow(($red2 - $red1) * .299, 2) +
            pow(($green2 - $green1) * .587, 2) +
            pow(($blue2 - $blue1) * .114, 2);
    }

    /**
     * Get's the color value as a HEX string.
     *
     * @return string Hex string color value.
     */
    public function getColorAsHexString()
    {
        $color = $this->imgPixel->getColor();

        return sprintf(
            '#%s%s%s',
            dechex($color['r']),
            dechex($color['g']),
            dechex($color['b'])
        );
    }

    /**
     * Get's the distance between two colors.
     *
     * @param string | Pixel $color2 HEX value of the seond color.
     * @return float Distance between the colors.
     */
    public function getDistanceFrom(string | Pixel $color2)
    {
        $color2 = self::normalizeColorHex($color2);
        $color1 = $this->getColorAsHexString();
        if ($color2 instanceof Pixel) {
            $color2 = $color2->getColorAsHexString();
        }
        return self::getDistance($color1, $color2);
    }
}
