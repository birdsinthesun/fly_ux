<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\Input;
use Contao\System;

#[AsCallback(table: 'tl_content', target: 'config.onbeforesubmit', method:'savePid')]
class ContentElementSaveListener
{
    public function savePid($Values, DataContainer $dc)
    {
        
        
        
        if (Input::get('op_add') === 'add_content_element') {

            
            $session = System::getContainer()->get('request_stack')->getSession();
            $pid = $session->getBag('contao_backend')->get('OP_ADD_PID');

            
            $dc->activeRecord->pid = $pid;
            $Values['pid'] = $pid;
           return $Values;
        }else{
            return $Values;
            }
        
    }
}


        