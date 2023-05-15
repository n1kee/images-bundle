<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Interface\ResponseInterface;
use ImagesBundle\Api\TheColorApi;
use ImagesBundle\Api\Response\SuccessResponse;

class TheColorAdapter implements ImagesApiInterface {
	function __construct(
		protected TheColorApi $theColorApi
	) {
	}

	function getColorName($colorHex): ResponseInterface {
		$response = $this->theColorApi->getColorInfo($colorHex);
		if ($response->success) {
			$colorName = $response->result["name"]["value"];
			return new SuccessResponse($colorName);
		}
		return $response;
	}
}