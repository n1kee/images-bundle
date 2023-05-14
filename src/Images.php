<?php

namespace ImagesBundle;

use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;

use ImagesBundle\Api\Adapter\TheColorAdapter;
use ImagesBundle\Api\Adapter\ColorPizzaAdapter;
use ImagesBundle\Api\ImagesApi;
use ImagesBundle\Api\ApiLoader;

use ourcodeworld\NameThatColor\ColorInterpreter;

use App\Storage\RequestHeadersStorage;

use FilesBundle\Image;

class Images {

    function __construct(
        protected TheColorAdapter $theColorAdapter,
        protected ColorPizzaAdapter $colorPizzaAdapter,
        protected ImagesApi $imagesApi,
        protected ApiLoader $apiLoader,
        protected RequestHeadersStorage $requestHeadersStorage,
    ) {
        $this->apiLoader->setApiAdapters(
            $theColorAdapter,
            $colorPizzaAdapter,
            $imagesApi,
        );
    }


    function matchImageColor(Image $img, array $matchColors) {

        $topColors = $this->getMostUsedColors($img, 10);

        foreach($topColors as $color => $count) {

            $colorHex = Color::fromIntToHex($color);

            $colorName = $this->getColorName($colorHex);

            foreach ($matchColors as $matchColor) {
                if (stripos($colorName, $matchColor) !== false) {
                    return $matchColor;
                }
            }
        }
        return null;
    }

    function getColorName($colorHex) {
        return $this->apiLoader->getColorName($colorHex);
    }

    function getMostUsedColors(Image $img, int $number = 1) {

        # Needs ext-gd extension
        $palette = Palette::fromContents($img->__toString());

        return $palette->getMostUsedColors($number);
    }
}