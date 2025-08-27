<?php


namespace Bits\FlyUxBundle\EventListener\DataContainer;

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

#[AsHook('loadDataContainer','__invoke',260)] 
class ContentDriverConfigLoaderListener
{
    public function __invoke(string $table): void
    {
        
       // if(Input::get('do') !== 'themes'){
            if($table === 'tl_content'){
                        if(Input::get('ptable') !== null){
                            $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = Input::get('ptable');
                        }
             }
             if($table === 'tl_page'){
              //  unset($GLOBALS['TL_DCA']['tl_page']['list']['operations']);
             }
              // settings for fly_ux driver
            if(isset($GLOBALS['BE_FLY_UX'][Input::get('do')]['config']['driver'])
                &&$GLOBALS['BE_FLY_UX'][Input::get('do')]['config']['driver'] === 'fly_ux'
                &&!isset($GLOBALS['BE_FLY_UX'][Input::get('do')]['init'])
                ){
                   
                 
                    $GLOBALS['BE_FLY_UX'][Input::get('do')]['init'] = true;
                     $count = count($GLOBALS['BE_FLY_UX'][Input::get('do')]['config']['relations']);
                    foreach($GLOBALS['BE_FLY_UX'][Input::get('do')]['config']['relations'] as $key => $ptable){
                     
                        if(isset($GLOBALS['BE_FLY_UX'][Input::get('do')]['config']['relations'][$key+1])){
                            $GLOBALS['TL_DCA'][$ptable]['config']['ctable'] = [$GLOBALS['BE_FLY_UX'][Input::get('do')]['config']['relations'][$key+1]];
                           //set the show-button
                           if($GLOBALS['BE_FLY_UX'][Input::get('do')]['config']['relations'][$key+1]==='tl_content'){
                               if(Input::get('do')!=='page')
                                { 
                                   $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['prefetch'] = true;
                               
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['primary'] = true;
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['attributes'] = 'data-contao--deeplink-target="primary"';
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['icon'] = 'system/themes/flexible/icons/children.svg';
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['label'] = ['Inhalte', 'Inhalt bearbeiten'];
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['children']['button_callback'] = ['Bits\FlyUxBundle\Operation\Children', 'contentShowButton'];
                                }else{
                                    
                                    
                                   
                                    
                                   
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['articles']['prefetch'] = true;
                               
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['articles']['primary'] = true;
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['articles']['attributes'] = 'data-contao--deeplink-target="primary"';
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['articles']['icon'] = 'system/themes/flexible/icons/children.svg';
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['articles']['label'] = ['Inhalte', 'Inhalt bearbeiten'];
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['articles']['href'] = 'mode=layout&table=tl_content';
                                    $GLOBALS['TL_DCA'][$ptable]['list']['operations']['articles']['button_callback'] = ['Bits\FlyUxBundle\Operation\Children', 'contentShowButton'];
                                    
                                    
                                    }
                                 
                                 $GLOBALS['BE_FLY_UX'][Input::get('do')]['showBtn'] = $ptable;
                              
                            }
                      
                        
                        $root = $GLOBALS['BE_FLY_UX'][Input::get('do')]['config']['relations'][$key];
                        }
                        
                              if($key === $count-1){
                                   $GLOBALS['TL_DCA'][$ptable]['list']['sorting']['mode'] = DataContainer::MODE_PARENT;
                                    $GLOBALS['TL_DCA'][$ptable]['config']['dataContainer'] = DC_Content::class;
                                    $GLOBALS['TL_DCA'][$ptable]['config']['switchToEdit'] = true;
                        
                        }
                    }
                   

                }

        }
    
   // }
   
}