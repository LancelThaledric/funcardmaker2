<?php

require_once('include/FcmBackgroundLayerComponent.php');

/**
 * Component affichant le fond de la carte
 * 
 * Parameters (same as FcmBackgroundLayerComponent)
 * - visible (default true)
 */

class FcmModernBasicBackgroundFeboxComponent extends FcmBackgroundLayerComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function setDefaultParameters(){
        parent::setDefaultParameters();
        $this->setParameter('x', 570. / 791. * 100);
        $this->setParameter('y', 973. / 1107. * 100);
        $this->setParameter('w', 173. / 791. * 100);
        $this->setParameter('h', 93. / 1107. * 100);
        $this->setParameter('type', 'febox');
        $this->setParameter('name', 'r');
        $this->setParameter('visible', true);
    }
    
    public function configure(){ 
        if($this->getParameter('visible') == false) return;
        parent::configure();
    }
    
    public function apply(){ 
        if($this->getParameter('visible') == false) return;
        parent::apply();
    }
}
