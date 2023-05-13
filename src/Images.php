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

    function __construct(
        protected TheColorAdapter $theColorAdapter,
        protected ColorPizzaAdapter $colorPizzaAdapter,
        protected ImagesApi $imagesApi,
    ) {
        $apiLoader = new class {
            function __construct(...$adapters) {
                $this->apiAdapters = $adapters;
            }

            function __call(string $methodName, $params)
            {   
                $result = null;

                foreach ($this->apiAdapters as $adapter) {
                    $result = $adapter->{$methodName}(...$params);
                    return $result;
                }
            }
        };
        $this->api = new $apiLoader(
            $this->theColorAdapter,
            $this->colorPizzaAdapter,
            $this->imagesApi,
        );
    }

    function matchImageColor(Image $img, array $matchColors) {

        $topColors = $this->getMostUsedColors($img, 10);

        foreach($topColors as $color => $count) {

            $colorHex = Color::fromIntToHex($color);

            $colorName = $this->getColorName($colorHex);

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

    function getColorName($colorHex) {
        return $this->api->getColorName($colorHex);
    }

    function getMostUsedColors(Image $img, int $number = 1) {

        # Needs ext-gd extension
        $palette = Palette::fromContents($img->__toString());

        return $palette->getMostUsedColors($number);
    }
}