<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\System;
use Contao\Input;


#[AsCallback(table: 'tl_content', target: 'config.onbeforesubmit', method:'savePid')]
class ContentElementSaveListener
{
    public function savePid($record, DataContainer $dc):array
    {
        
        
        
        if (Input::get('do') === 'content'&&Input::get('op_add') === 'add_content_element') {

            
            $session = System::getContainer()->get('request_stack')->getSession();
            $pid = $session->getBag('contao_backend')->get('OP_ADD_PID');
            $ptable = $session->getBag('contao_backend')->get('OP_ADD_PTABLE');
        
            $record['id'] = Input::get('id');
            $record['pid'] = $pid;
            $record['parentTable'] = $ptable;
            
           
        }
            
            return $record;
        
    }
}


        