<?php

namespace Bits\FlyUxBundle\EventListener;

// src/EventListener/AddBackendAssetsListener.php
namespace App\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener]
class AddBackendAssetsListener
{
    public function __construct(private readonly ScopeMatcher $scopeMatcher)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        
        var_dump('test',RequestEvent->get('do') );exit;
        if (!$this->scopeMatcher->isBackendMainRequest($event)) {
            return;
        }
        if (RequestEvent->get('do') === 'files' && RequestEvent->get('view') === 'media') {
      
            $GLOBALS['TL_CSS'][] = 'bundles/birdsinthesun/fly_ux/assets/dc_media.css'/* add your CSS asset here */;
      //  $GLOBALS['TL_JAVASCRIPT'][] = /* add your JS asset here */;
        }
    }
}
