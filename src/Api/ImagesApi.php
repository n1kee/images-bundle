<?php

namespace ImagesBundle\Api;

use ourcodeworld\NameThatColor\ColorInterpreter;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\ApiResponse;

class ImagesApi implements ImagesApiInterface {
	function getColorName(string $colorHex): ApiResponse {
		$interpreter = new ColorInterpreter;

		$colorName = $interpreter->name($colorHex)["name"];

		return new ApiResponse($colorName);
	}
}