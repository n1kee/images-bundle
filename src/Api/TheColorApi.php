<?php

namespace ImagesBundle\Api;

use ImagesBundle\Api\ApiClient;

class TheColorApi extends ApiClient {

	protected static string $url = "https://www.thecolorapi.com/id";

	static public function getColorInfo(string | array $colorHex) {
		if (is_array($colorHex)) {
			$colorHex = implode(",", $colorHex);
		}
		$colorHex = str_replace("#", "", $colorHex);
		$result = self::get("", [
			"query" => [
		        "hex" => $colorHex,
		    ]
		]);
		var_dump("%%%%%%%{$colorHex}%%%%");
		var_dump($result ? $result["name"]["value"] : null);
		return $result;
	}
}