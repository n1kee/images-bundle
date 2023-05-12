<?php

namespace ImagesBundle;

use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;

use ImagesBundle\Api\Adapter\ColorPizzaAdapter;
use ImagesBundle\Api\ImagesApi;

use ourcodeworld\NameThatColor\ColorInterpreter;

use FilesBundle\Image;

class Images {

    static protected function getApi() {
        return new class {
            protected $apiAdapters = [
                ColorPizzaAdapter::class,
                ImagesApi::class,
            ];

            function __call(string $methodName, $params)
            {   
                $result = null;

                foreach ($this->apiAdapters as $adapter) {
                    try {
                        $result = $adapter::{$methodName}(...$params);
                    } catch(\Exception $ex) {
                        continue;
                    }
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
        $averageColorImg = new Image($img->clone());

        # Needs ext-gd extension
        $palette = Palette::fromContents($img->__toString());

        return $palette->getMostUsedColors($number);
    }
}