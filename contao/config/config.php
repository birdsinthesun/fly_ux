<?php
use Contao\CoreBundle\ContaoCoreBundle;
use Bits\FlyUxBundle\Widgets\ModuleWizard;

$GLOBALS['TL_PTY']['regular'] = 'Bits\FlyUxBundle\Pages\MyPageRegular';
$GLOBALS['BE_FFL']['flyWizard']  = ModuleWizard::class;

unset($GLOBALS['BE_MOD']['content']['article']);
unset($GLOBALS['FE_MOD']['navigationMenu']['articlenavigation']);

$GLOBALS['BE_MOD']['content']['content'] = [
    'tables' => ['tl_content'],
    'icon'   => 'vendor/birdsinthesun/fly_ux/assets/icon.svg', // optional
  //  'stylesheet' => 'bundles/deinmodul/backend.css', // optional
   // 'javascript' => 'bundles/deinmodul/backend.js',  // optional
];



return [
   
    ContaoCoreBundle::class => ['all' => true]
   
   
];