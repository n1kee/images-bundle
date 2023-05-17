<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\Interface\ImagesApiInterface;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;
use FilesBundle\File;
use ImagickPixel;
use Exception;
use ImagesBundle\Api\Adapter\ImagickAdapter;

class ColorNamesAdapter implements ImagesApiInterface {

    protected string $dbPath = __DIR__ . "/../data/colornames.txt";

    function __construct(protected ImagickAdapter $imagickAdapter) {
        $this->db = new File($this->dbPath);
    }

    function getColorName(string $colorHex): Response {

        $colorName = null;

        while (!$this->db->eof()) {
            $line = explode(",", $this->db->fgets());
            $compareToHex = $line[0];
            $similarColorFound = $this->imagickAdapter->isSimilarColor($colorHex, $compareToHex);
            if ($similarColorFound) {
                $colorName = $line[1];
                break;
            }
        }
        return new SuccessResponse($colorName);
    }
}