<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Contao\DataContainer;

class RemoveNewOperationListener implements ServiceSubscriberInterface
{
    public function removeNewOperation(DataContainerOperation $operation, DataContainer $dc): void
    {
  
       
        if ($operation->getName() === 'new') {
                $operation->setHtml('');
            
        }
       
    }
    
     public static function getSubscribedServices(): array
    {
        return [];
    }
}
