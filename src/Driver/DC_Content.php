<?php

namespace Bits\FlyUxBundle\Driver;

use Contao\DC_Table;
use Contao\ContaoBundle\RequestToken;
use Contao\System;
use Contao\Database;
use Contao\Input;
use Contao\Image;
use Contao\StringUtil;
use Contao\FilesModel;
use Contao\ContentModel;
use Contao\PageModel;
use Contao\LayoutModel;
use Contao\Versions;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\CoreBundle\Security\DataContainer\UpdateAction;
use Symfony\Component\HttpFoundation\RequestStack;
use Bits\FlyUxBundle\Service\ImageResizer;

class DC_Content extends DC_Table
{
    
    private $imageResizer;
    private $container;
    private $session;

    protected $arrModule;
    
    public function __construct($strTable, $arrModule=array())
    {
        
            $this->container = System::getContainer();
            $this->session = $this->container->get('request_stack')->getSession()->getBag('contao_backend');
            $this->strTable = $strTable;
             $this->arrModule = $arrModule;
           // parent::__construct($strTable, $arrModule);
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
        $objSession = $this->container->get('request_stack')->getSession();
        
        if(Input::get('do') === 'content'&&Input::get('mode') === 'layout'){
            
                $pTable = 'tl_page';
                $inColumn = 'main';
                
        }elseif((Input::get('do') === 'calendar'||Input::get('do') === 'news')&&Input::get('mode') === 'layout'){
                
                $pTable = (Input::get('do') === 'calendar')?'tl_calendar_events':'tl_news';
                $inColumn = 'container';
                
        }elseif((Input::get('do') === 'content'||Input::get('do') === 'calendar'||Input::get('do') === 'news')&&Input::get('mode') === 'plus'){
                
                $pTable = 'tl_content';
                $inColumn = Input::get('plus').'-el-1';
        }
        
        //set Session Array
        $this->session->set('OP_ADD' ,[
                    'pid' => Input::get('pid'),
                    'parentTable' => $pTable,
                    'mode' => Input::get('mode'),
                    'inColumn'=> $inColumn,
                    'el'=>Input::get('el'),
                    'plus'=>Input::get('plus')
                    ]);
        
        
        
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
            if($k ==='pid'){
                  
                  $this->set[$k] =   Input::get('pid');
                    
                    }    
      
            if($k ==='inColumn'){
          
                   $this->set[$k] = $inColumn;
                   
            }
		}

		// Set passed values
		if (!empty($set) && \is_array($set))
		{
			$this->set = array_merge($this->set, $set);
		}

		//var_dump( $this->set);exit;

		$this->set['tstamp'] = 0;// time();

		// Insert the record if the table is not closed and switch to edit mode
		if (!($GLOBALS['TL_DCA'][$this->strTable]['config']['closed'] ?? null))
		{
           // var_dump($this->set);exit;
			$objInsertStmt = $db
				->prepare("INSERT INTO " . $this->strTable . " %s")
				->set($this->set)
				->execute();
//var_dump($this->set,$objInsertStmt->affectedRows);exit;
			if ($objInsertStmt->affectedRows)
			{
				$s2e = ($GLOBALS['TL_DCA'][$this->strTable]['config']['switchToEdit'] ?? null) ? '&s2e=1' : '';
				
              
                $insertID = $objInsertStmt->insertId;

				// Save new record in the session
				$new_records = $this->session->get('new_records');
				$new_records[$this->strTable][] = $insertID;
         
				$this->session->set('new_records', $new_records);

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
            
        
        var_dump($table);exit;
            if(Input::get('do') === 'content'&&Input::get('mode') === 'layout'){
                
                $pTable = 'tl_page';

                 //find Layout of the page 
                 $pageModel = new PageModel;
                 $objPage = $pageModel::findById(Input::get('id'));
                 $layoutId = $objPage->loadDetails()->layout;
                 
                 
                 $layoutModel = new LayoutModel;
                 $objLayout = $layoutModel::findById($layoutId);
                 // find layout sections within sections and modules
                
                 // make an assoc array about the posibilities to include a section
                 $attrBlock = ['position'=>'default'];
                 
                 $htmlBlocks = [];
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
                    }
                      $arrElements = array();
                        $dbElements = $this->container->get('database_connection')
                        ->fetchAllAssociative(
                            "SELECT id, pid, headline, type, inColumn, cssId, el_count
                             FROM tl_content
                             WHERE pid = :pid
                               AND parentTable = :parentTable
                             ORDER BY pid ASC, sorting ASC",
                            [
                                'pid' => (int) Input::get('id'),
                                'parentTable' => (string) $pTable,
                            ]
                        );
                        
                        
                        if ($dbElements !== null)
                        {
                          $arrElements = $this->buildElements($dbElements,Input::get('id')); 
                           
                        }


                    
                    return $this->renderDetailView($objLayout,$htmlBlocks,$arrElements,$objPage,$objPage->__get('title'));  
             
                       // var_dump($htmlBlocks);exit;
                }elseif((Input::get('do') === 'calendar'||Input::get('do') === 'news')&&Input::get('mode') === 'layout'){
                 
                        $pTable = (Input::get('do') === 'calendar')?'tl_calendar_events':'tl_news';
                         
                        $htmlBlocks = [];
                        $htmlBlocks['container'] = [];
                        $arrElements = array();
                        $dbElements = $this->container->get('database_connection')
                        ->fetchAllAssociative(
                            "SELECT id, pid, headline, type, inColumn, cssId, el_count
                             FROM tl_content
                             WHERE pid = :pid
                               AND parentTable = :parentTable
                             ORDER BY sorting ASC",
                            [
                                'pid' => (int) Input::get('id'),
                       
                                'parentTable' => (string) $pTable
                            ]
                        );
                        
                         if ($dbElements !== null)
                        {
                          $arrElements = $this->buildElements($dbElements,Input::get('id')); 
                           }

                         return $this->renderDetailView(Null,$htmlBlocks,$arrElements,Null,'Details');
                        
                        
                 
                 
                 }elseif((Input::get('do') === 'content'||Input::get('do') === 'calendar'||Input::get('do') === 'news')&&Input::get('mode') === 'plus'){
                
                    $pTable = 'tl_content';

                        $htmlBlocks = [];
                        $htmlBlocks[Input::get('plus')] = [];
                        for ($i = 0; $i < Input::get('el'); $i++) {
                            $htmlBlocks[Input::get('plus')][Input::get('plus').'-el-'.$i+1] = [];
                        }
                        $arrElements = array();
                        $dbElements = $this->container->get('database_connection')
                        ->fetchAllAssociative(
                            "SELECT id, pid, headline, type, inColumn, cssId, el_count
                             FROM tl_content
                             WHERE pid = :pid
                               AND parentTable = :parentTable
                             ORDER BY sorting ASC",
                            [
                                'pid' => (int) $objSession->getBag('contao_backend')->get('OP_ADD_PID'),
                       
                                'parentTable' => (string) $pTable
                            ]
                        );
                        
                        if ($dbElements !== null)
                        {
                          $arrElements = $this->buildElements($dbElements,Input::get('id')); 
                         
                        }

                         return $this->renderDetailView(Null,$htmlBlocks,$arrElements,Null,'Content Plus');
                        
                }
      
       
    }
 

    protected function renderDetailView($objLayout = Null,$htmlBlocks,$arrElements,$objPage = Null,$headline)
    {
        
        $requestStack = $this->container->get('request_stack');
        $request = $requestStack->getCurrentRequest();

        $baseUrl = $request->getSchemeAndHttpHost();
        
        $tokenManager = $this->container->get('contao.csrf.token_manager');
     
		return $this->container->get('twig')->render(
			'@Contao/be_content_details.html.twig',
			array(
                'baseUrl' => $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
                'pageName' => $headline,
                'layoutClass' => ($objLayout)?$objLayout->__get('cssClass'):'details',
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
                if($element['pid'] ==='root'){
                     $branch[$element['id']] = $element;
                    }else{
                         $branch[] = $element;
                        }
               
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
                
                if(is_array( unserialize($element['cssId']))){
                     $cssId = unserialize($element['cssId'])[1];
                    }else{
                        $cssId = $this->cssId;
                        }
               
                $element['css_class'] = $cssId;
                $element['href_act_edit'] = 'contao?do=content&id='.$element['id'].'&table=tl_content&act=edit';
               $element['href_act_delete'] = 'contao?do=content&id='.$element['id'].'&table=tl_content&act=delete&rt='.$token;
                $element['is_content_plus'] = ($element['type']==='contentslider'||$element['type']==='grid')?true:false;
                $element['href_act_edit_plus'] = 'contao?do=content&mode=plus&table=tl_content&pid='.$element['pid'].'&id='.$element['id'].'&plus='.$element['type'].'&el='.$element['el_count'];
             
                
                 if ($children) {
                    $element['children'] = $children;
                  }
                $branch[$element['inColumn']][] = $element;
            }
        }

        return $branch;
    }
    
    protected function getElement(array $element)
    {
        
		$objCte = ContentModel::findById($element['id']);

		 if ($objCte !== null) {
        
            $row = $objCte;

            // Optional: nur wenn Spalte passt
            if ($row->type !== 'module'
                &&$row->type !== 'form'
                &&$row->type !== 'contentslider'
                &&$row->type !== 'grid'
            ) {
                $strClass = 'Contao\\Content' . ucfirst($row->type);
                
            }elseif($row->type === 'module'
                ||$row->type === 'contentslider'
                ||$row->type === 'grid'
            ){
               // $row->__set('cssId',$element['css_class']);
                 $strClass = 'Bits\\FlyUxBundle\\Content\\Content' . ucfirst($row->type);
            
            }elseif($row->type === 'form'){
                            $strClass = 'Contao\\Form';    
            }
                    

                if (class_exists($strClass)) {
                    /** @var \Contao\ContentElement $objElement */
                    $objElement = new $strClass($row);
                  
                     return  $objElement->generate();
                }
         }else{
             return '';
             } 
            
        
    }
    
      /**
	 * Delete all incomplete and unrelated records
	 */
	protected function reviseTable()
	{
        
        }
        
    
    
    
}
