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

#[AsCallback('tl_content','fields.inColumn.options')]
class InColumnContentCallback
{

	public function __invoke(DataContainer $dc)
	{
		$arrSections = array();
        $session = System::getContainer()->get('request_stack')->getSession();
        $pid = $session->getBag('contao_backend')->get('OP_ADD')['pid'];
        $ptable = $session->getBag('contao_backend')->get('OP_ADD')['parentTable'];
        $mode = $session->getBag('contao_backend')->get('OP_ADD')['mode'];
        
        if($mode==='layout'){
            
            
            if($ptable === 'tl_page'){
            // Show only active sections
                if ($pid ?? null)
                {
                    
                    $objPage = PageModel::findWithDetails($pid);
                    
                    
                }elseif(Input::get('id') ?? null){
                    $objPage = PageModel::findWithDetails(Input::get('id'));
                    
                
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
            }elseif($ptable !== 'tl_content'){
                        $arrSections[] = 'container';
                }
        }
		if($ptable === 'tl_content'){
             $el_count = $session->getBag('contao_backend')->get('OP_ADD')['el'];
             $plus = $session->getBag('contao_backend')->get('OP_ADD')['plus'];
             for ($i = 0; $i < $el_count; $i++) {
                            $arrSections[] = $plus.'-el-'.$i+1;
                        }
            
        }

		

		return Backend::convertLayoutSectionIdsToAssociativeArray($arrSections);
	}
    
  
    
   
}