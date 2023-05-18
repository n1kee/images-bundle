<?php

namespace ImagesBundle;

use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;
use ImagesBundle\Api\Adapter\TheColorAdapter;
use ImagesBundle\Api\Adapter\ColorPizzaAdapter;
use ImagesBundle\Api\Adapter\NameThatColorAdapter;
use ImagesBundle\Api\Adapter\ColorNamesAdapter;
use ImagesBundle\Api\Adapter\ImagickAdapter;
use ImagesBundle\Api\ApiManager;
use App\Storage\RequestHeadersStorage;
use Psr\Log\LoggerInterface;
use FilesBundle\Image;

/**
 * A service class for working with images.
 */
class ImagesService {

    function __construct(
        protected TheColorAdapter $theColorAdapter,
        protected ColorPizzaAdapter $colorPizzaAdapter,
        protected ColorNamesAdapter $colorNamesAdapter,
        protected NameThatColorAdapter $ntcAdapter,
        protected ImagickAdapter $imagickAdapter,
        protected ApiManager $apiManager,
        protected RequestHeadersStorage $requestHeadersStorage,
        protected LoggerInterface $logger,
    ) {
        /**
         * Set API adapters for the API manager.
         */
        $this->apiManager->setApiAdapters(
            $imagickAdapter,
            $theColorAdapter,
            $colorPizzaAdapter,
            $colorNamesAdapter,
            $ntcAdapter,
        );
    }

    /**
     * Match a color name to the list of names.
     * 
     * @param string $colorName
     * @param array $matchColors List of color names.
     * @return string Returns the matched color name.
     */
    function matchColorName(string $colorName, array $matchColors) {
        $colorNameMatches = [];

        foreach ($matchColors as $matchColor) {
            preg_match("/(?<=^| ){$matchColor}(?=$| )/i", $colorName, $matches, PREG_OFFSET_CAPTURE);
            if (count($matches)) {
                $colorMatch = reset($matches);
                $matchPosition = $colorMatch[ 1 ];
                $colorNameMatches[ $matchPosition ] = $matchColor;
            }
        }

        if (count($colorNameMatches)) {
            ksort($colorNameMatches);
            return end($colorNameMatches);
        }

        return null;
    }

    /**
     * Match a dominant color of the image to the list of color names.
     * 
     * @param Image $img
     * @param array $matchColors List of color names.
     * @return string Name of the dominant image color.
     */
    function matchImageColor(Image $img, array $matchColors) {

        $imgClone = $img->getClone();

        $imgClone->setMaxHeight(50);

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

    /**
     * Finds a color name from the list of colors.
     * 
     * @param string $colorHex HEX value of the color.
     * @param array $matchColors List of color names for matching.
     * @return string Color name.
     */
    function findColorName(string $colorHex, array $matchColors) {
        $response = $this->apiManager
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

    /**
     * Get's most used colors on the image.
     * 
     * @param Image $img
     * @param int $number How many most used colors to get.
     * @return array List of the most used colors on the image.
     */
    function getMostUsedColors(Image $img, int $number = 1) {
        # Needs ext-gd extension
        $palette = Palette::fromContents($img->__toString());

        return $palette->getMostUsedColors($number);
    }
}