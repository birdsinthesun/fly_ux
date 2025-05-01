<?php
namespace Bits\FlyUxBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RemoveNewOperationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('Bits\FlyUxBundle\EventListener\RemoveNewOperationListener')) {
            return;
        }

        $definition = $container->findDefinition('Bits\FlyUxBundle\EventListener\RemoveNewOperationListener');
        $definition->setPublic(true);
       
        $definition->addTag('contao.callback', [
                'table' => 'tl_content',
                'method' => 'removeNewOperation',
                'target' => 'contao.listener.data_container.default_global_operations',
                'priority' => -16,
            ]);
        $definition->addArgument('$operation');
        }
   
}