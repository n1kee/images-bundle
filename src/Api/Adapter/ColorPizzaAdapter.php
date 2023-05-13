<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\ColorPizzaApi;

class ColorPizzaAdapter implements ImagesApiInterface {
	function __construct(
		protected ColorPizzaApi $colorPizzaApi
	) {
	}

	function getColorName($colorHex): string {
		$response = $this->colorPizzaApi->getColorInfo($colorHex);
		return $response ? $result["name"]["value"]: "";
	}
}