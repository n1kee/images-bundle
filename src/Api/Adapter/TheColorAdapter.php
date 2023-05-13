<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\TheColorApi;

class TheColorAdapter implements ImagesApiInterface {
	static function getColorName($colorHex): string {
		$response = TheColorApi::getColorInfo($colorHex);
		return $response ? $response["colors"][0]["name"]: "";
	}
}