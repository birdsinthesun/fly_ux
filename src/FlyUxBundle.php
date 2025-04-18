<?php
namespace Bits\FlyUxBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Bits\FlyUxBundle\DependencyInjection\Compiler\RemoveContaoCallbackPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class FlyUxBundle extends Bundle
{
    
     public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
       
            new RemoveContaoCallbackPass(),
        PassConfig::TYPE_BEFORE_OPTIMIZATION,
        100
            
            
        );
    }

}
