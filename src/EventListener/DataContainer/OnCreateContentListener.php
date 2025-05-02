<?php

namespace Bits\FlyUxBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\System;
use Contao\Input;


#[AsCallback(table: 'tl_content', target: 'config.oncreate')]
class OnCreateContentListener
{

    public function __invoke(
    $Table,
 $InsertID,
$record,
    DataContainer $dc): void
    {
       
        if(Input::get('act') !== 'create'&&Input::get('op_add') === 'add_content_element' ||Input::get('act') !== 'edit'){
            
            $session = System::getContainer()->get('request_stack')->getSession()->getBag('contao_backend');
            
            $dc->activeRecord->id = ($InsertID)?:Input::get('id');
            $dc->activeRecord->pid = $session->get('OP_ADD')['pid'];
            $record['id'] = ($InsertID)?:Input::get('id');
             $record['pid'] = $session->get('OP_ADD')['pid'];
           
            
           
            }
       
    }
}