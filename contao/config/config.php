<?php
use Contao\CoreBundle\ContaoCoreBundle;


unset($GLOBALS['TL_DCA']['tl_article']);

return [
   
    ContaoCoreBundle::class => ['all' => true]
   
   
];