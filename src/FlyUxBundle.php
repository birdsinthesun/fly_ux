<?php
namespace Bits\FlyUxBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class FlyUxBundle extends AbstractBundle
{
    public function loadExtension(
        array $config, 
        ContainerConfigurator $containerConfigurator, 
        ContainerBuilder $containerBuilder,
    ): void
    {
        $containerConfigurator->import('../config/bundles.php');
        $containerConfigurator->import('../config/services.yaml');
        //$containerConfigurator->import('../config/routes.yaml');
    }

}
