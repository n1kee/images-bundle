<?php

namespace ImagesBundle\Api;

use ourcodeworld\NameThatColor\ColorInterpreter;
use ImagesBundle\Api\Interface\ImagesApiInterface;

class ImagesApi implements ImagesApiInterface {
	static function getColorName(string $colorHex): string {
		$interpreter = new ColorInterpreter;

		return $interpreter->name($colorHex)["name"];
	}
}