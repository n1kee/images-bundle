<?php

namespace ImagesBundle\Api\Abstract;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Adapter\ImagickAdapter;
use FilesBundle\File;

class ImagesLocalDbAbstract implements ImagesApiInterface {

	protected string $dbPath;

    function __construct(protected ImagickAdapter $imagickAdapter) {
        $this->db = new File($this->dbPath);
    }

   	protected function dbParseLine(string $line): array {
    }

   	function isSimilarColor(string $color1, string $color2 )
   	{
   		return $this->imagickAdapter->isSimilarColor($color1, $color2);
   	}

    function getColorName(string $colorHex): Response {

        $colorName = null;

        while (!$this->db->eof()) {
            $line = $this->dbParseLine($this->db->fgets());
            $matchFound = $this->isSimilarColor($colorHex, $line["hex"]);
            if ($matchFound) {
                $colorName = $line["name"];
                break;
            }
        }
        return new SuccessResponse($colorName);
    }
}