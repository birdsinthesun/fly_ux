<?php
namespace Bits\FlyUxBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MakeListenerPublicPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('Bits\FlyUxBundle\EventListener\BrowserBackButtonBraceListener')) {
            $definition = $container->getDefinition('Bits\FlyUxBundle\EventListener\BrowserBackButtonBraceListener');
            $definition->setPublic(true);
            $definition->addTag('kernel.event_listener', [
                'event' => 'kernel.request',
                'method' => 'onKernelRequest',
                'priority' => -3,
            ]);
        
        }
    }
}
