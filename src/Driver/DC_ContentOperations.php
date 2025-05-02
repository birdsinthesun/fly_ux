<?php

namespace Bits\FlyUxBundle\Driver;

use Contao\Backend;
use Contao\System;
use Contao\Input;


class DC_ContentOperations extends Backend
{
        public function addElementButton($href, $label, $title,$class, $icon, $attributes)
    {
        $configService = System::getContainer()->get('fly_ux.config_service');
               
        //if($configService->useflyUxDriver()&&$configService->isContentTable())
          //  {
                $tokenManager = System::getContainer()->get('contao.csrf.token_manager');
                $token = $tokenManager->getDefaultTokenValue();
               
                
                return '<a class="'.$class.'" href="' . $this->getCurrentUrl().'/contao?do='.Input::get('do').'&'.
    $href . '&pid='.Input::get('id').'&mode='.Input::get('mode').'&table=tl_content&rt='.$token.'" title="' . $title . '"' . $attributes . '>' . $label . '</a>';
            
        //}else{
          //  return '';
          //  }
    }

    public function dragDropButton($href, $label, $title, $class,$icon, $attributes)
    {
         $configService = System::getContainer()->get('fly_ux.config_service');
               
       // if($configService->useflyUxDriver()&&$configService->isContentTable())
       // {
                return '<a class="'.$class.'" href="' .$this->getCurrentUrl().'/contao?do='.Input::get('do').'&mode='.Input::get('mode').'&pid='.
                Input::get('id').'&'. $href . '&rt='. Input::get('rt').'" title="' . $title . '"' . $attributes . '>' . $label . '</a>';
          //  }
        
    }
    public function dragDropDeaktivateButton($href, $label, $title, $class,$icon, $attributes)
    {
        $configService = System::getContainer()->get('fly_ux.config_service');
               
       // if($configService->useflyUxDriver()&&$configService->isContentTable())
       // {
            return '<a class="'.$class.'" href="' .$this->getCurrentUrl().'/contao?do='.Input::get('do').'&mode='.Input::get('mode').'&pid='.
            Input::get('id').'&'. $href . '&rt='. Input::get('rt').'" title="' . $title . '"' . $attributes . '>' . $label . '</a>';
            
          //  }
        
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