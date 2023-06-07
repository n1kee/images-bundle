<?php

namespace ImagesBundle\Api\Abstract;

/**
 * Class for representing responses from API's
 *
 */
abstract class Response
{
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

    /**
     * Error object.
     */
    public readonly mixed $error;
}
