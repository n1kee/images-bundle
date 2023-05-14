<?php

namespace ImagesBundle\Api;

class ApiLoader {

    function setApiAdapters(...$adapters) {
        $this->apiAdapters = $adapters;
    }

    function __call(string $methodName, $params)
    {   
        $result = null;

        foreach ($this->apiAdapters as $adapter) {
            // try {
                $result = $adapter->{$methodName}(...$params);
            // } catch(\Exception $exc) {
            //     continue;
            // }
            
            return $result;
        }
    }
}