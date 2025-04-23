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
//var_dump(get_class_methods($factory
           // ->createItem('content')));exit;
        
        
            $contentNode = $tree->getChild('content');
             if (!$contentNode) {
                return;
            }
             $node = $factory
            ->createItem('content')
                ->setLabel('Inhalt')
                ->setLinkAttribute('title', 'Inhalte bearbeiten')
                
               // ->setCurrent('tl_content')
                ->setUri('contao?do=content')
            ;
            $newChildren = [];

        foreach ($contentNode->getChildren() as $key => $child) {
            $newChildren[$key] = $child;

            if ($key === 'page') {
                // Unseren Punkt direkt danach einfügen
                $newChildren['content'] = $node;
            }
        }

        // Vorherige Kinder ersetzen (mit neuer Reihenfolge)
        $contentNode->setChildren($newChildren);
            
            $contentNode->removeChild($factory->createItem('article'));
                
                
            
       
        
        
    }
}
