<?php

namespace FilesBundle\Helper;

class FileSystem {
	static function createPath(string ...$pathParts): string {
		$pathString = implode(DIRECTORY_SEPARATOR, $pathParts);
		return preg_replace(
			"/(\/|\\\){2,}/",
			DIRECTORY_SEPARATOR,
			"/{$pathString}"
		);
	}
}
