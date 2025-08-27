## API 

Beispiel für contao/calendar-bundle

### contao/config.php



´$GLOBALS['BE_FLY_UX']['calendar']['config']  = [
                'driver' => 'fly_ux',
                'relations' => [
                    'tl_calendar', 
                    'tl_calendar_events',
                    'tl_content'
                        ],
                  'callbacks' => [
                    'view_settings' => ['Bits\FlyUxBundle\EventListener\View\ContentLayoutModeCalendarListener', 'getSettings']
                  
                  ]´
                        
### Callbacks
       ´

#### ContentLayoutModeEvent



´<?php

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
    
}´

