<?php

require_once('include/FcmBackgroundComponent.php');

/**
 * Component affichant le fond de la carte
 * 
 * Parameters :
 * - edging-x, edging-y, edging-w, edging-h : géométrie du liseré
 * - edging-color : lettre correspondant à la couleur du liseré (ou deux lettres pour hybride)
 */

class FcmModernPlaneswalker3EdgingComponent extends FcmBackgroundComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        
        // Etape 1 : la texture !
        $base = $this->getBackground('edging', $this->getParameter('edging-color'));
        if(!$base) return;
        
        $x = $this->getFuncard()->xc($this->getParameter('edging-x'));
        $y = $this->getFuncard()->yc($this->getParameter('edging-y'));
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $base, Imagick::COMPOSITE_OVER, $x, $y
        );
        
    }
    
    public function setDefaultParameters(){
        $this->setParameter('edging-x', 44. / 791. * 100);
        $this->setParameter('edging-y', 44. / 1107. * 100);
        $this->setParameter('edging-w', 706. / 791. * 100);
        $this->setParameter('edging-h', 988. / 1107. * 100);
        $this->setParameter('edging-color', '');    // Liseré indéterminé
    }
    
    public function configure(){ return false; }
}
