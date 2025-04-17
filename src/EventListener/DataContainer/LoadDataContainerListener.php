<?php
namespace Bits\FlyUxBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;

#[AsHook('loadDataContainer')]
class LoadDataContainerListener
{
    public function __invoke(string $table): void
    {
        if($table === 'tl_page'){
            
            $GLOBALS['TL_DCA']['tl_page']['config']['ctable'] =null;
            
        }
    }
}