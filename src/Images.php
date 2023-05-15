<?php

namespace ImagesBundle;

use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;
use ImagesBundle\Api\Adapter\TheColorAdapter;
use ImagesBundle\Api\Adapter\ColorPizzaAdapter;
use ImagesBundle\Api\Adapter\NameThatColorAdapter;
use ImagesBundle\Api\Adapter\ColorNamesAdapter;
use ImagesBundle\Api\Adapter\ImagickAdapter;
use ImagesBundle\Api\ApiLoader;
use ourcodeworld\NameThatColor\ColorInterpreter;
use App\Storage\RequestHeadersStorage;
use Psr\Log\LoggerInterface;
use FilesBundle\Image;

class Images {

    function __construct(
        protected TheColorAdapter $theColorAdapter,
        protected ColorPizzaAdapter $colorPizzaAdapter,
        protected ColorNamesAdapter $colorNamesAdapter,
        protected NameThatColorAdapter $ntcAdapter,
        protected ImagickAdapter $imagickAdapter,
        protected ApiLoader $apiLoader,
        protected RequestHeadersStorage $requestHeadersStorage,
        protected LoggerInterface $logger,
    ) {
        $this->apiLoader->setApiAdapters(
            $imagickAdapter,
            $theColorAdapter,
            $colorPizzaAdapter,
            $colorNamesAdapter,
            $ntcAdapter,
        );
    }

    function matchColorName(string $colorName, array $matchColors) {
        $colorNameMatches = [];

        foreach ($matchColors as $matchColor) {
            $matchPosition = stripos($colorName, $matchColor);
            if ($matchPosition !== false) {
                $colorNameMatches[ $matchPosition ] = $matchColor;
            }
        }

        if (count($colorNameMatches)) {
            ksort($colorNameMatches);
            return end($colorNameMatches);
        }

        return null;
    }

    function matchImageColor(Image $img, array $matchColors) {

        $imgClone = $img->getClone();

        $imgClone->setMaxHeight(10);

        $topColors = $this->getMostUsedColors($imgClone, 10);

        $topColorsSum = array_sum($topColors);

        $topColorsHex = [];

        $colorsFrequency = [];

        $colorMap = [];

        foreach($topColors as $color => $count) {

            $colorHex = Color::fromIntToHex($color);

            $topColorsHex[ $colorHex ] = $count;

            $colorName = $this->findColorName($colorHex, $matchColors);

            if ($colorName) {
                if (empty($colorsFrequency[ $colorName ])) {
                    $colorsFrequency[ $colorName ] = 0;
                }
                $colorsFrequency[ $colorName ] += $count;
                $colorMap[ $colorHex ] = $colorName;
            }
        }

        $this->logger->debug(json_encode([
            "image" => $img->getImageSignature(),
            "topColors " => $topColorsHex,
            "matchedColors" => $colorsFrequency,
            "matchedColorsMap" => $colorMap,
        ], JSON_UNESCAPED_SLASHES));

        if (count($colorsFrequency)) {
            arsort($colorsFrequency);
            $dominantColorName = array_key_first($colorsFrequency);
            $dominantColorFrequncy = $colorsFrequency[ $dominantColorName ];
            $dominantColorPercentage = $dominantColorFrequncy / $topColorsSum;
            if ($dominantColorPercentage > .5) {
                return $dominantColorName; 
            }
        }

        return null;
    }

    function findColorName(string $colorHex, array $matchColors) {
        $response = $this->apiLoader
            ->queryAll()
            ->getColorName($colorHex);
        $colorsFrequency = [];
        if ($response->success) {
            foreach ($response->result as $apiColorName) {
                if (!$apiColorName) continue;

                $colorName = $this->matchColorName(
                    $apiColorName, $matchColors
                );

                if ($colorName) {
                    if (empty($colorsFrequency[$colorName])) {
                        $colorsFrequency[ $colorName ] = 0;
                    }
                    $colorsFrequency[ $colorName ]++;
                }
            }
        }
        arsort($colorsFrequency);
        return array_key_first($colorsFrequency);
    }

    function getMostUsedColors(Image $img, int $number = 1) {

        # Needs ext-gd extension
        $palette = Palette::fromContents($img->__toString());

        return $palette->getMostUsedColors($number);
    }
}