<?php
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\Input;
use Contao\DataContainer;
use Bits\FlyUxBundle\Pages\MyPageRegular;
use Bits\FlyUxBundle\Content\ContentContentSlider;
use Bits\FlyUxBundle\Driver\DC_Content;


 $GLOBALS['TL_PTY']['regular'] = MyPageRegular::class;
  $GLOBALS['BE_MOD']['content']['content'] = ['tables' => ['tl_content']];
  $GLOBALS['BE_MOD']['content']['content_plus'] = ['tables' => ['tl_content']];
  unset($GLOBALS['BE_MOD']['content']['article']);
  
$GLOBALS['TL_CTE']['plus']['contentslider'] = ContentContentSlider::class;

 if(Input::get('do') === 'content'){
            
                $GLOBALS['TL_DCA']['tl_content']['config']['dataContainer']  = DC_Content::class;
           
  unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['new']);
             
 }
               