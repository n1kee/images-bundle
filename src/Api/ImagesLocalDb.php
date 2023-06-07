<?php

namespace ImagesBundle\Api;

use ImagesBundle\Api\Abstract\Response;
use ImagesBundle\Api\ImagesApi;
use FilesBundle\File;
use ImagesBundle\Api\Response\SuccessResponse;
use ImagesBundle\Pixel;
use Iterator;

/**
 * Class for working with local image databases.
 */
class ImagesLocalDb extends ImagesApi
{
    protected string $dbPath;

    /**
     * Get's a data iterator.
     *
     * @return iterable | File
     */
    public function getDataIterator(): iterable | File
    {
        return new File($this->dbPath);
    }
}
