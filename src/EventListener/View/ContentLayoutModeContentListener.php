<?php

namespace Bits\FlyUxBundle\EventListener\View;


use Contao\PageModel;
use Contao\LayoutModel;
use Contao\Input;

class ContentLayoutModeContentListener
{
    public function getSettings($arrSettings): array
    {
       
        
            $arrSettings['ptable'] = 'tl_page';
             //find Layout of the page 
             $pageModel = new PageModel;
             $objPage = $pageModel::findById(Input::get('id'));
             $layoutId = $objPage->loadDetails()->layout;
             $layoutModel = new LayoutModel;
             $objLayout = $layoutModel::findById($layoutId);
             
             $arrSettings['headline'] = $objPage->__get('title');
            $arrSettings['layoutClass'] = $objLayout->__get('cssClass');
             // find layout sections within sections and modules
            
             // make an assoc array about the posibilities to include a section
             $attrBlock = ['position'=>'default'];
                                 
            $arrSettings['htmlBlocks'] = [];
            $arrSettings['htmlBlocks']['container'] = $attrBlock;
                                 
                                 
            foreach(unserialize($objLayout->modules) as $module){
                    if($module['mod'] !== '0'){
                                            continue;
                    }
                                                          
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
        
            

        return $arrSettings;
    }
}
