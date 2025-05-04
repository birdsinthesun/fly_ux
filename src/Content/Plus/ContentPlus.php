<?php

namespace Bits\FlyUxBundle\Plus\Content;

use Contao\ContentModel;
use Contao\System;

class ContentPlus
{
    
    public $pid;
    
    public $ptable;
    
    protected $objParent;
    
    
     public function __construct($objParent)
    {
        
        $this->id = $objParent->id;
        $this->table = 'tl_content';
        $this->objParent = $objParent;
        
    }
    

    public function getElements(){


		$arrElements = [];
        
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
		if($request && $this->container->get('contao.routing.scope_matcher')->isBackendRequest($request)){
            $objElements = ContentModel::findByPidAndTable($this->id ,$this->table);

            }else{
             $objElements = ContentModel::findPublishedByPidAndTable($this->id ,$this->table);
   
        }
            
		
		 if ($objElements !== null) {
                while ($objElements->next()) {
                    $objElementModel = $objElements->current();

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
                            $arrElements[$objElementModel->inColumn][$objElementModel->id] = $objElement->generate();
                        }
    
                }
            }

    return $arrElements;
     
        
    }
    
   
    
}