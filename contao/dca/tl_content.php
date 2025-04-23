<?php


use Contao\Backend;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\System;
use Contao\DC_Table;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\StringUtil;
use Bits\FlyUxBundle\Driver\DC_Content;



$this->loadDataContainer('tl_page');


$GLOBALS['TL_DCA']['tl_content']['fields']['ptable'] = array
		(
			'sql'                     => "varchar(64) COLLATE ascii_bin NOT NULL default 'tl_page'"
		);
$GLOBALS['TL_DCA']['tl_content']['fields']['inColumn'] = array
		(
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_content_fly_ux', 'getActiveLayoutSections'),
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['COLS'],
			'sql'                     => "varchar(32) NOT NULL default 'main'"
		);
 

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @internal
 */
class tl_content_fly_ux extends Backend
{

 /**
	 * Return all active layout sections as array
	 *
	 * @param DataContainer $dc
	 *
	 * @return array
	 */
	public function getActiveLayoutSections(DataContainer $dc)
	{
		$arrSections = array();
        $session = System::getContainer()->get('request_stack')->getSession();
        $pid = $session->getBag('contao_backend')->get('OP_ADD_PID');

        // Show only active sections
		if ($pid ?? null)
		{
			
			$objPage = PageModel::findWithDetails($pid);
            
            
        }elseif(Input::get('pid') ?? null){
            $objPage = PageModel::findWithDetails(Input::get('pid'));
            
        
        }elseif($dc->getActiveRecord->pid ?? null){
            $objPage = PageModel::findWithDetails($dc->getActiveRecord->pid);
            
        }
			// Get the layout sections
			if ($objPage->layout)
			{
				$objLayout = LayoutModel::findById($objPage->layout);

				if ($objLayout === null)
				{
					return array();
				}

				$arrModules = StringUtil::deserialize($objLayout->modules);

				if (empty($arrModules) || !is_array($arrModules))
				{
					return array();
				}

				// Find all sections with an article module (see #6094)
				foreach ($arrModules as $arrModule)
				{
					if ($arrModule['mod'] == 0 && ($arrModule['enable'] ?? null))
					{
						$arrSections[] = $arrModule['col'];
					}
				}
			}
		

		

		return Backend::convertLayoutSectionIdsToAssociativeArray($arrSections);
	}
}