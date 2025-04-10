<?php

namespace Bits\FlyUxBundle\Driver;

use Contao\DC_Folder;
use Contao\System;
use Contao\Input;
use Contao\FilesModel;


use Bits\FlyUxBundle\Service\ImageResizer;

class DC_Media extends DC_Folder
{
    
    private $imageResizer;
    private $container;
    
    public function __construct($table, $arrModule = [])
    {
        parent::__construct($table, $arrModule);

        // evtl. Umschaltlogik gleich hier prüfen
        if (Input::get('view') === 'media') {
            $this->mode = 'media';
            $this->container = System::getContainer();
           // $this->container->get('contao.theme')->addCssFile('vendor/flyux/assets/css/dc_media.css');
            
            $this->imageResizer = new ImageResizer( $this->container );
            
        }
    }
    
     public function generateTree($path, $intMargin, $mount = false, $blnProtected = true, $arrClipboard = null, $arrFound = [])
    {
        
        if($this->mode === 'media'){
            $arrFiles = array();

                // Erstelle eine Abfrage, die nur Bilder (z.B. JPEG, PNG, GIF) lädt
                $dbFiles = System::getContainer()->get('database_connection')
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
                            ;
                            $file['preview_path'] = $this->imageResizer->resizeAndCacheImage($file['path'], 300,300);
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
        // $objTemplate->src_preview = 
        $container = System::getContainer();
        
        $tokenManager = System::getContainer()->get('contao.csrf.token_manager');

		return $container->get('twig')->render(
			'@Contao/media_view.html.twig',
			array(
				'files' => $arrFiles,
                'token' => $tokenManager->getToken('contao_backend')->getValue()
			)
		);
    }
}
