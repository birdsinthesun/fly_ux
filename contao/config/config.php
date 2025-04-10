<?php
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\CoreBundle\ContaoCalendarBundle;
use Contao\Input;
use Bits\FlyUxBundle\Widgets\ModuleWizard;

$GLOBALS['TL_PTY']['regular'] = 'Bits\FlyUxBundle\Pages\MyPageRegular';
$GLOBALS['BE_FFL']['flyWizard']  = ModuleWizard::class;

unset($GLOBALS['BE_MOD']['content']['article']);
unset($GLOBALS['FE_MOD']['navigationMenu']['articlenav']);
unset($GLOBALS['FE_MOD']['miscellaneous']['articlelist']);
unset($GLOBALS['TL_CTE']['includes']['article']);
unset($GLOBALS['TL_CTE']['includes']['alias']);
unset($GLOBALS['TL_MODELS']['tl_article']);

$GLOBALS['BE_MOD']['content']['content'] = [
    'tables' => ['tl_content'],
    'icon'   => 'vendor/birdsinthesun/fly_ux/assets/icon.svg' // optional
  //  'stylesheet' => 'bundles/deinmodul/backend.css', // optional
   // 'javascript' => 'bundles/deinmodul/backend.js',  // optional
];

return [
   
    ContaoCoreBundle::class => ['all' => true]
   
   
];