<?php
/**
 * Gestionnaire de polices d'écritures
 */

class FcmFontManager{
    
    //* tableau de fontes chargées
    private $_fonts;
    
    //* Constructeur
    public function __construct(){
        $this->_fonts = [];
    }
    
    //* Getter de fontes
    public function getFont($name){
        if($this->hasFont($name)) return $this->_fonts[$name];
        return null;
    }
    
    //* Font loader
    public function setFont($name, $path, $replace = false){
        if($replace || !$this->hasFont($name))
            $this->_fonts[$name] = $path;
    }
    
    //* Check font exists
    public function hasFont($name){
        return isset($this->_fonts[$name]);
    }
    
}