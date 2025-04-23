<?php
use Contao\CoreBundle\ContaoCoreBundle;

  $GLOBALS['BE_MOD']['content']['content'] = ['tables' => ['tl_content']];
   
   
return [
   
    ContaoCoreBundle::class => ['all' => true]
   
   
];