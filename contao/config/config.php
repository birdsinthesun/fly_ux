<?php
use Contao\CoreBundle\ContaoCoreBundle;
use Bits\FlyUxBundle\Pages\MyPageRegular;


 $GLOBALS['TL_PTY']['regular'] = MyPageRegular::class;
  $GLOBALS['BE_MOD']['content']['content'] = ['tables' => ['tl_content']];
   
   
return [
   
    ContaoCoreBundle::class => ['all' => true]
   
   
];