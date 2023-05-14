<?php

namespace ImagesBundle\Api;

use ImagesBundle\Api\ApiResponse;

class ApiLoader {

    function __construct(
        protected array $apiAdapters = [],
        protected bool $queryAllApis = false,
    ) {
    }

    function queryAll(...$adapters) {
        $newApiLoader = new ApiLoader(
            $this->apiAdapters, true
        );
        return $newApiLoader;
    }

    function setApiAdapters(...$adapters) {
        $this->apiAdapters = $adapters;
    }

    function __call(string $methodName, $params)
    {   
        if ($this->queryAllApis) {
            $result = [];
            $responceSuccess = false;

            foreach ($this->apiAdapters as $adapter) {
                $response = $adapter->{$methodName}(...$params);
                $result[] = $response->result;
                $responceSuccess = $responceSuccess || $response->success;
            }

            return new ApiResponse($result, $responceSuccess);
        } else {
            $response = null;

            foreach ($this->apiAdapters as $adapter) {
                $response = $adapter->{$methodName}(...$params);
                if ($response->success) break;
            }

            return $response;
        }
    }
}