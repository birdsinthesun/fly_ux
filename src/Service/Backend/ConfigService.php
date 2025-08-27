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
            isset($GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['driver'])
            &&$GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['driver']==='fly_ux'
        );
    }
    
     public function getViewSettings($arrSettings):array
    {
       $class = new $GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['callbacks']['view_settings'][0];
        
        return $class->{$GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['callbacks']['view_settings'][1]}($arrSettings);
    
    }
    
    
    public function isParentTable():bool
    {
        $countRelations = count($GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['relations']);
        
        return(
            $this->currentTable === $GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['relations'][$countRelations-2]
            );
    }
     public function getParentTable()
    {
        $countRelations = count($GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['relations']);
        
        return $GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['relations'][$countRelations-2];
    }
    
     public function isContentTable():bool
    {
        $countRelations = count($GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['relations']);
        
        return(
            $this->currentTable === $GLOBALS['BE_FLY_UX'][$this->backendModule]['config']['relations'][$countRelations-1]
            );
    }
    
}