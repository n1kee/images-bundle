<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\TheColorApi;
use ImagesBundle\Api\Response\SuccessResponse;

class TheColorAdapter implements ImagesApiInterface {
	function __construct(
		protected TheColorApi $theColorApi
	) {
	}

	function getColorName($colorHex): Response {
		$response = $this->theColorApi->getColorInfo($colorHex);
		if ($response->success) {
			$colorName = $response->result["name"]["value"];
			return new SuccessResponse($colorName);
		}
		return $response;
	}
}