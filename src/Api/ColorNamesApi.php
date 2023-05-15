<?php

namespace ImagesBundle\Api;

use ImagesBundle\Api\ApiClient;
use ImagesBundle\Api\Abstract\Response;

class ColorNamesApi extends ApiClient {

    protected string $url = "https://colornames.org/search/json/";

    public function getColorName(string $colorHex): Response {
        $colorHex = str_replace("#", "", $colorHex);
        return $this->get("", [
            "query" => [ "hex" => $colorHex, ]
        ]);
    }
}