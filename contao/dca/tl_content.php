 <?php



use Contao\DataContainer;

$this->loadDataContainer('tl_page');
$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_page';
$GLOBALS['TL_DCA']['tl_content']['config']['ctable'] = ['tl_content'];
$GLOBALS['TL_DCA']['tl_content']['config']['dynamicPtable'] = true;
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][]['tl_page'] = 'addBreadcrumb';
$GLOBALS['TL_DCA']['tl_content']['list']['sorting']['mode'] = DataContainer::MODE_TREE_EXTENDED;
$GLOBALS['TL_DCA']['tl_content']['list']['label']['fields'] =  ['headline', 'type', 'inColumn'];
$GLOBALS['TL_DCA']['tl_content']['list']['label']['format'] =   '%s <span class="label-info">[%s]</span><span class="label-column"> %s </span>';
