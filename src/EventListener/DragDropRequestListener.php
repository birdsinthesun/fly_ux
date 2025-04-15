<?php


namespace Bits\FlyUxBundle\EventListener;

use Contao\BackendUser;
use Contao\Database;
use Contao\BackendTemplate;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 0)]
class DragDropRequestListener
{
    public function __construct(private ScopeMatcher $scopeMatcher, private RequestStack $requestStack) {}

    public function __invoke(RequestEvent $event): void
    {
        if (!$this->scopeMatcher->isBackendRequest($event->getRequest())) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();

        if ($request->query->get('do') === 'content'
            && $request->query->get('op_dd') === 'drag_drop_mode'
        ) {
            // Dann z.B. eigenes Template rendern
            //$this->renderDragDropView();
        }
    }

    
}
