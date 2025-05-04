<?php
use Bits\FlyUxBundle\Driver\DC_Content;
use Contao\DataContainer;

$this->loadDataContainer('tl_page');

    $GLOBALS['TL_DCA']['tl_content']['config']['sql']['keys']['parentTable'] = 'index';

    $GLOBALS['TL_DCA']['tl_content']['fields']['parentTable'] = array
            (
                'sql'                     => "varchar(64) COLLATE ascii_bin NOT NULL default 'tl_page'"
            );
    $GLOBALS['TL_DCA']['tl_content']['fields']['ptable'] = array
            (
                'sql'                     => "varchar(64) COLLATE ascii_bin NOT NULL default ''"
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
     $GLOBALS['TL_DCA']['tl_content']['fields']['el_css_class'] = array
            (
                'inputType'               => 'text',
                'sql'                     => "varchar(128) NOT NULL default ''"
            );
    
   
   

    $GLOBALS['TL_DCA']['tl_content']['palettes']['contentslider']   = '{type_legend},type,headline;{plus_legend},el_count,el_css_class;{slider_legend},sliderDelay,sliderSpeed,sliderStartSlide,sliderContinuous;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
    $GLOBALS['TL_DCA']['tl_content']['palettes']['contentgrid']   = '{type_legend},type,headline;{plus_legend},el_count,el_css_class;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';

                     
        
	
 

