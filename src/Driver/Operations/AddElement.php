<?php

namespace Bits\FlyUxBundle\Driver\Operations;

use Contao\System;
use Contao\Input;
use Contao\Backend;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;

class AddElement 
{
    public function getButton(
    ): string
    {
       
        return sprintf(
            '<a href="'.$this->getHref().'" class="header_new_element" title="%s">%s</a> ',
            StringUtil::specialchars($this->getLabel()),
            $this->getLabel()
        );
    }
    
     public function getToken()
    {
        $container = System::getContainer();
        $tokenManager = $container->get('contao.csrf.token_manager');
        $token = $tokenManager->getToken('contao.csrf.token')->getValue();
    }
     public function getName(): string
    {
        return 'add_content_element';
    }

    public function getHref(): string
    {
       
       $configService = System::getContainer()->get('fly_ux.config_service');
        $sessionBag = System::getContainer()->get('request_stack')->getSession()->getBag('contao_backend');
        $href = 'op_add=add_content_element&act=create';
        $href .= ($sessionBag->get('OP_ADD_PTABLE'))?'&ptable='.$sessionBag->get('OP_ADD_PTABLE'):'';
                $tokenManager = System::getContainer()->get('contao.csrf.token_manager');
                $token = $tokenManager->getDefaultTokenValue();
                
        return $this->getCurrentUrl(true).'/contao?do='.Input::get('do').'&'.
    $href . '&pid='.Input::get('id').'&mode='.Input::get('mode').'&table=tl_content&rt='.$token;
    }

    public function getLabel(): string
    {
        return 'Neu';
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