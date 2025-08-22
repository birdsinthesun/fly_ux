<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\System;
use Contao\Input;


#[AsCallback(table: 'tl_content', target: 'config.onbeforesubmit', method:'savePid', priority:-15)]
class ContentElementSaveListener
{
    public function savePid($record, DataContainer $dc):array
    {
            
            $session = System::getContainer()->get('request_stack')->getSession()->getBag('contao_backend');
            $pid = $session->get('OP_ADD_PID');
            $ptable = $session->get('OP_ADD_PTABLE');
        
            //$record['id'] = Input::get('id');
           // $record['pid'] = $pid;
           // $record['parentTable'] = $ptable;
            $record['ptable'] = $ptable;
            
            return $record;
        
    }
}


        