<?php

namespace ImagesBundle\Api\Interface;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Abstract\Response;

interface ImagesApiInterface {
	function getColorName(string $colorHex): Response;
}