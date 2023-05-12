<?php

namespace ImagesBundle\Api\Interface;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface ImagesApiInterface {
	static function getColorName(string $colorHex): string;
}