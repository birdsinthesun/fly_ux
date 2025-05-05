## API 

Beispiel für contao/calendar-bundle

### contao/config.php



´    $GLOBALS['BE_FLX_UX']['content']['calendar']['config']  = [
                'driver' => 'fly_ux',
                'relations' => [
                    'tl_calendar', 
                    'tl_calendar_events',
                    'tl_content'
                        ]´
                        
### Events

#### ContentLayoutModeEvent



´<?php
// src\EventListener\View\ContentLayoutModeCalendarListener


namespace Bits\FlyUxBundle\EventListener\View;

use Bits\FlyUxBundle\DependencyInjection\Event\ContentLayoutModeEvent;

class ContentLayoutModeCalendarListener
{
    public function __invoke(ContentLayoutModeEvent $event): void
    {
            $arrSettings = $event->getDetailViewSettings();

            $arrSettings['ptable'] = 'tl_calendar_events';
            $arrSettings['headline'] = 'Event Details';
            $arrSettings['layoutClass'] = '';


                                     
            $arrSettings['htmlBlocks'] = [];
            $arrSettings['htmlBlocks']['container'] = [];
            $arrSettings['htmlBlocks']['container']['main'] = [];
            
            $event->setDetailViewSettings($arrSettings);
    }
    
}´

