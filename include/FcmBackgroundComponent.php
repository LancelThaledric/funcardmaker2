<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Classe abstraite qui gère la génération/chargement de fonds de carte
 * Les paramètres sont définis pour chaque classe fille
 */

abstract class FcmBackgroundComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        ////// NOTHING !!!
        // (Voir les classes filles pour ça)
    }
    
    //* Retourne l'image de fond chargée depuis les ressources
    public function loadBackground($name, $color){
        
        $template = $this->getFuncard()->getTemplateName();
        $filepath = 'resource/background/' . $template . '/' . $name . '/' . $color . '.png';
        $background = new Imagick(realpath($filepath));
        $this->resizeBackground($background, $name);
        return $background;
        
    }
    
    //* Retourne l'image de fond hybride générée
    public function computeHybrid($name, $colors){
        
        // Etape 1 : on prépare le masque de fusion
        
        $template = $this->getFuncard()->getTemplateName();
        $mask = new Imagick(realpath('resource/background/' . $template . '/hybrid-mask.png'));
        $this->resizeBackground($mask, $name);
        
        // Etape 2 : On charge les deux fonds
        
        $left = $this->loadBackground($name, $colors[0]);
        $right = $this->loadBackground($name, $colors[1]);
        
        // Etape 3 : On rend le fond de gauche transparent
        
        $left->compositeImage(
            $mask, Imagick::COMPOSITE_DSTIN, 0, 0
        );
        
        $right->compositeImage(
            $left, Imagick::COMPOSITE_OVER, 0, 0
        );
        
        return $right;
        
    }
    
    //* Retourne le fond chargé en fonction des letters
    public function getBackground($name, $colors){
        // On n'est valide que si on a une lettre ou deux
        $nbColors = strlen($colors);
        if($nbColors < 1 || $nbColors > 2) return null;
        
        $background = null;
        
        // Si on a une seule couleur
        if($nbColors == 1) $background = $this->loadBackground($name, $colors);
        
        // Sinon on est hybride
        else $background = $this->computeHybrid($name, $colors);
        
        if(!$background) return null;
        
        return $background;
    }
    
    //* Redimenssionne le background à la bonne taille si nécessaire
    //* ATTENTION le fichier à redimmensionner doit avoir le même ratio sinon ce sera MEGA MOCHE
    public function resizeBackground($background, $name){
        $imagewidth = $background->getImageWidth();
        $imageheight = $background->getImageHeight();
        
        $bgwidth = $this->getFuncard()->xc($this->getParameter($name.'-w'));
        $bgheight = $this->getFuncard()->yc($this->getParameter($name.'-h'));
        
        if($imagewidth != $bgwidth || $imageheight != $bgheight){
            $background->resizeImage($bgwidth, $bgheight, Imagick::FILTER_TRIANGLE, 1, false);
        }
    }
    
    public function setDefaultParameters(){
        // NOTHING !!!
        // Voir les classes filles
    }
    
}