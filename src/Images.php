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
use Psr\Log\LoggerInterface;
use FilesBundle\Image;

class Images {

    function __construct(
        protected TheColorAdapter $theColorAdapter,
        protected ColorPizzaAdapter $colorPizzaAdapter,
        protected ImagesApi $imagesApi,
        protected ApiLoader $apiLoader,
        protected RequestHeadersStorage $requestHeadersStorage,
        protected LoggerInterface $logger,
    ) {
        $this->apiLoader->setApiAdapters(
            $theColorAdapter,
            $colorPizzaAdapter,
            $imagesApi,
        );
    }


    function matchImageColor(Image $img, array $matchColors) {

        $topColors = $this->getMostUsedColors($img, 10);

        $topColorsHex = [];

        $colorsFrequency = [];

        foreach($topColors as $color => $count) {

            $colorHex = Color::fromIntToHex($color);

            $topColorsHex[ $colorHex ] = $count;

            $colorName = $this->getColorName($colorHex);

            $colorNameMatches = [];

            foreach ($matchColors as $matchColor) {
                $matchPosition = stripos($colorName, $matchColor);
                if ($matchPosition !== false) {
                    $colorNameMatches[ $matchPosition ] = $matchColor;
                }
            }

            if (count($colorNameMatches)) {
                ksort($colorNameMatches);
                $colorNameFound = end($colorNameMatches);
                if (empty($colorsFrequency[ $colorNameFound ])) {
                    $colorsFrequency[ $colorNameFound ] = 0;
                }
                $colorsFrequency[ $colorNameFound ]++;
            }
        }

        $this->logger->debug(json_encode([
            "image" => $img->getImageSignature(),
            "topColors " => $topColorsHex,
            "matchedColors" => $colorsFrequency,
        ], JSON_UNESCAPED_SLASHES));

        if (count($colorsFrequency)) {
            arsort($colorsFrequency);
            return array_key_last($colorsFrequency);  
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