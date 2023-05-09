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

    function save($path) {

    }

    function get() {
        return $this->file;
    }
}

class Image extends File {

    private Imagick $file;

    function __construct(string $filePath)
    {
        $this->file = new Imagick($filePath);
    }

    function __call($name, $args) {
        return $this->file->{$name}(...$args);
    }

    function getWidth()
    {
        return $this->file->getImageGeometry()["width"];
    }

    function getHeight()
    {
        return $this->file->getImageGeometry()["height"];
    }

    function setMaxWidth(float $width)
    {
        if ($this->getWidth() > $width) {
           return $this->resize($width); 
        }
        return null;
    }

    function setMaxHeight(float $height)
    {
        if ($this->getHeight() > $height) {
           return $this->resize(0, $height);
        }
        return null;
    }


    function resize(int $width, int $height)
    {
        $this->file->resizeImage(
            $width ?? 0, 
            $height ?? 0,
            Imagick::FILTER_LANCZOS,
            1
        );
    }

    function save($filePath) {
        $fileName = $this->file->getImageSignature();
        $this->file->writeImage("{$filePath}/{$fileName}");
    }
}