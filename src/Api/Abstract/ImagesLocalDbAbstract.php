<?php

namespace ImagesBundle\Api\Abstract;

use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Adapter\ImagickAdapter;
use FilesBundle\File;
use ImagesBundle\Api\Response\SuccessResponse;

/**
 * Class for working with local image databases.
 *
 */
class ImagesLocalDbAbstract implements ImagesApiInterface {

	protected string $dbPath;

    function __construct(protected ImagickAdapter $imagickAdapter) {
        $this->db = new File($this->dbPath);
    }


    /**
     * Parses a line from the database.
     * 
     * @param string $line A line to be read.
     * @return array Parsed data from the line.
     */
   	protected function dbParseLine(string $line): array {
    }

    /**
     * Checks if colors are similar.
     * 
     * @param string $color1 Hex code of the color.
     * @param string $color2 Hex code of the color.
     * @return bool Returns true if similar.
     */
   	function isSimilarColor(string $color1, string $color2 ): bool
   	{
   		return $this->imagickAdapter->isSimilarColor($color1, $color2);
   	}

    /**
     * Get's a color name by the color's HEX value.
     * 
     * @param string $colorHex HEX value of the color.
     * @return Response
     */
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