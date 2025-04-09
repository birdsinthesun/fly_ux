<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\InjectContainer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Contao\Frontend;

class PageRegularSubscriber implements EventSubscriberInterface
{
    /**
     * @InjectContainer
     */
    private $container;

    public static function getSubscribedEvents()
    {
        return [
            'contao.page_regular' => 'onPageRegular',
        ];
    }

    public function onPageRegular($event)
    {
        $event->setPageRegular(new \Bits\FlyUxBundle\Pages\PageRegular());
    }
}
