<?php

require_once('include/FcmBackgroundLayerComponent.php');

/**
 * Component affichant le fond de la carte
 * 
 * Parameters (same as FcmBackgroundLayerComponent)
 */

class FcmModernPlaneswalker3TextureComponent extends FcmBackgroundLayerComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function setDefaultParameters(){
        parent::setDefaultParameters();
        $this->setParameter('x', 42. / 791. * 100);
        $this->setParameter('y', 44. / 1107. * 100);
        $this->setParameter('w', 707. / 791. * 100);
        $this->setParameter('h', 1021. / 1107. * 100);
        $this->setParameter('type', 'texture');
        $this->setParameter('name', 'r');
    }
    
    public function configure(){ 
        parent::configure();
        // On repaorte la couleur sur le liseré
        $ret = [
            'edging' => ['name' => $this->getParameter('name')]
        ];
        
        return $ret;
    }
}
