<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\MenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;



#[AsEventListener(ContaoCoreEvents::BACKEND_MENU_BUILD, method: '__invoke' , priority: 0)]
class BackendMenuListener
{
    public function __invoke(MenuEvent $event): void
    {
        $factory = $event->getFactory();
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName()) {
            return ;
        }
//var_dump(get_class_methods($tree));exit;
        $contentNode = $tree->getChild('content');

        $node = $factory
            ->createItem('content')
                ->setLabel('Inhalt')
                ->setCurrent('tl_content')
                ->setLinkAttribute('title', 'Inhalte bearbeiten')
                ->setUri('contao?do=content')
        ;

        $contentNode->addChild($node);
        $contentNode->removeChild($factory->createItem('article'));
        
        
    }
}
