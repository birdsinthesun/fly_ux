 <?php


use Contao\Backend;
use Contao\Database;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\StringUtil;

$this->loadDataContainer('tl_page');
$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_page';
$GLOBALS['TL_DCA']['tl_content']['config']['ctable'] = ['tl_content'];
$GLOBALS['TL_DCA']['tl_content']['config']['dynamicPtable'] = true;
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][]['tl_page'] = 'addBreadcrumb';
$GLOBALS['TL_DCA']['tl_content']['config']['switchToEdit']                = true;
$GLOBALS['TL_DCA']['tl_content']['config']['enableVersioning']            = true;
$GLOBALS['TL_DCA']['tl_content']['config']['markAsCopy']                  = 'headline';

'ptable'                      => 'tl_page',
		'ctable'                      => array('tl_content'),
		'dynamicPtable'               => true,
        'switchToEdit'                => true,
		'enableVersioning'            => true,
		'markAsCopy'                  => 'title',
		'onload_callback'             => array
		(
			array('tl_content', 'adjustDcaByType'),
			array('tl_content', 'showJsLibraryHint'),
            array('tl_page', 'addBreadcrumb')
		),

$GLOBALS['TL_DCA']['tl_content']['list']['sorting']['mode'] = DataContainer::MODE_TREE_EXTENDED;
$GLOBALS['TL_DCA']['tl_content']['list']['label']['fields'] =  ['headline', 'type', 'inColumn'];
$GLOBALS['TL_DCA']['tl_content']['list']['label']['format'] =   '%s <span class="label-info">[%s]</span><span class="label-column"> %s </span>';
foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $paletteKey => $paletteValue) {
   
    if (is_string($paletteValue)) {
      
        if (strpos($paletteValue, '{layout_legend},inColumn;') !== 0) {
            $GLOBALS['TL_DCA']['tl_content']['palettes'][$paletteKey] = '{layout_legend},inColumn;' . $paletteValue;
        }
    }
}

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
		// Show only active sections
		if ($dc->activeRecord->pid ?? null)
		{
			$arrSections = array();
			$objPage = PageModel::findWithDetails($dc->activeRecord->pid);

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
		}

		// Show all sections (e.g. "override all" mode)
		else
		{
			$arrSections = array('header', 'left', 'right', 'main', 'footer');
			$objLayout = Database::getInstance()->query("SELECT sections FROM tl_layout WHERE sections!=''");

			while ($objLayout->next())
			{
				$arrCustom = StringUtil::deserialize($objLayout->sections);

				// Add the custom layout sections
				if (!empty($arrCustom) && is_array($arrCustom))
				{
					foreach ($arrCustom as $v)
					{
						if (!empty($v['id']))
						{
							$arrSections[] = $v['id'];
						}
					}
				}
			}
		}

		return Backend::convertLayoutSectionIdsToAssociativeArray($arrSections);
	}
}