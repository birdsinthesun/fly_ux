<?php
use Contao\CoreBundle\ContaoCoreBundle;

unset($GLOBALS['BE_MOD']['content']['article']);
unset($GLOBALS['FE_MOD']['navigationMenu']['articlenavigation']);
$GLOBALS['BE_MOD']['content']['content'] = [
    'tables' => ['tl_page', 'tl_content'],
    'icon'   => 'vendor/birdsinthesun/fly_ux/assets/icon.svg', // optional
  //  'stylesheet' => 'bundles/deinmodul/backend.css', // optional
   // 'javascript' => 'bundles/deinmodul/backend.js',  // optional
];


return [
   
    ContaoCoreBundle::class => ['all' => true]
   
   
];