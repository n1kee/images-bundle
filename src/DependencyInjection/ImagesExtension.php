<?php

namespace ImagesExtension\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ImagesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $containerBuilder)
    {
        $loader = new YamlFileLoader(
            $containerBuilder,
            new FileLocator(__DIR__.'/../../config')
        );
        $loader->load('services.yaml');

        $this->addAnnotatedClassesToCompile([
            // you can define the fully qualified class names...
            // 'App\\Controller\\DefaultController',
            // ... but glob patterns are also supported:
            //'**Bundle\\Controller\\',

            // ...
        ]);
    }
}