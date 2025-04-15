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
$GLOBALS['TL_DCA']['tl_content']['config']['dataContainer']  = DC_Content::class;
$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_page';
$GLOBALS['TL_DCA']['tl_content']['config']['ctable'] = ['tl_content'];
$GLOBALS['TL_DCA']['tl_content']['config']['dynamicPtable'] = true;
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['tl_page','addBreadcrumb'];
$GLOBALS['TL_DCA']['tl_content']['config']['switchToEdit']                = true;
$GLOBALS['TL_DCA']['tl_content']['config']['enableVersioning']            = true;
$GLOBALS['TL_DCA']['tl_content']['config']['markAsCopy']                  = 'headline';

$GLOBALS['TL_DCA']['tl_content']['list']['sorting']['mode'] = DataContainer::MODE_TREE_EXTENDED;

$GLOBALS['TL_DCA']['tl_content']['list']['label']['fields'] =  ['headline', 'type', 'inColumn'];
$GLOBALS['TL_DCA']['tl_content']['list']['label']['format'] =   '%s <span class="label-info">[%s]</span><span class="label-column"> %s </span>';
//$GLOBALS['TL_DCA']['tl_content']['list']['label']['label_callback'] =   array('tl_content', 'addIcon');
unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['fields']);
unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['panelLayout']);
unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['defaultSearchField']);
unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['headerFields']);
unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['child_record_callback']);
           
			

foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $paletteKey => $paletteValue) {
   if ($paletteKey === '__selector__') {
    continue;
}
    if (is_string($paletteValue)) {
      
        if (strpos($paletteValue, '{layout_legend},inColumn;') !== 0) {
            $GLOBALS['TL_DCA']['tl_content']['palettes'][$paletteKey] = '{layout_legend},inColumn;' . $paletteValue;
        }
    }
}
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
        
$GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['add_content_element'] = [
    'label'      => ['Neues Element einfügen', ''],
    'href'       => 'op_add=add_content_element&act=create',
    'class'      => 'header_new_element',
    'button_callback' => ['\Bits\FlyUxBundle\Driver\DC_ContentOperations', 'addElementButton'],
];

$GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['drag_drop_mode'] = [
    'label'      => ['Drag & Drop Modus', ''],
    'href'       => 'op_dd=drag_drop_mode',
    'class'      => 'header_drag_drop',
    'button_callback' => ['\Bits\FlyUxBundle\Driver\DC_ContentOperations', 'dragDropButton'],
];

$GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['drag_drop_disable'] = [
    'label'      => ['Drag & Drop Deaktivieren', ''],
    'href'       => '',
    'class'      => 'header_drag_drop_disable',
    'button_callback' => ['\Bits\FlyUxBundle\Driver\DC_ContentOperations', 'dragDropDeaktivateButton'],
];

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