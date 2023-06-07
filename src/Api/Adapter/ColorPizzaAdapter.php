<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\ImagesLocalDb;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;

/**
 * An adapter class for the Color Pizza database of colors.
 *
 */
class ColorPizzaAdapter extends ImagesLocalDb
{
    /**
     * Path to the database file.
     */
    protected string $dbPath = __DIR__ . "/../data/colorpizza.csv";

    /**
     * Parses a data entry.
     *
     * @param mixed $dataEntry
     * @param mixed $dataEntryKey
     * @return array Parsed data.
     */
    protected function parseDataEntry($dataEntry, $dataEntryKey = null): array
    {
        $lineArray = explode(",", $dataEntry);
        return [
            "name" => $lineArray[0],
            "hex" => substr($lineArray[1], 1),
        ];
    }
}
