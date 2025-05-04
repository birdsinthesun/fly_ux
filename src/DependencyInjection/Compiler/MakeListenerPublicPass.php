<?php
namespace Bits\FlyUxBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MakeListenerPublicPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('Bits\FlyUxBundle\Security\Voter\ContentVoter')) {
            $definition = $container->getDefinition('Bits\FlyUxBundle\Security\Voter\ContentVoter');
            $definition->setPublic(true);
            $definition->addTag('security.voter', [
                'priority' => 105,
            ]);
        
        }
    }
}
