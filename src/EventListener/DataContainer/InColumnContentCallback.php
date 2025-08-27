<?php


namespace Bits\FlyUxBundle\EventListener\DataContainer;

use Contao\Backend;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\System;
use Contao\DC_Table;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback(table: 'tl_content', target: 'fields.inColumn.options')]
class InColumnContentCallback
{

	public function __invoke(DataContainer $dc)
	{
		$arrSections = array();
        $session = System::getContainer()->get('request_stack')->getSession()->getBag('contao_backend');
        $pid = $session->get('OP_ADD_PID');
        
        $ptable = $dc->__get('parentTable');//$session->get('OP_ADD_PTABLE');
        $mode = $session->get('OP_ADD_MODE');
        
        
       // $typePlus = (array_key_exists($dc->activeRecord->type,$GLOBALS['TL_CTE']['plus']));
       // var_dump($pid,$typePlus,$mode,$ptable);exit;
        if($mode === 'layout' || $mode === null){
            
            
            if($ptable === 'tl_page'){
            // Show only active sections
                if ($pid !== null)
                {
                    
                    $objPage = PageModel::findWithDetails($pid);
                    
                    
                }elseif($dc->getCurrentRecord() !== null){
                    $objPage = PageModel::findWithDetails($dc->getCurrentRecord()['pid']);
                    
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
            }elseif($ptable === 'tl_theme'){
                        $arrSections[] = 'main';
                        //Todo: add all sections
                }elseif($ptable !== 'tl_content'){
                        $arrSections[] = 'main';
                }
            
        }
		elseif($ptable === 'tl_content'){
              $parentRecord = System::getContainer()->get('database_connection')
                                    ->fetchAllAssociative(
                                        "SELECT el_count,type
                                         FROM ".$ptable."
                                         WHERE id = :id",
                                        [
                                            'id' => (int) $pid
                                        ]
                                    );
             
             for ($i = 0; $i < $parentRecord[0]['el_count']; $i++) {
                            $arrSections[$parentRecord[0]['type'].'-el-'.$i+1] = $parentRecord[0]['type'].'-el-'.$i+1;
                        }
            
        }

		return $arrSections;
	}
    
  
    
   
}