<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Classe gérant le chargement et l'affichage d'une ressource.
 *
 * Parameters
 * - x, y, w, h : bounding box of the image destination
 * - category : ressource category.
 * - type : folder name of the resource in the template resource folder.
 * - name : name of the resource, without extension.
 * - extension : extension of the resource. Default is "png". (Keep png, please.)
 */

class FcmImageComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    private $_templateName;
    
    //* Resource chargée
    private $_resource;
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
        
        $this->_templateName = $this->getFuncard()->getTemplateName();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('extension', 'png');
        $this->setParameter('type', '');
    }
    
    public function configure(){ 
        
        if(!$this->hasParameter('name')) return;
        
        // on charge la ressource
        $sizex = $this->getFuncard()->xc($this->getParameter('w'));
        $sizey = $this->getFuncard()->yc($this->getParameter('h'));
        
        $this->_resource = $this->getFuncard()->loadResource(
            $this->getParameter('category'),
            $this->getParameter('type'),
            $this->getParameter('name') . '.' . $this->getParameter('extension'),
            $sizex, $sizey
        );
    }
    
    public function apply(){
        
        // On applique seulement si on a une ressource à appliquer
        if(!isset($this->_resource)) return;
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $this->_resource,
            Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')),
            $this->getFuncard()->yc($this->getParameter('y'))
        );
        
    }
    
}