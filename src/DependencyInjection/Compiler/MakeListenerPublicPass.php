<?php
namespace Bits\FlyUxBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MakeListenerPublicPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('Bits\FlyUxBundle\Security\Voter\ContentParentVoter')) {
            $definition = $container->getDefinition('Bits\FlyUxBundle\Security\Voter\ContentParentVoter');
            $definition->setPublic(true);
            $definition->addTag('security.voter', [
                'priority' => 105,
            ]);
        } 
        if ($container->hasDefinition('Bits\FlyUxBundle\Operation\Children')) {
            $definition = $container->getDefinition('Bits\FlyUxBundle\Operation\Children');
            $definition->setPublic(true);
        
        }
     
    }
}
