<?php

namespace ImagesBundle\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagesBundle\Pixel;
use Iterator;
use FilesBundle\File;

/**
 * Interface for image API's.
 */
class ImagesApi
{
    /**
     * Get's a data iterator.
     *
     * @return iterable | File
     */
    protected function getDataIterator(): iterable | File
    {
    }

    /**
     */
    protected function parseDataEntry($dataEntry, $dataEntryKey = null): array
    {
        return $dataEntry;
    }

    /**
     * Creates a list of approximate color names.
     *
     * @param string $colorHex HEX value of the color.
     * @return Response
     */
    public function guessColorName(string $colorHex): Response
    {

        $dataIterator = $this->getDataIterator();
        $closestColors = [];

        foreach ($dataIterator as $key => $value) {
            $line = $this->parseDataEntry($value);
            if (!$line["hex"]) {
                continue;
            }
            $colorComparison = Pixel::compareColors($colorHex, $line["hex"]);
            if ($colorComparison["similar"]) {
                $closestColors[ $line["name"] ] = $colorComparison["distance"];
            }
            if (!$colorComparison["distance"]) {
                break;
            }
        }

        return new SuccessResponse($closestColors);
    }
}
