<?php

require_once('include/FcmBackgroundComponent.php');

/**
 * Component affichant le fond de la carte
 * 
 * Parameters :
 * - base-x, base-y, base-w, base-h : géométrie de la base
 * - base-color : lettre correspondant à la couleur de la base (ou deux lettres pour hybride)
 * - edging-x, edging-y, edging-w, edging-h : géométrie du liseré
 * - edging-color : lettre correspondant à la couleur du liseré (ou deux lettres pour hybride)
 * - titlebox-x, titlebox-y, titlebox-w, titlebox-h : géométrie de boite de titre
 * - typebox-x, typebox-y, typebox-w, typebox-h : géométrie de la boite de type
 * - box-color : lettre correspondant à la couleur des boites (ou deux lettres pour hybride)
 */

class FcmModernBasicBackgroundComponent extends FcmBackgroundComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        
        // Etape 1 : la base !
        $base = $this->getBackground('base', $this->getParameter('base-color'));
        if(!$base) return;
        
        $x = $this->getFuncard()->xc($this->getParameter('base-x'));
        $y = $this->getFuncard()->yc($this->getParameter('base-y'));
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $base, Imagick::COMPOSITE_OVER, $x, $y
        );
        
        // Etape 2 : le liseré !
        $edging = $this->getBackground('edging', $this->getParameter('edging-color'));
        if($edging){
            $x = $this->getFuncard()->xc($this->getParameter('edging-x'));
            $y = $this->getFuncard()->yc($this->getParameter('edging-y'));
            
            $this->getFuncard()->getCanvas()->compositeImage(
                $edging, Imagick::COMPOSITE_OVER, $x, $y
            );
            
        }
        
        // Etape 3 : Les box !
        $titlebox = $this->getBackground('titlebox', $this->getParameter('box-color'));
        if($titlebox){
            $x = $this->getFuncard()->xc($this->getParameter('titlebox-x'));
            $y = $this->getFuncard()->yc($this->getParameter('titlebox-y'));
            
            $this->getFuncard()->getCanvas()->compositeImage(
                $titlebox, Imagick::COMPOSITE_OVER, $x, $y
            );
            
        }
        
        $typebox = $this->getBackground('typebox', $this->getParameter('box-color'));
        if($typebox){
            
            $x = $this->getFuncard()->xc($this->getParameter('typebox-x'));
            $y = $this->getFuncard()->yc($this->getParameter('typebox-y'));
            
            $this->getFuncard()->getCanvas()->compositeImage(
                $typebox, Imagick::COMPOSITE_OVER, $x, $y
            );
            
        }
        
        // Dernière étape : il faut calculer la couleur la febox
        
        $ret = '';
        if($titlebox) $ret = substr($this->getParameter('box-color'), -1, 1);
        elseif($edging) $ret = substr($this->getParameter('edging-color'), -1, 1);
        else $ret = substr($this->getParameter('base-color'), -1, 1);
        
        $ret = [
            'fe' => ['febox-color' => $ret],
        ];
        
        // Re-dernière étape, il faut calculer la couleur de l'illustrateur
        
        $base_letter = $this->getParameter('base-color');
        if($base_letter == 'b' || $base_letter == 'l'){
            $ret['illus'] = [
                'color' => 'white',
                'altcolor' => 'black'
            ];
        }
        
        return $ret;
        
    }
    
    public function setDefaultParameters(){
        $this->setParameter('base-x', 41. / 791. * 100);
        $this->setParameter('base-y', 41. / 1107. * 100);
        $this->setParameter('base-w', 709. / 791. * 100);
        $this->setParameter('base-h', 1025. / 1107. * 100);
        $this->setParameter('base-color', 'r');
        
        $this->setParameter('edging-x', 50. / 791. * 100);
        $this->setParameter('edging-y', 55. / 1107. * 100);
        $this->setParameter('edging-w', 691. / 791. * 100);
        $this->setParameter('edging-h', 946. / 1107. * 100);
        $this->setParameter('edging-color', '');
        
        $this->setParameter('titlebox-x', 56. / 791. * 100);
        $this->setParameter('titlebox-y', 61. / 1107. * 100);
        $this->setParameter('titlebox-w', 679. / 791. * 100);
        $this->setParameter('titlebox-h', 64. / 1107. * 100);
        $this->setParameter('typebox-x', 60. / 791. * 100);
        $this->setParameter('typebox-y', 621. / 1107. * 100);
        $this->setParameter('typebox-w', 671. / 791. * 100);
        $this->setParameter('typebox-h', 62. / 1107. * 100);
        $this->setParameter('box-color', '');
    }
    
    public function configure(){ return false; }
}
