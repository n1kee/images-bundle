<?php

namespace ImagesBundle\Api;

class ApiResponse {
    function __construct(
        public readonly mixed $result,
        public readonly bool $success = true,
        public readonly mixed $error = null,
    ) {
    }
}