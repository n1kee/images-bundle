<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Abstract\ImagesLocalDbAbstract;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagesBundle\Api\Adapter\ImagickAdapter;
use FilesBundle\File;

/**
 * An adapter class for TheColor database of colors.
 */
class TheColorAdapter extends ImagesLocalDbAbstract {

    /**
     * Path to the database file.
     */
	protected string $dbPath = __DIR__ . "/../data/thecolor.json";

    function __construct(protected ImagickAdapter $imagickAdapter) {
        $this->db = (new File($this->dbPath))->readJson()["colors"];
    }

    /**
     * Get's the name of the color.
     * 
     * @param string $colorHex HEX value of the color.
     * @return Response Name of the color.
     */
   	function getColorName(string $colorHex): Response {
   		$colorName = null;
   		foreach ($this->db as $colorData) {
   			$matchFound = $this->isSimilarColor($colorHex, $colorData["hex"]);
   			if ($matchFound) {
   				$colorName = $colorData["name"];
   			} 
   		}
        return new SuccessResponse($colorName);
   	}
}