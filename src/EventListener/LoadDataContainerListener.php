<?php


namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;

#[AsHook('loadDataContainer')]
class LoadDataContainerListener
{
    public function __invoke(string $table): void
    {
       if($table === 'tl_content'){
           $GLOBALS['TL_DCA'][$table]['config']['notCreatable'] = true;
          // var_dump($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']);exit;
           unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['toggleNodes']);
           unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['all']);
            unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['showOnSelect']);
             unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['create']);
           }
    }
}