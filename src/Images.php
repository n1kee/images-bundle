<?php

namespace ImagesBundle;

use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;
use ImagesBundle\Api\Adapter\TheColorAdapter;
use ImagesBundle\Api\Adapter\ColorPizzaAdapter;
use ImagesBundle\Api\Adapter\NameThatColorAdapter;
use ImagesBundle\Api\ApiLoader;
use ourcodeworld\NameThatColor\ColorInterpreter;
use App\Storage\RequestHeadersStorage;
use Psr\Log\LoggerInterface;
use FilesBundle\Image;

class Images {

    function __construct(
        protected TheColorAdapter $theColorAdapter,
        protected ColorPizzaAdapter $colorPizzaAdapter,
        protected NameThatColorAdapter $ntcAdapter,
        protected ApiLoader $apiLoader,
        protected RequestHeadersStorage $requestHeadersStorage,
        protected LoggerInterface $logger,
    ) {
        $this->apiLoader->setApiAdapters(
            $theColorAdapter,
            $colorPizzaAdapter,
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

        $topColors = $this->getMostUsedColors($img, 10);

        $topColorsHex = [];

        $colorsFrequency = [];

        foreach($topColors as $color => $count) {

            $colorHex = Color::fromIntToHex($color);

            $topColorsHex[ $colorHex ] = $count;

            $colorName = $this->findColorName($colorHex, $matchColors);

            if ($colorName) {
                if (empty($colorsFrequency[ $colorName ])) {
                    $colorsFrequency[ $colorName ] = 0;
                }
                $colorsFrequency[ $colorName ] += $count;
            }
        }

        $this->logger->debug(json_encode([
            "image" => $img->getImageSignature(),
            "topColors " => $topColorsHex,
            "matchedColors" => $colorsFrequency,
        ], JSON_UNESCAPED_SLASHES));

        if (count($colorsFrequency)) {
            arsort($colorsFrequency);
            return array_key_first($colorsFrequency);  
        }

        return null;
    }

    function findColorName(string $colorHex, array $matchColors) {
        # TODO CHANGE TO API INTERATION UNTIL FIRST HIT
        $response = $this->apiLoader
            ->queryAll()
            ->getColorName($colorHex);
        $matchedColors = [];
        if ($response->success) {
            foreach ($response->result as $apiColorName) {
                $colorName = $this->matchColorName(
                    $apiColorName, $matchColors
                );
                $matchedColors[] = $colorName;
            }
        }
        return reset($matchedColors);
    }

    function getMostUsedColors(Image $img, int $number = 1) {

        # Needs ext-gd extension
        $palette = Palette::fromContents($img->__toString());

        return $palette->getMostUsedColors($number);
    }
}