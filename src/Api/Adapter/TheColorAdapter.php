<?php

namespace ImagesBundle\Api\Adapter;
use ImagesBundle\Api\Abstract\ImagesLocalDbAbstract;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\TheColorApi;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagesBundle\Api\Adapter\ImagickAdapter;
use FilesBundle\File;

class TheColorAdapter extends ImagesLocalDbAbstract {

	protected string $dbPath = __DIR__ . "/../data/thecolor.json";

    function __construct(protected ImagickAdapter $imagickAdapter) {
        $this->db = (new File($this->dbPath))->readJson()["colors"];
    }

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