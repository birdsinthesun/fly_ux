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
            isset($GLOBALS['BE_FLY_UX']['content'][$this->backendModule]['config']['driver'])
            &&$GLOBALS['BE_FLY_UX']['content'][$this->backendModule]['config']['driver']==='fly_ux'
            &&isset($GLOBALS['BE_FLY_UX']['content'][$this->backendModule]['init'])
        );
    }
    
    public function isParentTable():bool
    {
        $countRelations = count($GLOBALS['BE_FLY_UX']['content'][$this->backendModule]['config']['relations']);
        
        return(
            $this->currentTable === $GLOBALS['BE_FLY_UX']['content'][$this->backendModule]['config']['relations'][$countRelations-2]
            );
    }
    
     public function isContentTable():bool
    {
        $countRelations = count($GLOBALS['BE_FLY_UX']['content'][$this->backendModule]['config']['relations']);
        
        return(
            $this->currentTable === $GLOBALS['BE_FLY_UX']['content'][$this->backendModule]['config']['relations'][$countRelations-1]
            );
    }
    
}