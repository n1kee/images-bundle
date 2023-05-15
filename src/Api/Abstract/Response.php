<?php

namespace ImagesBundle\Api\Abstract;

abstract class Response {
    public readonly bool $success;
    public readonly mixed $result;
    public readonly bool $exactMatch;
    public readonly mixed $error;
    public readonly bool $empty;
}