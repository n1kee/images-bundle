<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\ApiResponse;
use ImagesBundle\Api\ColorNamesApi;

class ColorNamesAdapter implements ImagesApiInterface {

    function __construct(
        protected ColorNamesApi $colorNamesApi
    ) {
    }

    function getColorName(string $colorHex): ApiResponse {
        $response = $this->colorNamesApi->getColorName($colorHex);
        $colorName = null;
        if ($response->success) {
            $colorName = $response->result["name"];
        }
        return new ApiResponse($colorName, $response->success);
    }
}