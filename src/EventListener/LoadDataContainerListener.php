<?php


namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Bits\FlyUxBundle\Widgets\ModuleWizard;
use Bits\FlyUxBundle\Content\ContentModule;
use Bits\FlyUxBundle\Driver\DC_Content;
use Contao\Backend;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\System;
use Contao\DC_Table;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\StringUtil;

#[AsHook('loadDataContainer')]
class LoadDataContainerListener
{
    public function __invoke(string $table): void
    {
       
       
        $GLOBALS['BE_FFL']['flyWizard']  = ModuleWizard::class;
        $GLOBALS['TL_CTE']['includes']['module'] = ContentModule::class;

        unset($GLOBALS['FE_MOD']['navigationMenu']['articlenav']);
        unset($GLOBALS['FE_MOD']['miscellaneous']['articlelist']);
        unset($GLOBALS['TL_CTE']['includes']['article']);
        unset($GLOBALS['TL_CTE']['includes']['content']);
        unset($GLOBALS['TL_CTE']['includes']['teaser']);
        unset($GLOBALS['TL_CTE']['includes']['alias']);
        unset($GLOBALS['TL_CTE']['legacy']['accordionSingle']);
        unset($GLOBALS['TL_CTE']['miscellaneous']['swiper']);
        //unset($GLOBALS['TL_MODELS']['tl_article']);
      
       
           
        if($table === 'tl_content' && Input::get('do') === 'content'){
            
                $GLOBALS['TL_DCA']['tl_content']['config']['dataContainer']  = DC_Content::class;
                $GLOBALS['TL_DCA']['tl_content']['config']['ctable'] = [];
                $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_page';
                
               
                $GLOBALS['TL_DCA']['tl_content']['config']['dynamicPtable'] = true;
                $GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['tl_page','addBreadcrumb'];
                $GLOBALS['TL_DCA']['tl_content']['config']['switchToEdit']                = true;
                $GLOBALS['TL_DCA']['tl_content']['config']['enableVersioning']            = true;
                $GLOBALS['TL_DCA']['tl_content']['config']['markAsCopy']                  = 'headline';

                $GLOBALS['TL_DCA']['tl_content']['list']['sorting']['mode'] = DataContainer::MODE_TREE_EXTENDED;

                $GLOBALS['TL_DCA']['tl_content']['list']['label']['fields'] =  ['headline', 'type', 'inColumn'];
                $GLOBALS['TL_DCA']['tl_content']['list']['label']['format'] =   '%s <span class="label-info">[%s]</span><span class="label-column"> %s </span>';


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
                           
                
                $GLOBALS['TL_DCA'][$table]['config']['notCreatable'] = true;
                
                unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['toggleNodes']);
                unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['all']);
                unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['showOnSelect']);
                unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['create']);
           
           
           }
           if($table === 'tl_page'){
                $GLOBALS['TL_DCA']['tl_page']['config']['ctable'] = ['tl_content'];
                // unset($GLOBALS['TL_DCA']['tl_page']['list']['operations']['articles']);
                 //$GLOBALS['TL_DCA']['tl_page']['list']['operations']['show']['label'] = ['Inhalten', 'Inhalt bearbeiten'];
                 $GLOBALS['TL_DCA']['tl_page']['list']['operations']['children']['button_callback'] = [self::class, 'contentShowButton'];
               
           }
    }
    
     public static function contentShowButton(array $row, string $href, string $label, string $title, string $icon, string $attributes): string
    {
        $container = System::getContainer();
        $tokenManager = $container->get('contao.csrf.token_manager');
        $token = $tokenManager->getToken('contao.csrf.token')->getValue();
        
        
        return '<a href="contao?do=content&pid=' . $row['id']. '&amp;rt='.$token . '" title="Inhalte ID ' . $row['id']. ' bearbeiten" ' . $attributes . '>
            <img src="system/themes/flexible/icons/children.svg" alt="Inhalte zeigen und bearbeiten">
        </a>';
    }
    
    
    
}