<?php

require_once('include/FcmBackgroundLayerComponent.php');

/**
 * Component affichant le fond de la carte
 * 
 * Parameters (same as FcmBackgroundLayerComponent)
 */

class FcmOldBasicBackgroundBaseComponent extends FcmBackgroundLayerComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function setDefaultParameters(){
        parent::setDefaultParameters();
        $this->setParameter('x', 41. / 787. * 100);
        $this->setParameter('y', 41. / 1087. * 100);
        $this->setParameter('w', 705. / 787. * 100);
        $this->setParameter('h', 1005. / 1087. * 100);
        $this->setParameter('type', 'base');
        $this->setParameter('name', 'r');
    }
    
    public function configure(){ 
        parent::configure();
        
        $ret = [];
        
        if($this->isWhiteBackground()){
            
            $ret['copyright'] = [
                'color' => 'black'
            ];
        }
        
        return $ret;
    }
    
    public function isWhiteBackground(){
        $firstcolor = $this->getColorArray()[0];
        return ($firstcolor == 'w');
    }
}
