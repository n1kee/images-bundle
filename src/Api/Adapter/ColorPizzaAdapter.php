<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\ColorPizzaApi;
use ImagesBundle\Api\Abstract\ImagesLocalDbAbstract;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;

class ColorPizzaAdapter extends ImagesLocalDbAbstract {

	protected string $dbPath = __DIR__ . "/../data/colorpizza.csv";

   	protected function dbParseLine(string $line): array {
        $lineArray = explode(",", $line);
        return [
            "name" => $lineArray[0],
            "hex" => substr($lineArray[1], 1),
        ];
    }
}