<?php


namespace Bits\FlyUxBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
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
use Contao\CoreBundle\DataContainer\DataContainerOperation;

#[AsHook('loadDataContainer','__invoke',210)]
class LoadDataContainerListener
{
    public function __invoke(string $table): void
    {

        
         // settings for fly_ux driver
        if(isset($GLOBALS['BE_MOD']['content'][Input::get('do')]['config']['driver'])
            &&$GLOBALS['BE_MOD']['content'][Input::get('do')]['config']['driver'] === 'fly_ux'
            &&!isset($GLOBALS['BE_MOD']['content'][Input::get('do')]['init'])
            ){
               
               // var_dump($GLOBALS['TL_DCA']['tl_page']);exit;
                $GLOBALS['BE_MOD']['content'][Input::get('do')]['init'] = true;
                //$root = $GLOBALS['BE_MOD']['content'][Input::get('do')]['relations'][0];
                
                foreach($GLOBALS['BE_MOD']['content'][Input::get('do')]['config']['relations'] as $key => $ptable){
                   // $GLOBALS['TL_DCA'][$ptable]['config']['dataContainer'] = DC_Content::class;
                    $GLOBALS['TL_DCA']['tl_content']['config']['switchToEdit'] = true;
                    
                    if(isset($GLOBALS['BE_MOD']['content'][Input::get('do')]['config']['relations'][$key+1])){
                        $GLOBALS['TL_DCA'][$ptable]['config']['ctable'] = [$GLOBALS['BE_MOD']['content'][Input::get('do')]['config']['relations'][$key+1]];
                       //set the show-button
                       if($GLOBALS['BE_MOD']['content'][Input::get('do')]['config']['relations'][$key+1]==='tl_content'){
                            $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['prefetch'] = true;
                             $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['primary'] = true;
                            $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['icon'] = 'system/themes/flexible/icons/children.svg';
                            $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['label'] = ['Inhalten', 'Inhalt bearbeiten'];
                            $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['button_callback'] = [self::class, 'contentShowButton'];
                            $GLOBALS['BE_MOD']['content'][Input::get('do')]['showBtn'] = $table;
                                // var_dump( $ptable, $GLOBALS['TL_DCA'][$ptable]['config']['ctable']);exit; 
                       
                        }
                  
                   
                        if($key !== 0){
                            $GLOBALS['TL_DCA'][$ptable]['list']['sorting']['mode'] = DataContainer::MODE_TREE_EXTENDED;
                           $GLOBALS['TL_DCA'][$ptable]['config']['ptable'] = (isset($root))?:'';
                           $GLOBALS['TL_DCA'][$ptable]['config']['dynamicPtable'] = true;
                         }
                    
                    $root = $GLOBALS['BE_MOD']['content'][Input::get('do')]['config']['relations'][$key];
                    }
                }
                //changes to tl_content only
                if($table === 'tl_content'){
                    
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
                                'label'      => ['Neues Element einfügen', ''],
                                'href'       => 'op_add=add_content_element&act=create',
                                'class'      => 'header_new_element',
                                'icon'       => '',
                                'button_callback' => ['\Bits\FlyUxBundle\Driver\DC_ContentOperations', 'addElementButton'],
                            ];
                            
                        }
                        

                        $GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['drag_drop_mode'] = [
                            'label'      => ['Drag & Drop Modus', ''],
                            'href'       => 'op_dd=drag_drop_mode',
                            'class'      => 'header_drag_drop',
                            'icon'       => '',
                            'button_callback' => ['\Bits\FlyUxBundle\Driver\DC_ContentOperations', 'dragDropButton'],
                        ];

                        $GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['drag_drop_disable'] = [
                            'label'      => ['Drag & Drop Deaktivieren', ''],
                            'href'       => '',
                            'class'      => 'header_drag_drop_disable',
                            'icon'       => '',
                            'button_callback' => ['\Bits\FlyUxBundle\Driver\DC_ContentOperations', 'dragDropDeaktivateButton'],
                        ];
                    
                    
                }
                
                           
            }   

        
              //  $GLOBALS['TL_DCA'][$table]['config']['notCreatable'] = true;
    }
    
     public static function contentShowButton(array $row, $href, string $label, string $title, $icon, string $attributes): string
    {
        $container = System::getContainer();
        $tokenManager = $container->get('contao.csrf.token_manager');
        $token = $tokenManager->getToken('contao.csrf.token')->getValue();
        $table = $GLOBALS['BE_MOD']['content'][Input::get('do')]['showBtn'];
        $do = (Input::get('do')==='page')?'content':Input::get('do');
        
        return '<a href="contao?do='.$do.'&mode=layout&table=tl_content&id=' . $row['id']. '&amp;rt='.$token . '" title="Inhalte ID ' . $row['id']. ' bearbeiten" ' . $attributes . '>
            <img src="system/themes/flexible/icons/children.svg" alt="Inhalte zeigen und bearbeiten">
        </a>';
    }
    

   
}