<?php

namespace ImagesBundle\Api;

use ImagesBundle\Api\Response\SuccessResponse;

/**
 * A class for managing API adapters.
 * 
 * @param Foo $param
 * @return Bar
 */
class ApiManager {

    function __construct(
        protected array $apiAdapters = [],
        protected bool $queryAllApis = false,
    ) {
    }

    /**
     * Returns an API manager for querying all API's.
     * 
     * @param ImagesApiInterface $adapters,... API adapters to be used.
     * @return ApiManager
     */
    function queryAll(...$adapters): ApiManager {
        $newApiManager = new ApiManager(
            $this->apiAdapters, true
        );
        return $newApiManager;
    }

    /**
     * Set's API adapters for the manager.
     * 
     * @param ImagesApiInterface $adapters,... API adapters to be set.
     */
    function setApiAdapters(...$adapters) {
        $this->apiAdapters = $adapters;
    }

    /**
     * Calls a method of API adapters.
     * 
     * Can call all API's or one by one until a first result.
     * 
     * @param string $methodName Name of the method
     * @param $params,... Parameters of the method.
     */
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
                if (!$response->empty) break;
            }

            return $response;
        }
    }
}