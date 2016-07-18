<?php

require_once('include/FcmBackgroundComponent.php');

/**
 * Component affichant le fond de la carte
 * 
 * Parameters :
 * - base-x, base-y, base-w, base-h : géométrie de la base
 * - base-color : lettre correspondant à la couleur de la base (ou deux lettres pour hybride)
 * - hybrid-method : méthode d'hybridation. Correspond au nom du masque.
 */

class FcmOldBasicBackgroundComponent extends FcmBackgroundComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        
        // Etape 1 : la base !
        $base = $this->getBackground2('base', $this->getParameter('base-color'));
        if(!$base) return;
        
        $x = $this->getFuncard()->xc($this->getParameter('base-x'));
        $y = $this->getFuncard()->yc($this->getParameter('base-y'));
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $base, Imagick::COMPOSITE_OVER, $x, $y
        );
        
        // Etape 2 : la capabox !
        $capabox = $this->getBackground2('capabox', $this->getParameter('capabox-color'));

        if($capabox){
            $x = $this->getFuncard()->xc($this->getParameter('capabox-x'));
            $y = $this->getFuncard()->yc($this->getParameter('capabox-y'));

            $this->getFuncard()->getCanvas()->compositeImage(
                $capabox, Imagick::COMPOSITE_OVER, $x, $y
            );
        }
        
        // Dernière étape, il faut calculer la couleur de l'illustrateur
        
        $ret = [];
        
        $base_letter = $this->getParameter('base-color')[0];
        if($base_letter == 'w'){
            $ret['copyright'] = [
                'color' => 'black',
            ];
        }
        
        return $ret;
        
    }
    
    public function setDefaultParameters(){
        $this->setParameter('base-x', 41. / 787. * 100);
        $this->setParameter('base-y', 41. / 1087. * 100);
        $this->setParameter('base-w', 705. / 787. * 100);
        $this->setParameter('base-h', 1005. / 1087. * 100);
        $this->setParameter('base-color', 'r');
        $this->setParameter('capabox-x', 71. / 787. * 100);
        $this->setParameter('capabox-y', 640. / 1087. * 100);
        $this->setParameter('capabox-w', 646. / 787. * 100);
        $this->setParameter('capabox-h', 336. / 1087. * 100);
        $this->setParameter('capabox-color', '');
    }
    
    public function configure(){ return false; }
}
