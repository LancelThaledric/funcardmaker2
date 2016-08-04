<?php

require_once('include/FcmBackgroundLayerComponent.php');

/**
 * Component affichant le fond de la carte
 * 
 * Parameters (same as FcmBackgroundLayerComponent)
 */

class FcmModernBasicBackgroundBaseComponent extends FcmBackgroundLayerComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function setDefaultParameters(){
        parent::setDefaultParameters();
        $this->setParameter('x', 41. / 791. * 100);
        $this->setParameter('y', 41. / 1107. * 100);
        $this->setParameter('w', 709. / 791. * 100);
        $this->setParameter('h', 1025. / 1107. * 100);
        $this->setParameter('type', 'base');
        $this->setParameter('name', 'r');
    }
    
    public function configure(){ 
        parent::configure();
        
        $ret = [];
        
        if($this->isDarkBackground()){
            
            $ret['illus'] = [
                'color' => 'white',
                'altcolor' => 'black'
            ];
            $ret['copyright'] = [
                'color' => 'white'
            ];
        }
        
        return $ret;
    }
    
    public function isDarkBackground(){
        $firstcolor = $this->getColorArray()[0];
        return ($firstcolor == 'b' || $firstcolor == 'l');
    }
}
