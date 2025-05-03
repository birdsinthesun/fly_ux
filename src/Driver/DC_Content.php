<?php

namespace Bits\FlyUxBundle\Driver;

use Contao\DC_Table;
use Contao\ContaoBundle\RequestToken;
use Contao\System;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\Image;
use Contao\StringUtil;
use Contao\FilesModel;
use Contao\ContentModel;
use Contao\PageModel;
use Contao\Message;
use Contao\LayoutModel;
use Contao\Versions;
use Contao\CoreBundle\Security\DataContainer\CreateAction;
use Contao\CoreBundle\DataContainer\DataContainerOperationsBuilder;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\CoreBundle\Security\DataContainer\UpdateAction;
use Contao\EditableDataContainerInterface;
use Contao\ListableDataContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Bits\FlyUxBundle\Service\ImageResizer;

class DC_Content extends DC_Table implements EditableDataContainerInterface
{
    
    private $container;
    
    private $session;

    protected $arrModule;
    
    protected $strTable;
    
    public $intId;
    
    public function __construct($strTable, $arrModule=array())
    {

         $this->strTable = $strTable;
         $this->arrModule = $arrModule; 
        
        $configService = System::getContainer()->get('fly_ux.config_service');
        
        //if($configService->useflyUxDriver()&&$configService->isContentTable())
        //{           
     
        $this->container = System::getContainer();
        $this->session = $this->container->get('request_stack')->getSession()->getBag('contao_backend');
        
        if(Input::get('mode')){
                         $this->session->set('OP_ADD_MODE',Input::get('mode'));
            }
        if(Input::get('plus')){
                         
                        $inColumn = Input::get('plus').'-el-1';
                        $this->session->set('OP_ADD_COLUMN',$inColumn);
                        $this->session->set('OP_ADD_EL',Input::get('el'));
                        $this->session->set('OP_ADD_PLUS',Input::get('plus'));
            }
        if(Input::get('id')&&Input::get('act')!=='edit'){
                        $this->session->set('OP_ADD_PID',Input::get('id'));
            }
            
        if(Input::get('do') === 'content'&&Input::get('mode') === 'layout'&&Input::get('act')!=='edit'){
                    
                        $pTable = 'tl_page';
                        $inColumn = 'main';
                        
                        //set Session 
                        $this->session->set('OP_ADD_PID',Input::get('id'));
                        $this->session->set('OP_ADD_PTABLE',$pTable);
                        $this->session->set('OP_ADD_MODE',Input::get('mode'));
                        $this->session->set('OP_ADD_COLUMN',$inColumn);
                       // $this->session->set('OP_ADD_EL',$this->session->get('OP_ADD_EL'));
                       // $this->session->set('OP_ADD_PLUS',$this->session->get('OP_ADD_PLUS'));
                          
                        
                }elseif((Input::get('do') === 'calendar'||Input::get('do') === 'news')
                        &&Input::get('mode') === 'layout'&&Input::get('act')!=='edit'){
                        
                        $pTable = (Input::get('do') === 'calendar')?'tl_calendar_events':'tl_news';
                        $inColumn = 'container';
                        //set Session 
                        $this->session->set('OP_ADD_PID',Input::get('id'));
                        $this->session->set('OP_ADD_PTABLE',$pTable);
                        
                        $this->session->set('OP_ADD_COLUMN',$inColumn);
                        //$this->session->set('OP_ADD_EL',$this->session->get('OP_ADD_EL'));
                       // $this->session->set('OP_ADD_PLUS',$this->session->get('OP_ADD_PLUS'));
                        
                }elseif(Input::get('do') === 'content'
                        &&Input::get('mode') === 'layout'&&Input::get('act')!=='edit'){
                        
                        $pTable = 'tl_content';
                        //set Session 
                        $this->session->set('OP_ADD_PTABLE',$pTable);
                        
                        
                }
                    if($this->session->get('OP_ADD_MODE') === 'plus'){
                     $pTable = 'tl_content';
                     $inColumn = $this->session->get('OP_ADD_PLUS').'-el-1';
                        //set Session 
                        $this->session->set('OP_ADD_PID',$this->session->get('OP_ADD_PID'));
                        $this->session->set('OP_ADD_PTABLE',$pTable);
                        
                        $this->session->set('OP_ADD_COLUMN',$inColumn);
                        $this->session->set('OP_ADD_EL',$this->session->get('OP_ADD_EL'));
                        $this->session->set('OP_ADD_PLUS',$this->session->get('OP_ADD_PLUS'));                           
                    
                    }
                    
                    
             
            // var_dump($this->session->get('OP_ADD')['plus']);exit;
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
		
         $configService = System::getContainer()->get('fly_ux.config_service');
               
      //  if($configService->useflyUxDriver()&&$configService->isContentTable())
       // {
        
                $db = Database::getInstance();
                $databaseFields = $db->getFieldNames($this->strTable);
                
                if(Input::get('do') === 'content'&&$this->session->get('OP_ADD_MODE') === 'layout'){
                    
                        $pTable = 'tl_page';
                        $inColumn = 'main';
                }elseif((Input::get('do') === 'calendar'||Input::get('do') === 'news')&&$this->session->get('OP_ADD_MODE') === 'layout'){
                        
                        $pTable = (Input::get('do') === 'calendar')?'tl_calendar_events':'tl_news';
                        $inColumn = 'container';
                        $this->session->set('OP_ADD_PID',Input::get('id'));
                        
                }elseif($this->session->get('OP_ADD_MODE') === 'plus'){
                    
                        $pTable = 'tl_content';
                        
                        $inColumn = $this->session->get('OP_ADD_PLUS').'-el-1';
                       
                    } 
                
                
                
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
                    

                    if($k ==='parentTable'){
                  
                           $this->set[$k] = $pTable;
                           
                    }
                }
                    $this->set['ptable'] = $pTable;
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
        
                    if ($objInsertStmt->affectedRows)
                    {
                        $s2e = ($GLOBALS['TL_DCA'][$this->strTable]['config']['switchToEdit'] ?? null) ? '&s2e=1' : '';
                        
                      
                        $insertID = $objInsertStmt->insertId;
                        $this->intId = $insertID;
                        // Save new record in the session
                        //$objSession = $this->container->get('request_stack')->getSession()->getBag('contao_backend');
                        $new_records = $this->session->get('new_records');
                        $new_records[$this->strTable][] = $insertID;
                 //var_dump($insertID,$this->set,$new_records[$this->strTable]);exit;
                        $this->session->set('new_records', $new_records);

                       // System::getContainer()->get('monolog.logger.contao.general')->info('A new entry "' . $this->strTable . '.id=' . $insertID . '" has been created' . $this->getParentEntries($this->strTable, $insertID));

                        $this->redirect($this->switchToEdit($insertID) . $s2e);
                    }
                }

                $this->redirect($this->getReferer());
            
       // }else{
           // parent::create($set);
       // }
	}
    
   
     public function parentView()
    {
        
  
         $configService = System::getContainer()->get('fly_ux.config_service');
      //    if($configService->useflyUxDriver()&&$configService->isContentTable())
      //  {    
          
					$operations = $this->generateGlobalButtons();
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


                                
                                return $this->renderDetailView($objLayout,$htmlBlocks,$arrElements,$objPage,$objPage->__get('title'),$operations);  
                         
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

                                     return $this->renderDetailView(Null,$htmlBlocks,$arrElements,Null,'Details',$operations);
                                    
                                    
                             
                             
                             }elseif(Input::get('mode') === 'plus'){
                            
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
                                            'pid' => (int) Input::get('id'),
                                   
                                            'parentTable' => (string) $pTable
                                        ]
                                    );
                                    
                                    if ($dbElements !== null)
                                    {
                                      $arrElements = $this->buildElements($dbElements,Input::get('id')); 
                                     
                                    }

                                     return $this->renderDetailView(Null,$htmlBlocks,$arrElements,Null,'Content Plus',$operations);
                                    
                            }
                
          //  }else{
                
             //   parent::parentView();
                
          //  }
      
       
    }
 

    protected function renderDetailView($objLayout = Null,$htmlBlocks,$arrElements,$objPage = Null,$headline,$operations)
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
                'elementsByBlock' => $arrElements,
                'operations' => $operations
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
                $element['href_act_edit_plus'] = 'contao?do='.Input::get('do').'&mode=plus&table=tl_content&pid='.$element['pid'].'&id='.$element['id'].'&plus='.$element['type'].'&el='.$element['el_count'];
             
                
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
    
   
    public function edit($intId=null, $ajaxId=null)
	{
        if(Input::get('act') !== 'edit'){
          
            $this->parentView();
        }
        
        elseif(Input::get('act') === 'edit'){
        
            
            if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notEditable'] ?? null)
            {
              //  throw new AccessDeniedException('Table "' . $this->strTable . '" is not editable.');
            }

            if ($intId)
            {
                $this->intId = $intId;
            }
            //self::preloadCurrentRecords([$this->intId],$this->strTable);
            // Get the current record
            $currentRecord = $this->getCurrentRecord($this->intId);
//var_dump($currentRecord);
            // Redirect if there is no record with the given ID
            if (null === $currentRecord)
            {
                $currentRecord = $this->container->get('database_connection')
                                    ->fetchAllAssociative(
                                        "SELECT *
                                         FROM ".$this->strTable."
                                         WHERE id = :id",
                                        [
                                            'id' => (int) Input::get($this->intId)
                                        ]
                                    );
                
                
                // throw new NotFoundException('Cannot load record "' . $this->strTable . '.id=' . $this->intId . '".');
            }

           // $this->denyAccessUnlessGranted(ContaoCorePermissions::DC_PREFIX . $this->strTable, new UpdateAction($this->strTable, $currentRecord));

            // Store the active record (backwards compatibility)
            $this->objActiveRecord = (object) $currentRecord;

            $return = '';
            $this->values[] = $this->intId;
            $this->procedure[] = 'id=?';
            $this->arrSubmit = array();
            $this->blnCreateNewVersion = false;
            $objVersions = new Versions($this->strTable, $this->intId);

            if (!($GLOBALS['TL_DCA'][$this->strTable]['config']['hideVersionMenu'] ?? null))
            {
                // Compare versions
                if (Input::get('versions'))
                {
                    $objVersions->compare();
                }

                // Restore a version
                if (Input::post('FORM_SUBMIT') == 'tl_version' && Input::post('version'))
                {
                    $objVersions->restore(Input::post('version'));

                    $this->invalidateCacheTags();

                    $this->reload();
                }
            }

            $objVersions->initialize();
            $intLatestVersion = $objVersions->getLatestVersion();

            $security = System::getContainer()->get('security.helper');

            // Build an array from boxes and rows
            $this->strPalette = $this->getPalette();
            $boxes = StringUtil::trimsplit(';', $this->strPalette);
            //var_dump($this->strPalette );
            $legends = array();

            if (!empty($boxes))
            {
                foreach ($boxes as $k=>$v)
                {
                   
                    $eCount = 1;
                    $boxes[$k] = StringUtil::trimsplit(',', $v);

                    foreach ($boxes[$k] as $kk=>$vv)
                    {
                       
                        if (preg_match('/^\[.*]$/', $vv))
                        {
                            ++$eCount;
                            continue;
                        }

                        if (preg_match('/^{.*}$/', $vv))
                        {
                            $legends[$k] = substr($vv, 1, -1);
                            unset($boxes[$k][$kk]);
                        }
                        elseif (!\is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv] ?? null) || (DataContainer::isFieldExcluded($this->strTable, $vv) && !$security->isGranted(ContaoCorePermissions::USER_CAN_EDIT_FIELD_OF_TABLE, $this->strTable . '::' . $vv)))
                        {
                           // unset($boxes[$k][$kk]);
                        }
                          
                    }

                    // Unset a box if it does not contain any fields
                    if (\count($boxes[$k]) < $eCount)
                    {
                        unset($boxes[$k]);
                    }
                }

                $objSessionBag = System::getContainer()->get('request_stack')->getSession()->getBag('contao_backend');

                $class = 'tl_tbox';
                $fs = $objSessionBag->get('fieldset_states');

                // Render boxes
                foreach ($boxes as $k=>$v)
                {
                    
                   
                    $arrAjax = array();
                    $blnAjax = false;
                    $key = '';
                    $cls = '';
                    $legend = '';

                    if (isset($legends[$k]))
                    {
                        list($key, $cls) = explode(':', $legends[$k]) + array(null, null);

                        $legend = "\n" . '<legend><button type="button" data-action="contao--toggle-fieldset#toggle">' . ($GLOBALS['TL_LANG'][$this->strTable][$key] ?? $key) . '</button></legend>';
                    }

                    if ($legend)
                    {
                        if (isset($fs[$this->strTable][$key]))
                        {
                            $class .= ($fs[$this->strTable][$key] ? '' : ' collapsed');
                        }
                        elseif ($cls)
                        {
                            // Convert the ":hide" suffix from the DCA
                            if ($cls == 'hide')
                            {
                                $cls = 'collapsed';
                            }

                            $class .= ' ' . $cls;
                        }
                    }

                    $return .= "\n\n" . '<fieldset class="' . $class . ($legend ? '' : ' nolegend') . '" data-controller="contao--toggle-fieldset" data-contao--toggle-fieldset-id-value="' . $key . '" data-contao--toggle-fieldset-table-value="' . $this->strTable . '" data-contao--toggle-fieldset-collapsed-class="collapsed" data-contao--jump-targets-target="section" data-contao--jump-targets-label-value="' . ($GLOBALS['TL_LANG'][$this->strTable][$key] ?? $key) . '" data-action="contao--jump-targets:scrollto->contao--toggle-fieldset#open">' . $legend . "\n" . '<div class="widget-group">';
                    $thisId = '';

                    // Build rows of the current box
                    foreach ($v as $vv)
                    {
                       
                        if ($vv == '[EOF]')
                        {
                            if ($blnAjax && Environment::get('isAjaxRequest'))
                            {
                                if ($ajaxId == $thisId)
                                {
                                    if (($intLatestVersion = $objVersions->getLatestVersion()) !== null)
                                    {
                                        $arrAjax[$thisId] .= '<input type="hidden" name="VERSION_NUMBER" value="' . $intLatestVersion . '">';
                                    }

                                    return $arrAjax[$thisId];
                                }

                                if (\count($arrAjax) > 1)
                                {
                                    $current = "\n" . '<div id="' . $thisId . '" class="subpal widget-group">' . $arrAjax[$thisId] . '</div>';
                                    unset($arrAjax[$thisId]);
                                    end($arrAjax);
                                    $thisId = key($arrAjax);
                                    $arrAjax[$thisId] .= $current;
                                }
                            }

                            $return .= "\n" . '</div>';

                            continue;
                        }

                        if (preg_match('/^\[.*]$/', $vv))
                        {
                            $thisId = 'sub_' . substr($vv, 1, -1);
                            $arrAjax[$thisId] = '';
                            $blnAjax = ($ajaxId == $thisId && Environment::get('isAjaxRequest')) ? true : $blnAjax;
                            $return .= "\n" . '<div id="' . $thisId . '" class="subpal widget-group">';

                            continue;
                        }

                        $this->strField = $vv;
                        $this->strInputName = $vv;
                        $this->varValue = $currentRecord[$vv] ?? null;

                        // Convert CSV fields (see #2890)
                        if (($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'] ?? null) && isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['csv']))
                        {
                            $this->varValue = StringUtil::trimsplit($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['csv'], $this->varValue);
                        }

                        // Call load_callback
                        if (\is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] ?? null))
                        {
                            foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
                            {
                                if (\is_array($callback))
                                {
                                    $this->varValue = System::importStatic($callback[0])->{$callback[1]}($this->varValue, $this);
                                }
                                elseif (\is_callable($callback))
                                {
                                    $this->varValue = $callback($this->varValue, $this);
                                }
                            }
                        }

                        // Re-set the current value
                        $this->objActiveRecord->{$this->strField} = $this->varValue;

                        // Build the row and pass the current palette string (thanks to Tristan Lins)
                        $blnAjax ? $arrAjax[$thisId] .= $this->row() : $return .= $this->row();
                    }

                    $class = 'tl_box';
                    $return .= "\n</div>\n</fieldset>";
                   // return $return;
                }

                $this->submit();
            }
            // Reload the page to prevent _POST variables from being sent twice
		if (!$this->noReload && Input::post('FORM_SUBMIT') == $this->strTable)
		{
			// Show a warning if the record has been saved by another user (see #8412)
			if ($intLatestVersion !== null && Input::post('VERSION_NUMBER') !== null && $intLatestVersion > Input::post('VERSION_NUMBER'))
			{
				$objTemplate = new BackendTemplate('be_conflict');
				$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
				$objTemplate->title = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['versionConflict']);
				$objTemplate->theme = Backend::getTheme();
				$objTemplate->charset = System::getContainer()->getParameter('kernel.charset');
				$objTemplate->h1 = $GLOBALS['TL_LANG']['MSC']['versionConflict'];
				$objTemplate->explain1 = \sprintf($GLOBALS['TL_LANG']['MSC']['versionConflict1'], $intLatestVersion, Input::post('VERSION_NUMBER'));
				$objTemplate->explain2 = \sprintf($GLOBALS['TL_LANG']['MSC']['versionConflict2'], $intLatestVersion + 1, $intLatestVersion);
				$objTemplate->diff = $objVersions->compare(true);
				$objTemplate->href = Environment::get('requestUri');
				$objTemplate->button = $GLOBALS['TL_LANG']['MSC']['continue'];

				// We need to set the status code to either 4xx or 5xx in order for Turbo to render this response.
				$response = $objTemplate->getResponse();
				$response->setStatusCode(Response::HTTP_CONFLICT);

				throw new ResponseException($response);
			}

			// Redirect
			if (Input::post('saveNclose') !== null)
			{
				Message::reset();

				$this->redirect($this->getReferer());
			}
			elseif (Input::post('saveNedit') !== null)
			{
				Message::reset();

				$this->redirect($this->addToUrl($GLOBALS['TL_DCA'][$this->strTable]['list']['operations']['children']['href'] ?? '', false, array('s2e', 'act', 'mode', 'pid')));
			}
			elseif (Input::post('saveNback') !== null)
			{
				Message::reset();

				if (!$this->ptable)
				{
					$this->redirect(System::getContainer()->get('router')->generate('contao_backend') . '?do=' . Input::get('do'));
				}
				// TODO: try to abstract this
				elseif ($this->ptable == 'tl_page' && $this->strTable == 'tl_article')
				{
					$this->redirect($this->getReferer(false, $this->strTable));
				}
				else
				{
					$this->redirect($this->getReferer(false, $this->ptable));
				}
			}
			elseif (Input::post('saveNcreate') !== null)
			{
				Message::reset();

				$strUrl = System::getContainer()->get('router')->generate('contao_backend') . '?do=' . Input::get('do');

				if (Input::get('table') !== null)
				{
					$strUrl .= '&amp;table=' . Input::get('table');
				}

				// Tree view
				if ($this->treeView)
				{
					$strUrl .= '&amp;act=create&amp;mode=1&amp;pid=' . $this->intId;
				}

				// Parent view
				elseif (($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] ?? null) == self::MODE_PARENT)
				{
					$strUrl .= Database::getInstance()->fieldExists('sorting', $this->strTable) ? '&amp;act=create&amp;mode=1&amp;pid=' . $this->intId : '&amp;act=create&amp;mode=2&amp;pid=' . ($currentRecord['pid'] ?? null);

					if (($currentRecord['ptable'] ?? null) === $this->strTable)
					{
						$strUrl .= '&amp;ptable=' . $currentRecord['ptable'];
					}
				}

				// List view
				else
				{
					$strUrl .= $this->ptable ? '&amp;act=create&amp;mode=2&amp;pid=' . $this->intCurrentPid : '&amp;act=create';
				}

				$this->redirect($strUrl . '&amp;rt=' . System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue());
			}
			elseif (Input::post('saveNduplicate') !== null)
			{
				Message::reset();

				$strUrl = System::getContainer()->get('router')->generate('contao_backend') . '?do=' . Input::get('do');

				if (Input::get('table') !== null)
				{
					$strUrl .= '&amp;table=' . Input::get('table');
				}

				// Tree view
				if ($this->treeView)
				{
					$strUrl .= '&amp;act=copy&amp;mode=1&amp;id=' . $this->intId . '&amp;pid=' . $this->intId;
				}

				// Parent view
				elseif (($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] ?? null) == self::MODE_PARENT)
				{
					$strUrl .= Database::getInstance()->fieldExists('sorting', $this->strTable) ? '&amp;act=copy&amp;mode=1&amp;pid=' . $this->intId . '&amp;id=' . $this->intId : '&amp;act=copy&amp;mode=2&amp;pid=' . $this->intCurrentPid . '&amp;id=' . $this->intId;

					if (($currentRecord['ptable'] ?? null) === $this->strTable)
					{
						$strUrl .= '&amp;ptable=' . $currentRecord['ptable'];
					}
				}

				// List view
				else
				{
					$strUrl .= $this->ptable ? '&amp;act=copy&amp;mode=2&amp;pid=' . $this->intCurrentPid . '&amp;id=' . $this->intId : '&amp;act=copy&amp;id=' . $this->intId;
				}

				$this->redirect($strUrl . '&amp;rt=' . System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue());
			}

			$this->reload();
		}

		// Versions overview
		if (($GLOBALS['TL_DCA'][$this->strTable]['config']['enableVersioning'] ?? null) && !($GLOBALS['TL_DCA'][$this->strTable]['config']['hideVersionMenu'] ?? null))
		{
			$version = $objVersions->renderDropdown();
		}
		else
		{
			$version = '';
		}

		$strButtons = System::getContainer()
			->get('contao.data_container.buttons_builder')
			->generateEditButtons(
				$this->strTable,
				(bool) $this->ptable,
				$security->isGranted(ContaoCorePermissions::DC_PREFIX . $this->strTable, new CreateAction($this->strTable,[ 'test'])),
				$security->isGranted(ContaoCorePermissions::DC_PREFIX . $this->strTable, new CreateAction($this->strTable, array_replace($currentRecord, array('id' => null, 'sorting' => null)))),
				$this
			);

		// Add the buttons and end the form
		$return .= '
</div>
  ' . $strButtons . '
</form>';

		$strVersionField = '';

		// Store the current version number (see #8412)
		if ($intLatestVersion !== null)
		{
			$strVersionField = '
<input type="hidden" name="VERSION_NUMBER" value="' . $intLatestVersion . '">';
		}

		$strBackUrl = $this->getReferer(true);

		if ((string) $currentRecord['tstamp'] === '0')
		{
			$strBackUrl = preg_replace('/&(?:amp;)?revise=[^&]+|$/', '&amp;revise=' . $this->strTable . '.' . ((int) $this->intId), $strBackUrl, 1);

			$return .= '
<script>
  history.pushState({}, "");
  window.addEventListener("popstate", () => fetch(document.querySelector(".header_back").href).then(() => history.back()));
</script>';
		}

		// Begin the form (-> DO NOT CHANGE THIS ORDER -> this way the onsubmit attribute of the form can be changed by a field)
		$return = $version . Message::generate() . ($this->noReload ? '
<p class="tl_error">' . $GLOBALS['TL_LANG']['ERR']['submit'] . '</p>' : '') . (Input::get('nb') ? '' : '
<div id="tl_buttons">
' . DataContainerOperationsBuilder::generateBackButton($strBackUrl) . '
</div>') . '
<form id="' . $this->strTable . '" class="tl_form tl_edit_form" method="post" enctype="' . ($this->blnUploadable ? 'multipart/form-data' : 'application/x-www-form-urlencoded') . '"' . (!empty($this->onsubmit) ? ' onsubmit="' . implode(' ', $this->onsubmit) . '"' : '') . '>
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="' . $this->strTable . '">
<input type="hidden" name="REQUEST_TOKEN" value="' . htmlspecialchars(System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue(), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5) . '">' . $strVersionField . $return;

		$return = '
<div data-controller="contao--jump-targets">
	<div class="jump-targets"><div class="inner" data-contao--jump-targets-target="navigation"></div></div>
	' . $return . '
</div>';

		return $return;
            
            
        }
    }
  

 
      /**
	 * Delete all incomplete and unrelated records
	 */
	protected function reviseTable()
	{
        
        }
    
    
        
    
    
    
}
