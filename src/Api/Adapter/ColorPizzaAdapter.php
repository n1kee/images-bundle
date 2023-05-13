<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\ColorPizzaApi;

class ColorPizzaAdapter implements ImagesApiInterface {
	static function getColorName($colorHex): string {
		$response = ColorPizzaApi::getColorInfo($colorHex);
		return $response ? $result["name"]["value"]: "";
	}
}