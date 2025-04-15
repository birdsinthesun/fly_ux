<?php

namespace Bits\FlyUxBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;

#[AsCallback(table: 'tl_content', target: 'edit.buttons')]
class EditButtonsCallbackListener
{
    public function __invoke(array $buttons, DataContainer $dc): array
    {
        // Remove the "Save and close" button
        unset($buttons['saveNedit']);

        return $buttons;
    }
}