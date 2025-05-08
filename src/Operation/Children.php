<?php
namespace Bits\FlyUxBundle\Operation;

use Contao\Backend;
use Contao\System;
use Contao\Input;

class Children extends Backend
{

 public static function contentShowButton($arrRow, $strHref, $strLabel, $strTitle, $strIcon, $arrAtrributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext, $objThis): string
    {
        $container = System::getContainer();
        $tokenManager = $container->get('contao.csrf.token_manager');
        $token = $tokenManager->getToken('contao.csrf.token')->getValue();
        $showBtn = $GLOBALS['BE_FLY_UX']['content'][Input::get('do')]['showBtn'];
        $do = (Input::get('do')==='page')?'content':Input::get('do');
        if($showBtn){
          
            return '<a href="contao?do='.$do.'&mode=layout&table=tl_content&id=' . $arrRow['id']. '&amp;rt='.$token . '" title="Inhalte ID ' . $arrRow['id']. ' bearbeiten" ' . $arrAtrributes . '>
                <img src="system/themes/flexible/icons/children.svg" alt="Inhalte zeigen und bearbeiten">
            </a>';
            
        }else{
             return ''; 
            }
    }
    
}