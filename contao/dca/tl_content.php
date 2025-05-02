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
    $GLOBALS['TL_DCA']['tl_content']['config']['dataContainer'] = DC_Content::class;
    $GLOBALS['TL_DCA']['tl_content']['list']['sorting']['mode'] = DataContainer::MODE_TREE_EXTENDED;
    $GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['tl_page','addBreadcrumb'];
    $GLOBALS['TL_DCA']['tl_content']['config']['ctable'] = ['tl_content'];
    $GLOBALS['TL_DCA']['tl_content']['config']['enableVersioning']            = true;
    $GLOBALS['TL_DCA']['tl_content']['config']['markAsCopy']                  = 'headline';

  
   $GLOBALS['TL_DCA']['tl_content']['list']['label']['fields'] =  ['headline', 'type', 'inColumn'];
   $GLOBALS['TL_DCA']['tl_content']['list']['label']['format'] =   '%s <span class="label-info">[%s]</span><span class="label-column"> %s </span>';


    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['fields']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['panelLayout']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['defaultSearchField']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['headerFields']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['sorting']['child_record_callback']);

    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['header_new']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['new']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['toggleNodes']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['all']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['showOnSelect']);
    unset($GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['create']);
       
      
   
   

    $GLOBALS['TL_DCA']['tl_content']['palettes']['contentslider']   = '{type_legend},type,headline,el_count;{slider_legend},sliderDelay,sliderSpeed,sliderStartSlide,sliderContinuous;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';

                     
        
	
 

