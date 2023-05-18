<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\Abstract\ImagesLocalDbAbstract;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;
use FilesBundle\File;
use ImagesBundle\Api\Adapter\ImagickAdapter;

/**
 * An adapter class for the ColorNames database of colors.
 * 
 */
class ColorNamesAdapter extends ImagesLocalDbAbstract {
    /**
     * Path to the database file.
     */
    protected string $dbPath = __DIR__ . "/../data/colornames.txt";

    /**
     * Parses a line from the database.
     * 
     * @param string $line A line to be parsed.
     * @return array Parsed data from the line.
     */
    protected function dbParseLine(string $line): array {
        $lineArray = explode(",", $line);
        return [
            "hex" => $lineArray[0] ?? "",
            "name" => $lineArray[1] ?? "",
        ];
    }
}