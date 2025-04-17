<?php


namespace Bits\FlyUxBundle\EventListener;

use Contao\DataContainer;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback(table: 'tl_page', target: 'config.onsubmit', priority: -15)]
class PageSubmitOverrideListener
{
    public function __invoke(DataContainer $dc): void
    {
        // Artikel-Erstellung blockieren – z. B. über Session-Flag
        $GLOBALS['disable_article_creation'] = true;
    }
}
