<?php

namespace ImagesBundle\Api;

use ImagesBundle\Api\ApiClient;
use ImagesBundle\Api\Abstract\Response;

class TheColorApi extends ApiClient {

	protected string $url = "https://www.thecolorapi.com/id";

	public function getColorInfo(string | array $colorHex): Response {
		if (is_array($colorHex)) {
			$colorHex = implode(",", $colorHex);
		}
		$colorHex = str_replace("#", "", $colorHex);
		return $this->get("", [
			"query" => [
		        "hex" => $colorHex,
		    ]
		]);
	}
}