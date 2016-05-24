<?php

require_once('include/FcmBackgroundComponent.php');

/**
 * Component affichant un fond de carte uploadé par l'utilisateur
 * 
 * Parameters :
 * - file : url du fichier uploadé
 */

class FcmCustomBackgroundComponent extends FcmBackgroundComponent {
    
    // self::$draw est disponible par héritage
    
    private $_image = null;
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function loadImage(){
        
        try{
            $this->_image = new Imagick(realpath('uploads/'.$this->getParameter('file')));
            $this->getFuncard()->setWidth($this->_image->getImageWidth());
            $this->getFuncard()->setHeight($this->_image->getImageHeight());
            
        } catch (Exception $e) {
            if(DEBUG) echo $e->getMessage();
        }
        
    }
    
    public function apply(){
        
        // On a juste à charger l'image si ce n'est pas déjà fait et à l'appliquer
        if(!$this->_image) $this->loadImage();
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $this->_image, Imagick::COMPOSITE_OVER, 0, 0
        );
    }
    
    public function setDefaultParameters(){
        // Il n'y a pas de paramètre par défaut : le seul paramètre est obligatoire
    }
    
    public function configure(){ return false; }
    
}