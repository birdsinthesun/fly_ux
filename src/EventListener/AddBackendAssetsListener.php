<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener]
class AddBackendAssetsListener
{
    private ScopeMatcher $scopeMatcher;

    
    public function __construct(ScopeMatcher $scopeMatcher)
    {
        $this->scopeMatcher = $scopeMatcher;
    }

    public function __invoke(RequestEvent $event): void
    {
        
        if (!$this->scopeMatcher->isBackendMainRequest($event)) {
            return;
        }
       
        if ($event->getRequest()->get('do') === 'files') {
           
            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/dc_media.css';
        }
    }
}
