<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant la bordure des modern planeswalker3
 *
 * Parameters : 
 * - color : border color
 * Pas d'épaisseur de bordure / rayon des arrondis, la bordure des PW est complexe.
 */

class FcmModernPlaneswalker3BorderComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        self::$draw->push();
        
        // On créé le calque coloré
        $layer = $this->getFuncard()->createLayer($this->getParameter('color'));
        $layer->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
        
        // Puis on le "taille" de la forme de la bordure
        $mask = $this->getFuncard()->loadResource('border', null, 'mask.png');
        
        $layer->compositeImage($mask, Imagick::COMPOSITE_DSTIN, 0, 0);
        
        $this->getFuncard()->getCanvas()->compositeImage($layer, Imagick::COMPOSITE_OVER, 0, 0);
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('color', 'black');
    }
    
    public function configure(){ return false; }
    
}