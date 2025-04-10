<?php

namespace Bits\FlyUxBundle\Driver;

use Contao\DC_Folder;
use Contao\System;
use Contao\Input;
use Contao\FilesModel;

class DC_Media extends DC_Folder
{
    public function __construct($table, $arrModule = [])
    {
        parent::__construct($table, $arrModule);

        // evtl. Umschaltlogik gleich hier prüfen
        if (Input::get('view') === 'media') {
            $this->mode = 'media';
        }
    }
     public function generateTree($path, $intMargin, $mount = false, $blnProtected = true, $arrClipboard = null, $arrFound = [])
    {
        $arrFiles = array();

        // Erstelle eine Abfrage, die nur Bilder (z.B. JPEG, PNG, GIF) lädt
        $objFiles = System::getContainer()->get('database_connection')
                ->fetchAllAssociative("
                    SELECT * FROM tl_files
                    WHERE type = 'file' 
                    AND extension = 'jpg' OR extension = 'jpeg' OR extension = 'png' OR extension = 'gif' OR extension ='webp'
                ");

        if ($objFiles !== null)
        {
           $arrFiles = $objFiles;
        }

        // Gebe nur die Bilddateien aus
       // $this->Template->files = $arrFiles;
        
        return $this->renderMediaView($arrFiles);
    }

    protected function renderMediaView($arrFiles = array())
    {
        // Hier deine Bildanzeige wie vorher beschrieben
        // z. B. Galerie mit "edit"-Button pro Bild
        // $objTemplate->src_preview = $imageResizer->resizeAndCacheImage("/isotope/".$isotopeSubFolder."/".$arrImages[0]['src'], 300,null);
        $container = System::getContainer();

		return $container->get('twig')->render(
			'@Contao/media_view.html.twig',
			array(
				'files' => $arrFiles
			)
		);
    }
}
