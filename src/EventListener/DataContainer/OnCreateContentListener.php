<?php

namespace Bits\FlyUxBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\System;
use Contao\Input;


#[AsCallback(table: 'tl_content', target: 'config.oncreate', priority:-15)]
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
            
            //$dc->activeRecord->id = ($InsertID)?:Input::get('id');
            $dc->activeRecord->ptable = $session->get('OP_ADD_PTABLE');
           // $record['id'] = ($InsertID)?:Input::get('id');
            $record['ptable'] = $session->get('OP_ADD_PTABLE');
           
            
           
            }
       
    }
}