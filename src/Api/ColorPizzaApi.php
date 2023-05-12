<?php

namespace ImagesBundle\Api;

use ImagesBundle\Api\ApiClient;

class ColorPizzaApi extends ApiClient {

	static protected string $url = "https://api.color.pizza/v1/";

	static public function getColorName(string | array $colorHex) {
		if (is_array($colorHex)) {
			$colorHex = implode(",", $colorHex);
		}
		$result = self::get("", [
			"query" => [
		        "values" => $colorHex,
		        "list" => "bestOf",
		    ]
		]);
		return $result ?? $result["colors"][0]["name"];
	}
}