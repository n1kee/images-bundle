<?php

namespace ImagesBundle\Api\Interface;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Abstract\Response;

/**
 * Interface for image API's.
 */
interface ImagesApiInterface {
    /**
     * Get's the name of the color.
     * 
     * @param string $colorHex HEX value of the color.
     * @return Response Name of the color.
     */
	function getColorName(string $colorHex): Response;
}