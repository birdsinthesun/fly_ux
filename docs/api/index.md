## API

### contao/config.php

Example for contao/calendar-bundle

´    $GLOBALS['BE_FLX_UX']['content']['calendar']['config']  = [
                'driver' => 'fly_ux',
                'relations' => [
                    'tl_calendar', 
                    'tl_calendar_events',
                    'tl_content'
                        ]´
                        
### Callbacks

#### fly_ux.driver_view

#### fly_ux.inColumn_Options