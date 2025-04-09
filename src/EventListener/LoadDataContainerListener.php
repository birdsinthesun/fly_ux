<?php

namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;

class LoadDataContainerListener
{
    /**
     * @Hook("loadDataContainer")
     */
    public function onLoadDataContainer(string $table): void
    {
        if ($table === 'tl_article') {
            unset($GLOBALS['TL_DCA']['tl_article']);
        }
    }
}