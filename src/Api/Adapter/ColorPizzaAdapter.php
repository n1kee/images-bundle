<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Abstract\ImagesLocalDbAbstract;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;

/**
 * An adapter class for the Color Pizza database of colors.
 * 
 */
class ColorPizzaAdapter extends ImagesLocalDbAbstract {
    /**
     * Path to the database file.
     */
	protected string $dbPath = __DIR__ . "/../data/colorpizza.csv";

    /**
     * Parses a line from the database.
     * 
     * @param string $line A line to be parsed.
     * @return array Parsed data from the line.
     */
   	protected function dbParseLine(string $line): array {
        $lineArray = explode(",", $line);
        return [
            "name" => $lineArray[0],
            "hex" => substr($lineArray[1], 1),
        ];
    }
}