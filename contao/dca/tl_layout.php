<?php

$GLOBALS['TL_DCA']['tl_layout']['fields']['modules']['inputType']    = 'flyWizard';


$GLOBALS['TL_DCA']['tl_layout']['fields']['be_grid'] = array
		(
			'inputType'               => 'fileTree',
			'eval'                    => array('multiple'=>false, 'fieldType'=>'radio', 'filesOnly'=>true, 'extensions'=>'css,scss,less', 'isSortable'=>false),
			'sql'                     => "blob NULL"
		);
        
$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_layout']['palettes']['default'].';{backend_legend:hide},be_grid';