<?php

namespace Bits\FlyUxBundle\Driver;

use Contao\DC_Folder;
use Contao\System;
use Contao\Input;
use Contao\Image;
use Contao\FilesModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Bits\FlyUxBundle\Service\ImageResizer;

class DC_Media extends DC_Folder
{
    
    private $imageResizer;
    private $container;
    private $mode;
    
    public function __construct($table, $arrModule = [])
    {
        

        // evtl. Umschaltlogik gleich hier prüfen
        if (Input::get('view') === 'media') {
            $this->mode = 'media';
            $this->container = System::getContainer();
           // $this->container->get('contao.theme')->addCssFile('vendor/flyux/assets/css/dc_media.css');
            
            $this->imageResizer = new ImageResizer( $this->container );
            parent::__construct($table, $arrModule); 
        }else{
         parent::__construct($table, $arrModule);   
        
            }
    }
    
     public function generateTree($path, $intMargin, $mount = false, $blnProtected = true, $arrClipboard = null, $arrFound = [])
    {
        
        if($this->mode === 'media'){
            $arrFiles = array();
             $strBaseUrl = $this->container->getParameter('kernel.project_dir');
                // Erstelle eine Abfrage, die nur Bilder (z.B. JPEG, PNG, GIF) lädt
                $dbFiles = $this->container->get('database_connection')
            ->fetchAllAssociative("
                SELECT * FROM tl_files
                WHERE type = 'file'
                AND (
                    extension = 'jpg'
                    OR extension = 'jpeg'
                    OR extension = 'png'
                    OR extension = 'gif'
                    OR extension = 'webp'
                )
                ORDER BY lastModified DESC
            ");

                if ($dbFiles !== null)
                {
                   $arrFiles = $dbFiles;
                   foreach ($arrFiles as &$file) {
                        if (isset($file['path'])) {
                            $objImage = new Image($file['path']);
                            $file['preview_path'] = $this->imageResizer->resizeAndCacheImage($file['path'], 300,300);
;                           $file['info_src'] = $this->urlEncode($strBaseUrl.'/'.$file['path']);
;
                        }
                    }
                    unset($file);
                }
             return $this->renderMediaView($arrFiles,$path);
             
             
        }else{
            
             //var_dump($this->__get('path'),$path);exit;
            return parent::generateTree($path, $intMargin, $mount, $blnProtected, $arrClipboard, $arrFound);
            
        }
      
       
    }

    protected function renderMediaView($arrFiles = array(),$path)
    {
        
        $requestStack = $this->container->get('request_stack');
        $request = $requestStack->getCurrentRequest();

        $baseUrl = $request->getSchemeAndHttpHost();
        
        $tokenManager = $this->container->get('contao.csrf.token_manager');
        
		return $this->container->get('twig')->render(
			'@Contao/media_view.html.twig',
			array(
                'baseUrl' => $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
				'files' => $arrFiles,
                'token' => $tokenManager->getToken('contao_backend')->getValue()
			)
		);
    }
}
