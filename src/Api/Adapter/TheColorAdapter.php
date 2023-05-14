<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\TheColorApi;

class TheColorAdapter implements ImagesApiInterface {
	function __construct(
		protected TheColorApi $theColorApi
	) {
	}

	function getColorName($colorHex): string {
		$response = $this->theColorApi->getColorInfo($colorHex);
		return $response ? $response["name"]["value"]: "";
	}
}