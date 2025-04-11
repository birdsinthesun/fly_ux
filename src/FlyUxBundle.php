<?php
namespace Bits\FlyUxBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class FlyUxBundle extends Bundle
{
      public function getPath(): string
    {
        return \dirname(__DIR__);
    }
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config'));
        $loader->load('services.yaml');
    }
}
