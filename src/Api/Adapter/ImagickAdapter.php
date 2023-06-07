<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\ImagesApi;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagesBundle\Pixel;
use ImagickPixel;
use ImagickPixelException;
use Iterator;
use FilesBundle\File;

/**
 * An adapter class for working with Imagick API.
 */
class ImagickAdapter extends ImagesApi
{
    /**
     * Get's a data iterator.
     *
     * @return iterable | File
     */
    protected function getDataIterator(): iterable | File
    {
        /**
         * Known color names.
         */
        return [
            "Blue" => "#0000FF",
            "Red" => "#FF0000",
            "Green" => "#00FF00",
            "Black" => "#000000",
            "White" => "#FFFFFF",
            "Yellow" => "#FFFF00",
            "Gray" => "#808080",
            "Purple" => "#8000FF",
            "Orange" => "#FF8000",
        ];
    }

    /**
     * Parses a data entry.
     *
     * @param mixed $dataEntry
     * @param mixed $dataEntryKey
     * @return array Parsed data.
     */
    protected function parseDataEntry($dataEntry, $dataEntryKey = null): array
    {
        return [
            "name" => $dataEntry,
            "hex" => $dataEntryKey,
        ];
    }
}
