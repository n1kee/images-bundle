<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Interface\ResponseInterface;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagesBundle\Api\ColorNamesApi;

class ColorNamesAdapter implements ImagesApiInterface {

    function __construct(
        protected ColorNamesApi $colorNamesApi
    ) {
    }

    function getColorName(string $colorHex): ResponseInterface {
        $response = $this->colorNamesApi->getColorName($colorHex);
        $colorName = null;
        if ($response->success) {
            $colorName = $response->result["name"];
            return new SuccessResponse($colorName);
        }
        return $response;
    }
}