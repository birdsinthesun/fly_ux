<?php

namespace Bits\FlyUxBundle\Content\Plus;

use Contao\ContentModel;
use Contao\System;

class ContentPlus
{
    
    public $pid;
    
    public $ptable;
    
    
    
     public function __construct($objParentId)
    {
        
        $this->pid = $objParentId;
        $this->table = 'tl_content';
        
    }
    

    public function getElements(){


		$arrElements = [];
        
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
		if($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)){
            $objElements = ContentModel::findBy('pid',$this->pid ,['ptable'=>$this->table]);

            }else{
             $objElements = ContentModel::findPublishedByPidAndTable($this->pid ,$this->table);
   
        }
            
		
		 if ($objElements !== null) {
                while ($objElements->next()) {
                    $objElementModel = $objElements->current();
                        $objElementModel->type = ($objElementModel->type)?$objElementModel->type:'text';
                        if ($objElementModel->type !== 'module'&&$objElementModel->type !== 'form') {
                            $strClass = 'Contao\\Content' . ucfirst($objElementModel->type);
                        }elseif($objElementModel->type === 'module'){
                             $strClass = 'Bits\\FlyUxBundle\\Content\\Content' . ucfirst($objElementModel->type);
                            $objModule = ModuleModel::findById($objElementModel->module);

                                $cssID = StringUtil::deserialize($objElementModel->cssID, true);
                                $objModule->cssID = $cssID;
                        
                            $objElementModel = $objModule;
                        }elseif($objElementModel->type === 'form'){
                             $strClass = 'Contao\\Form' ;
                        }

                        if (class_exists($strClass)) {
                            /** @var \Contao\ContentElement $objElement */
                            $objElement = new $strClass($objElementModel);
                         }else{
                             
                             $strClass = $this->findContentElementClass($objElementModel->type);
                            $objElement = new $strClass($objElementModel);
                        }
                        
                        $arrElements[$objElementModel->inColumn][$objElementModel->id] = $objElement->generate();
                     
    
                }
            }

    return $arrElements;
     
        
    }
    
      public function findContentElementClass(string $targetType):string
    {
        
        $tlCte = $GLOBALS['TL_CTE'];
        foreach ($tlCte as $group => $classes) {
           foreach ($classes as $type => $class) {
            if($type === $targetType){
                return  $class;
                }
            }
        }
        
    }
    
   
    
}