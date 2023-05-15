<?php

namespace ImagesBundle\Api;

use ImagesBundle\Api\Response\SuccessResponse;

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

            foreach ($this->apiAdapters as $adapter) {
                $response = $adapter->{$methodName}(...$params);
                $result[] = $response->result;
            }

            return new SuccessResponse($result);
        } else {
            $response = null;

            foreach ($this->apiAdapters as $adapter) {
                $response = $adapter->{$methodName}(...$params);
                # TODO: REPLACE W EMPTY
                if ($response->success) break;
            }

            return $response;
        }
    }
}