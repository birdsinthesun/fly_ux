<?php

namespace Bits\FlyUxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
class FlyUxExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // services.yaml im Bundle-Verzeichnis laden
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');
        $loader2 = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader2->load('bundles.php');
    }
}
