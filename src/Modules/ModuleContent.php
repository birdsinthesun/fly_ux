<?php

namespace Bits\FlyUxBundle\Modules;



class ModuleContent extends Module
{
	
	protected $strTemplate = 'mod_content';

	public function generate($blnNoMarkup=false)
	{
		if ($this->isHidden())
		{
			return '';
		}

		$this->type = 'content';

		return parent::generate();
	}

	
	protected function compile()
	{
		global $objPage;

		$id = 'page-content-' . $objPage->id;

		$arrElements = array();
		$objCte = ContentModel::findPublishedByPidAndTable($objPage->id, 'tl_content');
var_dump($objCte,'test');
		if ($objCte !== null)
		{
			while ($objCte->next())
			{
				$arrElements[] = $this->getContentElement($objCte->current(), $objCte->current()->strColumn);
			}
		}

		
		$this->Template->elements = $arrElements;
		
	}

}
