<?php

namespace Bits\FlyUxBundle\EventListener\View;


class ContentLayoutModeCalendarListener
{
     public function getSettings($arrSettings): array
    {

            $arrSettings['ptable'] = 'tl_calendar_events';
            $arrSettings['headline'] = 'Event Details';
            $arrSettings['layoutClass'] = '';


                                     
            $arrSettings['htmlBlocks'] = [];
            $arrSettings['htmlBlocks']['container'] = [];
            $arrSettings['htmlBlocks']['container']['main'] = [];
            
            return $arrSettings;
    }
    
}