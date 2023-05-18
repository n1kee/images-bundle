<?php

namespace ImagesBundle\Api\Response;
use ImagesBundle\Api\Abstract\Response;

/**
 * Class for error responses from API's.
 */
class ErrorResponse extends Response {
    /**
     * Shows if an exact match was found.
     */
    public readonly bool $exactMatch;

    /**
     * Request result.
     */
    public readonly mixed $result;

    /**
     * Shows if request was successful.
     */
    public readonly bool $success;

    /**
     * Shows if request result was empty.
     */
    public readonly bool $empty;

    function __construct(
        /**
         * Error object.
         */
        public readonly mixed $error = null,
    ) {
        $this->exactMatch = false;
        $this->result = null;
        $this->success = false;
        $this->empty = true;
    }
}