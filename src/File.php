<?php

namespace FilesBundle;

use Symfony\Component\DependencyInjection\Container;
use Imagick;
use SplFileObject;

class File {

    private $file;
    
    function __construct(string $filePath)
    {
        $this->file = new SplFileObject($filePath);
    }

    function save(string $filePath) {
    }
}

