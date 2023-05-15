<?php

namespace ImagesBundle\Api\Interface;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Interface\ResponseInterface;

interface ImagesApiInterface {
	function getColorName(string $colorHex): ResponseInterface;
}