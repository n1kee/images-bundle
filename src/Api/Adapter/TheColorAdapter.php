<?php

namespace ImagesBundle\Api\Adapter;

use ImagesBundle\Api\ImagesLocalDb;
use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagesBundle\Api\Adapter\ImagickAdapter;
use ImagesBundle\Pixel;
use FilesBundle\File;
use Iterator;

/**
 * An adapter class for TheColor database of colors.
 */
class TheColorAdapter extends ImagesLocalDb
{
    /**
     * Path to the database file.
     */
    protected string $dbPath = __DIR__ . "/../data/thecolor.json";

    /**
     * Get's a data iterator.
     *
     * @return iterable | File
     */
    public function getDataIterator(): iterable | File
    {
        return (new File($this->dbPath))->readJson()["colors"];
    }
}
