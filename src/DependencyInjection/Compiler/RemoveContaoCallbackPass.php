<?php

namespace Bits\FlyUxBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RemoveContaoCallbackPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // Service-ID vom Original-Listener
        $serviceId = 'contao.listener.data_container.content_composition';

        if (!$container->hasDefinition($serviceId)) {
            return;
        }

        $definition = $container->getDefinition($serviceId);

        
        $tags = $definition->getTag('contao.callback');

        $filtered = array_filter($tags, function ($tag) {
            return !($tag['table'] === 'tl_page' && $tag['target'] === 'config.onsubmit');
        });

        $definition->clearTag('contao.callback');

        foreach ($filtered as $tag) {
            $definition->addTag('contao.callback', $tag);
            }
    }
}