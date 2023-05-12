<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\ColorPizzaApi;

class ColorPizzaAdapter implements ImagesApiInterface {
	static function getColorName($colorHex): string {
		$response = ColorPizzaApi::getColorName($colorHex);
		return $response ?? $response["colors"][0]["name"];
	}
}