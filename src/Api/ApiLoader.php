<?php

namespace ImagesBundle\Api;

class ApiLoader {

    protected array $apiAdapters;
    protected bool $queryAllApis = false;

    function setApiAdapters(...$adapters) {
        $this->apiAdapters = $adapters;
    }

    function getApiAdapters() {
        return $this->apiAdapters;
    }

    function __call(string $methodName, $params)
    {   
        $response = null;

        foreach ($this->apiAdapters as $adapter) {
            $response = $adapter->{$methodName}(...$params);
            if ($response->success) break;
        }

        return $response;
    }
}