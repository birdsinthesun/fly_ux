<?php

namespace Bits\FlyUxBundle\EventListener\View;


class ContentLayoutModeThemesListener
{
     public function getSettings($arrSettings): array
    {

            $arrSettings['ptable'] = 'tl_theme';
            $arrSettings['headline'] = 'Theme Inhaltselemente';
            $arrSettings['layoutClass'] = '';


                                     
            $arrSettings['htmlBlocks'] = [];
            $arrSettings['htmlBlocks']['container'] = [];
            $arrSettings['htmlBlocks']['container']['main'] = [];
            
            return $arrSettings;
    }
    
}