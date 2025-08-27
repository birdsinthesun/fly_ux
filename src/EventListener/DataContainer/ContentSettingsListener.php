<?php

namespace Bits\FlyUxBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Bits\FlyUxBundle\Driver\DC_Content;
use Contao\DataContainer;
use Contao\Input;

#[AsHook('loadDataContainer','__invoke',-18)]
class ContentSettingsListener
{
    public function __invoke(string $table): void
    {
         
       
            //changes to tl_content only
            if($table === 'tl_content'){
                    if(Input::get('ptable') !== null){
                        $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = Input::get('ptable');
                    }
                   
                    $GLOBALS['TL_DCA']['tl_content']['config']['dataContainer'] = DC_Content::class;
                    $GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['tl_page','addBreadcrumb'];
                    
                    $GLOBALS['TL_DCA']['tl_content']['config']['ctable'] = ['tl_content'];
                    $GLOBALS['TL_DCA']['tl_content']['config']['switchToEdit']  = true;

                    $GLOBALS['TL_DCA']['tl_content']['config']['dynamicPtable'] = true;
                    $GLOBALS['TL_DCA']['tl_content']['config']['enableVersioning']            = true;
                    $GLOBALS['TL_DCA']['tl_content']['config']['markAsCopy']                  = 'headline';
                    
                    $GLOBALS['TL_DCA']['tl_content']['list']['sorting']['mode'] = DataContainer::MODE_PARENT;
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['fields']);
                    $GLOBALS['TL_DCA']['tl_content']['list']['sorting']['panelLayout'] = 'search';
                    $GLOBALS['TL_DCA']['tl_content']['list']['sorting']['defaultSearchField'] = 'headline';
                    $GLOBALS['TL_DCA']['tl_content']['list']['label']['fields'] =  ['headline', 'type', 'inColumn'];
                    $GLOBALS['TL_DCA']['tl_content']['list']['label']['format'] =   '%s <span class="label-info">[%s]</span><span class="label-column"> %s </span>';
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['child_record_callback']);
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['renderAsGrid']);
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['limitHeight']);
                  
                    
                    
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
                
                
                        if(!isset($GLOBALS['TL_DCA']['tl_content']['config']['notCreatable'])){
                            $GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['add_content_element'] = [
               
                             ];
                            
                        }
                        

                        $GLOBALS['TL_DCA']['tl_content']['list']['global_oper0ations']['drag_drop_mode'] = [
                               ];

                        $GLOBALS['TL_DCA']['tl_content']['list']['global_oper0ations']['drag_drop_disable'] = [
                                  ];
                    
                

                 
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['header_new']);
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['new']);
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['toggleNodes']);
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['all']);
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['showOnSelect']);
                    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['create']);
                    unset($GLOBALS['BE_MOD']['content']['article']);
                    unset($GLOBALS['FE_MOD']['navigationMenu']['articlenav']);
                    unset($GLOBALS['FE_MOD']['miscellaneous']['articlelist']);
                    unset($GLOBALS['TL_CTE']['includes']['article']);
                    //unset($GLOBALS['TL_CTE']['includes']['content']);
                    unset($GLOBALS['TL_CTE']['includes']['teaser']);
                    unset($GLOBALS['TL_CTE']['includes']['alias']);
                    unset($GLOBALS['TL_CTE']['legacy']['accordionSingle']);
                    unset($GLOBALS['TL_CTE']['miscellaneous']['swiper']);
                     unset($GLOBALS['TL_CTE']['miscellaneous']['element_group']);
                  
                   
            }
                  
    
    
    }
    
}