<?php

namespace Bits\FlyUxBundle\EventListener\View;


use Contao\PageModel;
use Contao\LayoutModel;
use Contao\Input;

class ContentLayoutModeThemesListener
{
    public function getSettings($arrSettings): array
    {
       
        
            $arrSettings['ptable'] = 'tl_theme';
            $ThemeId = Input::get('id');
             //find Layouts
             $layoutId = Input::get('id');
             $layoutModel = new LayoutModel;
             $objLayout = $layoutModel::findByPId($layoutId);
            
             $arrSettings['headline'] = 'Layout Inhaltselemente';
            $arrSettings['layoutClass'] = 'layout-content';
             // find layout sections within sections and modules
                while($objLayout->next()){
             // make an assoc array about the posibilities to include a section
             $attrBlock = ['position'=>'default'];
                                 
            $arrSettings['htmlBlocks'] = [];
            $arrSettings['htmlBlocks']['container'] = $attrBlock;
                                 
                                 
            foreach(unserialize($objLayout->modules) as $module){
                   
                                                          
                     foreach(unserialize($objLayout->sections) as $section){
                                             
                            if($section['position'] === 'top'
                                &&$module['col'] === $section['id']){
                                        $arrSettings['htmlBlocks'][$section['id']] = ['position'=>'top'];
                                }
                            elseif($section['position'] === 'before'
                                &&$module['col'] === $section['id']){
                                                     
                                        $arrSettings['htmlBlocks']['container'][$section['id']] = ['position'=>'before'];  
                                        $arrSettings['htmlBlocks']['container'][$module['col']] = $attrBlock;
                                                
                            }elseif($section['position'] === $module['col']
                                    &&$module['col'] === $section['id']){
                                                     
                                         $arrSettings['htmlBlocks']['container'][$module['col']][$section['id']] = ['position'=>'main'];    
                            }elseif($section['position'] === 'after'
                                    &&$module['col'] === $section['id']){
                                                
                                        $arrSettings['htmlBlocks']['container'][$module['col']] = $attrBlock;
                                        $arrSettings['htmlBlocks']['container'][$section['id']] = ['position'=>'after'];    
                            }elseif($section['position'] === 'bottom'
                                    &&$module['col'] === $section['id']){
                                                     
                                         $arrSettings['htmlBlocks'][$section['id']] = ['position'=>'bottom'];    
                            }else{
                                         $arrSettings['htmlBlocks']['container'][$module['col']] = $attrBlock;
                                                
                                }
                                             
                        }
                }
        
                }

        return $arrSettings;
    }
}
