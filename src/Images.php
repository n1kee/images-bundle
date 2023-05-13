<?php

namespace ImagesBundle;

use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;

use ImagesBundle\Api\Adapter\TheColorAdapter;
use ImagesBundle\Api\Adapter\ColorPizzaAdapter;
use ImagesBundle\Api\ImagesApi;

use ourcodeworld\NameThatColor\ColorInterpreter;

use FilesBundle\Image;

class Images {

    static protected function getApi() {
        return new class {
            protected $apiAdapters = [
                TheColorAdapter::class,
                ColorPizzaAdapter::class,
                ImagesApi::class,
            ];

            function __call(string $methodName, $params)
            {   
                $result = null;

                foreach ($this->apiAdapters as $adapter) {
                    $result = $adapter::{$methodName}(...$params);
                    return $result;
                }
            }
        };
    }

    static function matchImageColor(Image $img, array $matchColors) {

        $topColors = self::getMostUsedColors($img, 10);

        foreach($topColors as $color => $count) {

            $colorHex = Color::fromIntToHex($color);

            $colorName = self::getColorName($colorHex);

            foreach ($matchColors as $matchColor) {
                var_dump($colorName . " / " . $matchColor);
                var_dump("==================================");
                if (stripos($colorName, $matchColor) !== false) {
                    return $matchColor;
                }
            }
        }
        return null;
    }

    static function getColorName($colorHex) {
        return self::getApi()->getColorName($colorHex);
    }

    static function getMostUsedColors(Image $img, int $number = 1) {

        # Needs ext-gd extension
        $palette = Palette::fromContents($img->__toString());

        return $palette->getMostUsedColors($number);
    }
}