<?php

namespace ImagesBundle\Api\Interface;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface ImagesApiInterface {
	function getColorName(string $colorHex): string;
}