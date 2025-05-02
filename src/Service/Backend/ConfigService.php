<?php

namespace Bits\FlyUxBundle\Service\Backend;

use Contao\Input;

class ConfigService
{
    private $backendModule;
    
    private $currentTable;
    
    public function __construct() {
        $this->backendModule = Input::get('do');
        $this->currentTable = Input::get('table');
        }
    
    public function useflyUxDriver():bool
    {
        return(
            isset($GLOBALS['BE_MOD']['content'][$this->BackendModule]['config']['driver'])
            &&$GLOBALS['BE_MOD']['content'][$this->BackendModule]['config']['driver']==='fly_ux'
            &&isset($GLOBALS['BE_MOD']['content'][$this->BackendModule]['init'])
        );
    }
    
    public function isParentTable():bool
    {
        $countRelations = count($GLOBALS['BE_MOD']['content'][$this->BackendModule]['config']['relations']);
        
        return(
            $this->currentTable === $GLOBALS['BE_MOD']['content'][$this->BackendModule]['config']['relations'][$countRelations-2]
            );
    }
    
     public function isContentTable():bool
    {
        $countRelations = count($GLOBALS['BE_MOD']['content'][$this->BackendModule]['config']['relations']);
        
        return(
            $this->currentTable === $GLOBALS['BE_MOD']['content'][$this->BackendModule]['relations'][$countRelations-1]
            );
    }
    
}