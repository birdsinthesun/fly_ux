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
        
        if ($event->getRequest()->get('do') === 'content') {
           
            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/dc_content.css';
            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/grid.css';
        }
        
        if ($event->getRequest()->get('op_dd') === 'drag_drop_mode') {
           /// $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/flyux/js/sortablejs.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/flyux/js/drag.js';
            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/drag.css';
        }
        
        
    }
}
