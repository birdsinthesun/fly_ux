<?php

$GLOBALS['TL_DCA']['tl_calendar']['fields']['sorting'] = array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		);
        
    $GLOBALS['TL_DCA']['tl_calendar']['list']['label']['fields'] =  ['title'];
$GLOBALS['TL_DCA']['tl_calendar']['list']['label']['format'] =   '%s ';