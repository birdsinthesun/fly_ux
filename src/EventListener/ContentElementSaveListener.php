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
            
            $session = System::getContainer()->get('request_stack')->getSession()->getBag('contao_backend');
            $pid = $session->get('OP_ADD')['pid'];
            $ptable = $session->get('OP_ADD')['parentTable'];
        
            $record['id'] = Input::get('id');
            $record['pid'] = $pid;
            $record['parentTable'] = $ptable;
            
            return $record;
        
    }
}


        