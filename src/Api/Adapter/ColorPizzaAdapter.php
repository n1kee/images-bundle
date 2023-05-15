<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\ColorPizzaApi;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;

class ColorPizzaAdapter implements ImagesApiInterface {
	function __construct(
		protected ColorPizzaApi $colorPizzaApi
	) {
	}

	function getColorName($colorHex): Response {
		$response = $this->colorPizzaApi->getColorInfo($colorHex);
		$colorName = null;
		if ($response->success) {
			$colorName = $response->result["colors"][0]["name"];
			return new SuccessResponse($colorName);
		}
		return $response;
	}
}