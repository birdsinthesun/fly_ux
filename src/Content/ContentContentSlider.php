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
class ContentContentSlider extends ContentElement
{
	

/**
	 * Parse the template
	 *
	 * @return string
	 */
	public function generate()
	{
        
        if ($this->isHidden())
		{
			return '';
		}
        
    }
	/**
	 * Generate the content element
	 */
	protected function compile()
	{
       

        // Optional: auf Modul-Templates zugreifen
        parent::compile();
	}
    
     
}
