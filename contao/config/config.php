<?php
use Contao\CoreBundle\ContaoCoreBundle;

use Bits\FlyUxBundle\Pages\MyPageRegular;
use Bits\FlyUxBundle\Content\ContentContentSlider;
use Bits\FlyUxBundle\Content\ContentGrid;
use Bits\FlyUxBundle\Driver\DC_Content;
use Bits\FlyUxBundle\Widgets\ModuleWizard;
use Bits\FlyUxBundle\Content\ContentModule;


    $GLOBALS['TL_PTY']['regular'] = MyPageRegular::class;
    $GLOBALS['BE_FFL']['flyWizard']  = ModuleWizard::class;
    $GLOBALS['TL_CTE']['includes']['module'] = ContentModule::class;
    $GLOBALS['TL_CTE']['plus']['contentslider'] = ContentContentSlider::class;
    $GLOBALS['TL_CTE']['plus']['contentslider'] = ContentGrid::class;
    
    $GLOBALS['BE_MOD']['content']['page'] = ['tables' => ['tl_page']];
    $GLOBALS['BE_MOD']['content']['page']['config']  = [
                  'driver' => 'fly_ux',
                  'relations' => [
                    'tl_page', 
                    'tl_content'
                        ]
    ];
    $GLOBALS['BE_MOD']['content']['content'] = ['tables' => ['tl_content']];
    $GLOBALS['BE_MOD']['content']['content']['config'] = [ 
                  'driver' => 'fly_ux',
                  'relations' => [
                    'tl_page', 
                    'tl_content'
                        ]
  ];
    $GLOBALS['BE_MOD']['content']['calendar']['config']  = [
                'driver' => 'fly_ux',
                'relations' => [
                    'tl_calendar', 
                    'tl_calendar_events',
                    'tl_content'
                        ]
  ];
    $GLOBALS['BE_MOD']['content']['news']['config']  = [
                 'driver' => 'fly_ux',
                'relations' => [
                    'tl_news', 
                    'tl_content'
                        ]
  ];
  
        unset($GLOBALS['BE_MOD']['content']['article']);
        unset($GLOBALS['FE_MOD']['navigationMenu']['articlenav']);
        unset($GLOBALS['FE_MOD']['miscellaneous']['articlelist']);
        unset($GLOBALS['TL_CTE']['includes']['article']);
        unset($GLOBALS['TL_CTE']['includes']['content']);
        unset($GLOBALS['TL_CTE']['includes']['teaser']);
        unset($GLOBALS['TL_CTE']['includes']['alias']);
        unset($GLOBALS['TL_CTE']['legacy']['accordionSingle']);
        unset($GLOBALS['TL_CTE']['miscellaneous']['swiper']);
        //unset($GLOBALS['TL_MODELS']['tl_article']);
              