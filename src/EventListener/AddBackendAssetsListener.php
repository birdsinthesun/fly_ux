<?php

namespace Bits\FlyUxBundle\EventListener;

// src/EventListener/AddBackendAssetsListener.php

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 15)]
class AddBackendAssetsListener
{
    private ScopeMatcher $scopeMatcher;

    
    public function __construct(ScopeMatcher $scopeMatcher)
    {
        $this->scopeMatcher = $scopeMatcher;
    }

    public function __invoke(RequestEvent $event): void
    {
        var_dump('test', $event->getRequest()->get('do')); exit;

        if (!$this->scopeMatcher->isBackendMainRequest($event)) {
            return;
        }
        if ($event->getRequest()->get('do') === 'files' && $event->getRequest()->get('view') === 'media') {
            $GLOBALS['TL_CSS'][] = 'bundles/birdsinthesun/fly_ux/assets/dc_media.css';
        }
    }
}
