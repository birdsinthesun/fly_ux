<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

final class ContentLayoutModeCallback
{
    #[AsCallback(table: 'tl_content', target: 'view.settings', priority: 100)]
    public function addViewSettings(): array
    {
        return [];
    }
}
