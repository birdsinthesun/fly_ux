<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\PageModel;
use Contao\LayoutModel;
use Contao\FilesModel;
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
        
        if ($event->getRequest()->get('table') === 'tl_content') {
           
            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/dc_content.css';
           
        }
        if (($event->getRequest()->get('mode') === 'layout'||$event->getRequest()->get('mode') === 'plus')&&!$event->getRequest()->get('act')) {
                    
                        $page = PageModel::findOneBy('id',$event->getRequest()->get('id'));
                    if($page){
                            $layout = LayoutModel::findOneBy('id',$page->loadDetails()->layout);

                        if ($layout === null) {
                            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/grid.css';
                          
                        }

                        $uuid = $layout->be_grid;
           
                        // Falls kein Wert vorhanden, abbrechen
                        if (!$uuid) {
                            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/grid.css';
                           
                        }

                        // UUID in Pfad auflösen
                        $file = FilesModel::findByUuid($uuid);

                        if ($file !== null) {
                            $GLOBALS['TL_CSS'][] = $file->path;
      
                        }         
                         
                    }else{
                            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/grid.css';
                    
                    } 
             
        }
      
        if ($event->getRequest()->get('op_dd') === 'drag_drop_mode') {
           
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/flyux/js/drag.js';
           
            $GLOBALS['TL_CSS'][] = 'bundles/flyux/css/drag.css';
            
        }
        
        
    }
}
