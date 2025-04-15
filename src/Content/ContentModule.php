<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Bits\FlyUxBundle\Content;

use Contao\ContentElement;
use Contao\ContentModel;
use Contao\ModuleModel;
use Contao\Module;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Input;


/**
 * Front end content element "module".
 */
class ContentModule extends ContentElement
{
	

/**
	 * Parse the template
	 *
	 * @return string
	 */
	public function generate()
	{
		
       
       // $path = $kernel->getCacheDir().'/contao/config/config.php';
      //$config = $path ;
         //     $framework = System::getContainer()->get('contao.framework');
   // $framework->initialize();
          //  return '⚙️ Modulansicht im Backend (custom)';
        
        if ($this->isHidden())
		{
			return '';
		}
        
        
        if(Input::get('do')){
            $id = $this->module;
            $objModule = ModuleModel::findById( $id);
            }else{
              $id = $this->id; 
              $objModule = ModuleModel::findById( $id);
                $cssID = StringUtil::deserialize($this->cssID, true);
                $objModule->cssID = $cssID;
                }
		

		// Clone the model, so we do not modify the shared model in the registry
		//$objModel = ContentModel::findById($this->id);
       // var_dump($this->id,$this->__get('module'));exit;
		
        
        


		

		// Tag the content element (see #2137)
		if ($this->objModel !== null)
		{
			System::getContainer()->get('contao.cache.tag_manager')->tagWithModelInstance($this->objModel);
		}
        
         //var_dump($objModule->type);
        if ($this->checkIfBackend===false) {
                return $this->getContentModule($objModule);
        }else{
            return $this->getContentModule($objModule);
            
            }
	}

	/**
	 * Generate the content element
	 */
	protected function compile()
	{
       

        // Optional: auf Modul-Templates zugreifen
        //parent::compile();
	}
    
     public function checkIfBackend(): bool
    {
        $this->contaoFramework->initialize();

        $request = $this->requestStack->getCurrentRequest();
        
        if (Input::get('do')) {
            return true;  // Im Backend
        }

        return false;  // Im Frontend
    }
    
    /**
	 * Generate a front end module and return it as string
	 *
	 * @param mixed  $intId     A module ID or a Model object
	 * @param string $strColumn The name of the column
	 *
	 * @return string The module HTML markup
	 */
	public function getContentModule($objModule)
	{
		

		global $objPage;
        //$objPage = PageModel::findById($objElement->pid);

		

		$objRow = $objModule;

        
        
        if(class_exists($this->findFrontendModuleKey($objModule->type))){
                $strClass = $this->findFrontendModuleKey($objModule->type);
            }elseif(class_exists('Contao\\Module' . ucfirst($objModule->type))){
                 $strClass = 'Contao\\Module' . ucfirst($objModule->type);
                
            }else{
                
			System::getContainer()->get('monolog.logger.contao.error')->error('Module type "' . $objModule->type . '" (module "' . $objRow->type . '") does not exist');

			return 'Modul: '.$objModule->type;
		}
	 
      
		$strStopWatchId = 'contao.frontend_module.' . $objRow->type . ' (ID ' . $objRow->id . ')';

		if (System::getContainer()->getParameter('kernel.debug') && System::getContainer()->has('debug.stopwatch'))
		{
			$objStopwatch = System::getContainer()->get('debug.stopwatch');
			$objStopwatch->start($strStopWatchId, 'contao.layout');
		}

		$objRow->typePrefix = 'mod_';

		$objContentModule = new $strClass($objRow);
		$strBuffer = $objContentModule->generate();

		return $strBuffer;

		// Disable indexing if protected
		if ($objModule->protected && !preg_match('/^\s*<!-- indexer::stop/', $strBuffer))
		{
			$strBuffer = "\n<!-- indexer::stop -->" . $strBuffer . "<!-- indexer::continue -->\n";
		}

		if (isset($objStopwatch) && $objStopwatch->isStarted($strStopWatchId))
		{
			$objStopwatch->stop($strStopWatchId);
		}

		return $strBuffer;
	}
    
   public function findFrontendModuleKey(string $targetKey):string
    {
        foreach ($GLOBALS['FE_MOD'] as $scope => $modules) {
            if (array_key_exists($targetKey, $modules)) {
                return $modules[$targetKey];
            }
        }

        return '';
    }

}
