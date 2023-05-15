<?php

namespace ImagesBundle\Api\Response;
use ImagesBundle\Api\Abstract\Response;

class SuccessResponse extends Response {
    public readonly bool $success;
    public readonly mixed $error;
    public readonly bool $empty;

    function __construct(
        public readonly mixed $result,
        public readonly bool $exactMatch = true,
    ) {
        $this->success = true;
        $this->error = null;
        $this->empty = empty($result);
    }
}