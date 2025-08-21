<?php

namespace Bits\FlyUxBundle\Driver\Operations;

use Contao\System;
use Contao\Input;
use Contao\Backend;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;

class DragDrop 
{
    public function getButton(
    ): string
    {
       
        return sprintf(
            '<a href="'.$this->getHref().'" class="header_drag_drop" title="%s">%s</a> ',
            StringUtil::specialchars($this->getLabel()),
            $this->getLabel()
        );
    }
    
     public function getToken()
    {
        $container = System::getContainer();
        $tokenManager = $container->get('contao.csrf.token_manager');
        //$token = $tokenManager->getToken('contao.csrf.token')->getValue();
        return $tokenManager->getDefaultTokenValue();
    }
     public function getName(): string
    {
        return 'drag_drop_mode';
    }

    public function getHref(): string
    {
       
        $href = 'op_dd=drag_drop_mode';
       
        return $this->getCurrentUrl(true).'/contao?do='.Input::get('do').'&'.
    $href . '&id='.Input::get('id').'&mode='.Input::get('mode').'&table=tl_content&rt='.$this->getToken();
    
    }

    public function getLabel(): string
    {
        return 'Drag & Drop';
    }

    public function getIcon(): ?string
    {
        return 'bundles/flybills/icons/canceled.svg';
    }


    public function isAvailable(array $row): bool
    {
        // Optional: Zeige die Operation nur, wenn z.B. Status = 'pending'
        return true;//$row['status'] === 'pending';
    }
    
    public function getCurrentUrl($baseOnly=true)
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

            return ($baseOnly)?$baseUrl:$currentUrl ;
        }

        return '';
    }

    
}