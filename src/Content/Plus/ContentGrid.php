<?php

namespace Bits\FlyUxBundle\Content\Plus;

use Contao\ContentElement;
use Contao\ContentModel;
use Contao\ModuleModel;
use Contao\Module;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Input;
use Contao\FrontendTemplate;
use Bits\FlyUxBundle\Content\Plus\ContentPlus;



class ContentGrid extends ContentElement
{
	
    protected $id;
    
    protected $ptable;
    
    protected $objElement;
    
    protected $container;
    
    protected $strTemplate = 'mod_content';
    
    protected $strChildTemplate = '@Contao/contentelement/ce_contentgrid.html.twig';
    
       
    
	public function generate()
	{
        $this->objElement = ContentModel::findById($this->__get('id'));
        $this->container = System::getContainer();
       
       $objContentPlus = new ContentPlus($this->objElement->id);

       $cssId = StringUtil::deserialize($this->objElement->cssId, true);
       $headline = StringUtil::deserialize($this->objElement->cssId, true);
       
       
       $elementPlus = $this->container->get('twig')->render(
			$this->strChildTemplate,
			array(
                'plusClass' => $this->objElement->el_css_class,
                'headline' => (isset($headline['value']))?$headline['value']:'',
                'headlineTag' => (isset($headline['unit']))?$headline['unit']:'',
                'cssId' => (isset($cssId[0]))?$cssId[0]:'',
                'cssClass' => (isset($cssId[1]))?$cssId[1]:'',
                'elementsByColumn' => $objContentPlus->getElements()
			)
		);

        
        return $elementPlus;
       
    }

	protected function compile()
	{
       
        $request = $this->container->get('request_stack')->getCurrentRequest();
		
        if($this->hasParentPlus()){
			$this->Template->elements = ($request && $this->container->get('contao.routing.scope_matcher')->isBackendRequest($request))?'Ein ContentPlus-Element darf nicht innerhalb eine ContentPlus-Elements sein.':'';  
        }else{
            $this->Template->elements = $this->generate();
        }
        
	}
    
    private function hasParentPlus():bool
    {
        
        return ($this->objElement->ptable === $this->ptable);
    }
    
     
}

