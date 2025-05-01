<?php

namespace Bits\FlyUxBundle\Content;

use Contao\ContentElement;
use Contao\ContentModel;
use Contao\ModuleModel;
use Contao\Module;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Input;


class ContentContentSlider extends ContentElement
{
	

	public function generate()
	{
        
        if ($this->isHidden())
		{
			return '';
		}
        
    }

	protected function compile()
	{
       
        parent::compile();
	}
    
     
}

