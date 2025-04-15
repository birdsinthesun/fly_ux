<?php

namespace Bits\FlyUxBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\System;
use Contao\Input;


#[AsCallback(table: 'tl_content', target: 'config.oncreate')]
class ContentSubmitCallbackListener
{

    public function __invoke(
    $Table,
 $InsertID,
$Fields,
    DataContainer $dc): void
    {
       
        if(Input::get('act') !== 'create' ||Input::get('act') !== 'edit'){
            
             $session = System::getContainer()->get('request_stack')->getSession();
       
             $dc->activeRecord->pid = $session->getBag('contao_backend')->get('OP_ADD_PID');
               $Fields['pid']= $session->getBag('contao_backend')->get('OP_ADD_PID');
             $dc->activeRecord->ptable = 'tl_page';
             $Fields['ptable']= 'tl_page';
         // $this->dc = $dc ;
            
           
            }
       
    }
}