<?php

namespace Bits\FlyUxBundle\Driver;

use Contao\Backend;
use Contao\System;
use Contao\Input;

class DC_ContentOperations extends Backend
{
        public function addElementButton($href, $label, $title,$class, $icon, $attributes)
    {
        if(Input::get('pid')!==Null){
            
            
            $session = System::getContainer()->get('request_stack')->getSession();
           $tokenManager = System::getContainer()->get('contao.csrf.token_manager');
        $token = $tokenManager->getDefaultTokenValue();
            $pid = (Input::get('mode')==='layout')?Input::get('pid'):$session->getBag('contao_backend')->get('OP_ADD_PID');
            
            return '<a class="'.$class.'" href="' . $this->getCurrentUrl().'/contao?do=content&'.
$href . '&pid='.$pid.'&table=tl_content&rt='.$token.'" title="' . $title . '"' . $attributes . '>' . $label . '</a>';
        }
    }

    public function dragDropButton($href, $label, $title, $class,$icon, $attributes)
    {
        
        if(Input::get('pid')!==Null){
            return '<a class="'.$class.'" href="' .$this->getCurrentUrl().'/contao?do=content&pid='.
            Input::get('pid').'&'. $href . '&rt='. Input::get('rt').'" title="' . $title . '"' . $attributes . '>' . $label . '</a>';
        }
    }
    public function dragDropDeaktivateButton($href, $label, $title, $class,$icon, $attributes)
    {
        if(Input::get('pid')!==Null){
            
        
        
            return '<a class="'.$class.'" href="' .$this->getCurrentUrl().'/contao?do=content&pid='.
            Input::get('pid').'&'. $href . '&rt='. Input::get('rt').'" title="' . $title . '"' . $attributes . '>' . $label . '</a>';
        }
    
    }

    public function getCurrentUrl()
    {
        // Zugriff auf den Service-Container
        $container = System::getContainer();
        
        // Holen des aktuellen Requests über die RequestStack-Komponente
        $requestStack = $container->get('request_stack');
        $request = $requestStack->getCurrentRequest();
    
        if ($request) {
            // Die komplette URL mit Protokoll (http/https)
            $currentUrl = $request->getUri();

            // Wenn du nur die Basis-URL ohne Query-Parameter benötigst
            $baseUrl = $request->getSchemeAndHttpHost() . $request->getBaseUrl();

            return $baseUrl;
        }

        return '';
    }
}
