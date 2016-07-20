<?php

require_once('include/FcmBackgroundComponent.php');

/**
 * Component affichant le fond de la carte
 * 
 * Parameters :
 * - texture-x, texture-y, texture-w, texture-h : géométrie de la base
 * - color : lettre correspondant à la couleur de la base (ou deux lettres pour hybride)
 */

class FcmModernPlaneswalker3TextureComponent extends FcmBackgroundComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        
        // Etape 1 : la texture !
        $base = $this->getBackground('texture', $this->getParameter('texture-color'));
        if(!$base) return;
        
        $x = $this->getFuncard()->xc($this->getParameter('texture-x'));
        $y = $this->getFuncard()->yc($this->getParameter('texture-y'));
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $base, Imagick::COMPOSITE_OVER, $x, $y
        );
        
        // Dernière étape : il faut calculer la couleur la febox
        
        $ret = substr($this->getParameter('texture-color'), -1, 1);
        
        $ret = [
            'edging' => ['edging-color' => $ret]
        ];
        
        return $ret;
        
    }
    
    public function setDefaultParameters(){
        $this->setParameter('texture-x', 42. / 791. * 100);
        $this->setParameter('texture-y', 44. / 1107. * 100);
        $this->setParameter('texture-w', 707. / 791. * 100);
        $this->setParameter('texture-h', 1021. / 1107. * 100);
        $this->setParameter('texture-color', 'r');
    }
    
    public function configure(){ return false; }
}
