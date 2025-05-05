<?php

namespace Bits\FlyUxBundle\EventListener\View;


class ContentLayoutModeNewsListener
{
     public function getSettings($arrSettings): array
    {

            $arrSettings['ptable'] = 'tl_news';
            $arrSettings['headline'] = 'News Details';
            $arrSettings['layoutClass'] = '';


                                     
            $arrSettings['htmlBlocks'] = [];
            $arrSettings['htmlBlocks']['container'] = [];
            $arrSettings['htmlBlocks']['container']['main'] = [];
            
            return $arrSettings;
    }
    
}