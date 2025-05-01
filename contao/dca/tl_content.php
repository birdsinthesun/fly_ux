<?php


$GLOBALS['TL_DCA']['tl_content']['config']['sql']['keys']['parentTable'] = 'index';

$GLOBALS['TL_DCA']['tl_content']['fields']['parentTable'] = array
		(
			'sql'                     => "varchar(64) COLLATE ascii_bin NOT NULL default 'tl_page'"
		);
$GLOBALS['TL_DCA']['tl_content']['fields']['ptable'] = array
		(
			'sql'                     => "varchar(64) COLLATE ascii_bin NOT NULL default 'tl_page'"
		);
$GLOBALS['TL_DCA']['tl_content']['fields']['inColumn'] = array
		(
			'filter'                  => true,
			'inputType'               => 'select',
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['COLS'],
			'sql'                     => "varchar(128) NOT NULL default ''"
		);
$GLOBALS['TL_DCA']['tl_content']['fields']['el_count'] = array
		(
			'inputType'               => 'text',
            'sql'                     => "int(10) unsigned NOT NULL default 1"
		);
        
	
 

