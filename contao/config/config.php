<?php
use Contao\CoreBundle\ContaoCoreBundle;

use Bits\FlyUxBundle\Pages\MyPageRegular;
use Bits\FlyUxBundle\Content\ContentContentSlider;
use Bits\FlyUxBundle\Content\ContentGrid;



 $GLOBALS['TL_PTY']['regular'] = MyPageRegular::class;
  $GLOBALS['BE_MOD']['content']['content'] = ['tables' => ['tl_content']];
  unset($GLOBALS['BE_MOD']['content']['article']);
  
$GLOBALS['TL_CTE']['plus']['contentslider'] = ContentContentSlider::class;
$GLOBALS['TL_CTE']['plus']['contentslider'] = ContentGrid::class;

               