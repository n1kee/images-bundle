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


    function guessColorName($rgb)
    {
        $colors = [
            "Blue" => 0x0000FF,
            "Red" => 0xFF0000,
            "Green" => 0x00FF00,
            "Black" => 0x000000,
            "White" => 0xFFFFFF,
            "Yellow" => 0xFFFF00,
            "Gray" => 0x808080,
            "Purple" => 0x8000FF,
            "Orange" => 0xFF8000,
        ];

        $largestDiff = 0;
        $closestColor = "";
        foreach ($colors as $name => $rgbColor) {

            $colorDiff = $this->getColorDiff($rgbColor,$rgb);
            if ($colorDiff > $largestDiff) {
                $largestDiff = $colorDiff;
                $closestColor = $name;
            }

        }
        return $closestColor;

    }

    function getColorDiff($rgb1,$rgb2)
    {
        // do the math on each tuple
        // could use bitwise operates more efficiently but just do strings for now.
        $red1   = hexdec(substr($rgb1,0,2));
        $green1 = hexdec(substr($rgb1,2,2));
        $blue1  = hexdec(substr($rgb1,4,2));

        $red2   = hexdec(substr($rgb2,0,2));
        $green2 = hexdec(substr($rgb2,2,2));
        $blue2  = hexdec(substr($rgb2,4,2));

        return pow(($red2 - $red1) * .299, 2) + 
            pow(($green2 - $green1) * .587, 2) + 
            pow(($blue2 - $blue1) * .114, 2);
    }
}