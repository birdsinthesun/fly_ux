<?php

namespace Bits\FlyUxBundle\Backend;

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
            isset($GLOBALS['BE_MOD']['content'][$this->BackendModule]['driver'])
            &&$GLOBALS['BE_MOD']['content'][$this->BackendModule]['driver']==='fly_ux'
            &&isset($GLOBALS['BE_MOD']['content'][$this->BackendModule]['init'])
        );
    }
    
    public function isParentTable():bool
    {
        $countRelations = count($GLOBALS['BE_MOD']['content'][$this->BackendModule]['relations']);
        return(
            $this->currentTable === $GLOBALS['BE_MOD']['content'][$this->BackendModule]['relations'][$countRelations-1]
            );
    }
    
}