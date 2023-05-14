<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\TheColorApi;
use ImagesBundle\Api\ApiResponse;

class TheColorAdapter implements ImagesApiInterface {
	function __construct(
		protected TheColorApi $theColorApi
	) {
	}

	function getColorName($colorHex): ApiResponse {
		$response = $this->theColorApi->getColorInfo($colorHex);
		if ($response->success) {
			$colorName = $response->result["name"]["value"];
		}
		return new ApiResponse($colorName, $response->success);
	}
}