<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\MenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;



#[AsEventListener(ContaoCoreEvents::BACKEND_MENU_BUILD, method: '__invoke' , priority: -255)]
class BackendMenuListener
{
    public function __invoke(MenuEvent $event): void
    {
        $factory = $event->getFactory();
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName()) {
            return ;
        }
        
        $contentNode = $tree->getChild('content');
         if (!$contentNode) {
            return;
        }

        $contentNode->removeChild($factory->createItem('content'));
      
    }
}
