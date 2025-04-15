<?php

namespace Bits\FlyUxBundle\Driver;

use Contao\DC_Table;
use Contao\ContaoBundle\RequestToken;
use Contao\System;
use Contao\Database;
use Contao\Input;
use Contao\Image;
use Contao\FilesModel;
use Contao\ContentModel;
use Contao\PageModel;
use Contao\LayoutModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Bits\FlyUxBundle\Service\ImageResizer;

class DC_Content extends DC_Table
{
    
    private $imageResizer;
    private $container;

    protected $strTable;
    protected $arrModule;
    
    public function __construct($strTable, $arrModule=array())
    {
        
            $this->container = System::getContainer();
            
            $this->strTable = $strTable;
             $this->arrModule = $arrModule;
           // $this->container->get('contao.theme')->addCssFile('vendor/flyux/assets/css/dc_media.css');
            
           // $this->imageResizer = new ImageResizer( $this->container );
           parent::__construct($strTable, $arrModule); 
       
    }
    
    /**
	 * Insert a new row into a database table
	 *
	 * @param array $set
	 *
	 * @throws AccessDeniedException
	 */
	public function create($set=array())
	{
		
        $db = Database::getInstance();
		$databaseFields = $db->getFieldNames($this->strTable);

		// Get all default values for the new entry
		foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'] as $k=>$v)
		{
			// Use array_key_exists here (see #5252)
			if (\array_key_exists('default', $v) && \in_array($k, $databaseFields, true))
			{
				$default = $v['default'];

				if ($default instanceof \Closure)
				{
					$default = $default($this);
				}

				$this->set[$k] = \is_array($default) ? serialize($default) : $default;
			}
		}

		// Set passed values
		if (!empty($set) && \is_array($set))
		{
			$this->set = array_merge($this->set, $set);
		}

		$objSession = System::getContainer()->get('request_stack')->getSession();

		$this->set['tstamp'] = 0;// time();

		// Insert the record if the table is not closed and switch to edit mode
		if (!($GLOBALS['TL_DCA'][$this->strTable]['config']['closed'] ?? null))
		{
			$objInsertStmt = $db
				->prepare("INSERT INTO " . $this->strTable . " %s")
				->set($this->set)
				->execute();

			if ($objInsertStmt->affectedRows)
			{
				$s2e = ($GLOBALS['TL_DCA'][$this->strTable]['config']['switchToEdit'] ?? null) ? '&s2e=1' : '';
				
                
                $insertID = $objInsertStmt->insertId;
                $insertPID = $objInsertStmt->insertPid;

				$objSessionBag = $objSession->getBag('contao_backend');

				// Save new record in the session
				$new_records = $objSessionBag->get('new_records');
				$new_records[$this->strTable][] = $insertID;
            
         
				$objSessionBag->set('new_records', $new_records);

               

				System::getContainer()->get('monolog.logger.contao.general')->info('A new entry "' . $this->strTable . '.id=' . $insertID . '" has been created' . $this->getParentEntries($this->strTable, $insertID));

				$this->redirect($this->switchToEdit($insertID) . $s2e);
			}
		}

		$this->redirect($this->getReferer());
	}
    
     public function generateTree($table, $id, $arrPrevNext, $blnHasSorting,
 $intMargin=0, $arrClipboard=null, $blnCircularReference=false, 
 $protectedPage=false, $blnNoRecursion=false, $arrFound=array())
    {
            
        
      
              if(Input::get('pid')===Null){
        
                $arrPages = array();
                $dbPages = $this->container->get('database_connection')
                ->fetchAllAssociative("
                SELECT id,pid,title FROM tl_page
                ORDER BY pid ASC, sorting ASC
            ");
                
                
                if ($dbPages !== null)
                {
                  $arrPages = $this->buildTree($dbPages); 
                  
                 
                }
             return $this->renderListView($arrPages);
             }else{
                 $objSession = System::getContainer()->get('request_stack')->getSession();
                $objSession->getBag('contao_backend')->set('OP_ADD_PID',Input::get('pid'));
                 //find Layout of the page 
                 $pageModel = new PageModel;
                 $objPage = $pageModel::findById(Input::get('pid'));
                 $layoutId = $objPage->loadDetails()->layout;
                 
                 
                 $layoutModel = new LayoutModel;
                 $objLayout = $layoutModel::findById($layoutId);
                 // find layout sections within sections and modules
                
                 // make an assoc array about the posibilities to include a section
                 $attrBlock = ['position'=>'default'];
                 
                 $htmlBlocks = array();
                 $htmlBlocks['container'] = $attrBlock;
                 
                 
                    foreach(unserialize($objLayout->modules) as $module){
                         if($module['mod'] !== '0'){
                            continue;
                             }
                             
                  
                       //var_dump(unserialize($objLayout->sections));exit;
                         foreach(unserialize($objLayout->sections) as $section){
                             
                         //  var_dump($module['col'] === $section['id'],$section['id'],$module['col']);  
                             if($section['position'] === 'top'
                             &&$module['col'] === $section['id']){
                                 $htmlBlocks[$section['id']] = ['position'=>'top'];
                                 }
                             elseif($section['position'] === 'before'
                             &&$module['col'] === $section['id']){
                                     
                                 $htmlBlocks['container'][$section['id']] = ['position'=>'before'];  
                                $htmlBlocks['container'][$module['col']] = $attrBlock;
                                
                            }elseif($section['position'] === $module['col']
                            &&$module['col'] === $section['id']){
                                     
                                 $htmlBlocks['container'][$module['col']][$section['id']] = ['position'=>'main'];    
                            }elseif($section['position'] === 'after'
                            &&$module['col'] === $section['id']){
                                
                                $htmlBlocks['container'][$module['col']] = $attrBlock;
                                 $htmlBlocks['container'][$section['id']] = ['position'=>'after'];    
                            }elseif($section['position'] === 'bottom'
                            &&$module['col'] === $section['id']){
                                     
                                 $htmlBlocks[$section['id']] = ['position'=>'bottom'];    
                            }else{
                                 $htmlBlocks['container'][$module['col']] = $attrBlock;
                                
                                }
                             
                        }
                        
                       // var_dump($htmlBlocks);exit;
                  }
                
                 
                 
                 
                 $arrElements = array();
                $dbElements = $this->container->get('database_connection')
                ->fetchAllAssociative("
                SELECT id,pid,headline,type,inColumn,cssId FROM tl_content
                WHERE pid = ".Input::get('pid')."
                ORDER BY pid ASC, sorting ASC
            ");
                
                
                if ($dbElements !== null)
                {
                  $arrElements = $this->buildElements($dbElements,Input::get('pid')); 
                   // var_dump($arrElements);exit;
                }
                 
                 
                 
                 
                 
                 
            
               return $this->renderDetailView($htmlBlocks,$arrElements,$objPage );  
            } 
            
       
    }

    protected function renderListView($arrPages = array())
    {
        
        $requestStack = $this->container->get('request_stack');
        $request = $requestStack->getCurrentRequest();

        $baseUrl = $request->getSchemeAndHttpHost();
        //$token = RequestToken::get();
       // $tokenManager = $this->container->get('contao.csrf.token_manager');
       // var_dump(System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue() );exit;
		return $this->container->get('twig')->render(
			'@Contao/be_content_list.html.twig',
			array(
                'baseUrl' => $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
				'tree' => $arrPages,
                'token' => System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue() 
                
			)
		);
    
    }
    protected function renderDetailView($htmlBlocks = array(),$arrElements = array(),$objPage )
    {
        
        $requestStack = $this->container->get('request_stack');
        $request = $requestStack->getCurrentRequest();

        $baseUrl = $request->getSchemeAndHttpHost();
        
        $tokenManager = $this->container->get('contao.csrf.token_manager');
     
		return $this->container->get('twig')->render(
			'@Contao/be_content_details.html.twig',
			array(
                'baseUrl' => $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
                'pageName' => $objPage->__get('title'),
                'htmlBlocks' =>$htmlBlocks,
                'elementsByBlock' => $arrElements
			)
		);
    
    }
    protected function buildTree(array $elements, int $parentId = 0): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ((int)$element['pid'] === $parentId) {
                $children = $this->buildTree($elements, (int)$element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
    
    protected function buildElements(array $elements, int $parentId = 0): array
    {
        $branch = [];
        $token = System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();
        foreach ($elements as $element) {
            if ((int)$element['pid'] === $parentId) {
                $children = $this->buildTree($elements, (int)$element['id']);
                $element['content_element'] = $this->getElement($element);
                $element['css_class'] = unserialize($element['cssId'])[1];
                $element['href_act_edit'] = 'contao?do=content&id='.$element['id'].'&table=tl_content&act=edit';
               $element['href_act_delete'] = 'contao?do=content&id='.$element['id'].'&table=tl_content&act=delete&rt='.$token;
               
                if ($children) {
                    $element['children'] = $children;
                  }
                $branch[$element['inColumn']][] = $element;
            }
        }

        return $branch;
    }
    
    protected function getElement(array $element): string
    {
        
		$objCte = ContentModel::findById($element['id']);

		 if ($objCte !== null) {
        
            $row = $objCte;

            // Optional: nur wenn Spalte passt
            if ($row->type !== 'module') {
                $strClass = 'Contao\\Content' . ucfirst($row->type);
            }else{
                 $strClass = 'Bits\\FlyUxBundle\\Content\\Content' . ucfirst($row->type);
                }

                if (class_exists($strClass)) {
                    /** @var \Contao\ContentElement $objElement */
                    $objElement = new $strClass($row);
                    
                    //var_dump($objElement->generate());
                     return  $objElement->generate();
                }
            
            
        }
    }
        
    
    
    
}
