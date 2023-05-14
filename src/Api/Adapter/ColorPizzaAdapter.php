<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\ColorPizzaApi;
use ImagesBundle\Api\ApiResponse;

class ColorPizzaAdapter implements ImagesApiInterface {
	function __construct(
		protected ColorPizzaApi $colorPizzaApi
	) {
	}

	function getColorName($colorHex): ApiResponse {
		$response = $this->colorPizzaApi->getColorInfo($colorHex);
		$colorName = null;
		if ($response->success) {
			$colorName = $response->result["colors"][0]["name"];
		}
		return new ApiResponse($response->success, $colorName);
	}
}