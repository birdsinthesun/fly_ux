<?php
namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Input;
use Contao\System;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class BrowserBackButtonBraceListener
{
    private RequestStack $requestStack;
    private ScopeMatcher $scopeMatcher;

    public function __construct(RequestStack $requestStack, ScopeMatcher $scopeMatcher)
    {
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Nur im Backend reagieren
        if (!$this->scopeMatcher->isBackendRequest($request)) {
            return;
        }

        //$session = $this->requestStack->getSession();
        $bag = System::getContainer()->get('request_stack')->getSession()->getBag('contao_backend');
//var_dump($bag->has('Fly_UX_RELOAD'),'test');exit;
        if ($bag->has('Fly_UX_RELOAD')&&Input::get('act') !== 'edit') {
            $bag->remove('Fly_UX_RELOAD');
            $bag->set('OP_ADD_MODE','layout');
            

             if (!$request->query->get('reloaded')) {
                $url = $request->getUri();
                $separator = strpos($url, '?') !== false ? '&' : '?';
                $redirectUrl = $url . $separator . 'reloaded=1';

                
                $response = new RedirectResponse($redirectUrl, 302);
                $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
                $event->setResponse($response);
            }
        }
    }
}
