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



class ContentContentSlider extends ContentElement
{
	
    protected $id;
    
    protected $ptable;
    
    protected $objElement;
    
    protected $container;
    
    protected $strTemplate = 'mod_content';
    
    protected $strChildTemplate = '@Contao/contentelement/ce_contentslider.html.twig';
    
       
    
	public function generate()
	{
        $this->objElement = ContentModel::findById($this->__get('id'));
        $this->container = System::getContainer();
       
       $objContentPlus = new ContentPlus($this->objElement->id);
       
       $elementPlusStart = $this->container->get('twig')->render(
			$strChildTemplate,
			array(
                'headline' => $this->objElement->headline,
                'headlineTag' => $this->objElement->h1,
                'cssId' => StringUtil::deserialize($this->objElement->cssId, true)[0],
                'cssClass' => StringUtil::deserialize($this->objElement->cssId, true)[1],
                'elementsByColumn' => $objContentPlus->getElements(),
                'settings' => [
                        'delay' => $this->objElement->sliderDelay,
                        'speed' => $this->objElement->sliderSpeed,
                        'startSlide' => $this->objElement->sliderSpeed,
                        'continuous' => $this->objElement->sliderContinuous
                        ],
                'previous' => $GLOBALS['TL_LANG']['MSC']['previous'],
                'next' => $GLOBALS['TL_LANG']['MSC']['next']
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

