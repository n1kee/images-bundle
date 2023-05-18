<?php

namespace ImagesBundle\Api\Response;
use ImagesBundle\Api\Abstract\Response;

/**
 * Class for successful responses from API's.
 */
class SuccessResponse extends Response {

    /**
     * Shows if request was successful.
     */
    public readonly bool $success;

    /**
     * Shows if request result was empty.
     */
    public readonly bool $empty;

    /**
     * Error object.
     */
    public readonly mixed $error;

    function __construct(
        /**
         * Request result.
         */
        public readonly mixed $result,
        /**
         * Shows if an exact match was found.
         */
        public readonly bool $exactMatch = true,
    ) {
        $this->success = true;
        $this->error = null;
        $this->empty = empty($result);
    }
}