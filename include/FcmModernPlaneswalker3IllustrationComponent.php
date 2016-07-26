<?php

require_once('include/FcmIllustrationComponent.php');

/**
 * Component affichant l'illustration
 *
 * Parameters (same as FcmIllustrationComponent) : 
 * - x : illusbox position x (top left)
 * - y : illusbox position y (top left)
 * - w : illusbox width
 * - h : illusbox height
 * - file : illustration filename   // Compulsory
 * - crop-x, crop-y, crop-w, crop-h : Crop geometry in percent
 */

class FcmModernPlaneswalker3IllustrationComponent extends FcmIllustrationComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        if(!$this->hasParameter('file')) return;
        $filename = $this->getParameter('file');
        
        //var_dump($filename);
        
        // On charge le fichier
        $filepath = realpath('uploads/'.$filename);
        $this->_illus = new Imagick($filepath);
        
        // On crope
        if($this->hasCrop()){
            // Crop personnalisé
            $this->customCrop();
        } else {
            // Crop automatique
            $this->autoCrop();
        }
        
        $w = $this->getFuncard()->xc($this->getParameter('w'));
        $h = $this->getFuncard()->yc($this->getParameter('h'));
        
        // On resize
        $this->_illus->resizeImage(
            $w,
            $h,
            Imagick::FILTER_TRIANGLE, 1, false
        );
        
        // On masque !
        
        $this->_illus->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
        $mask = $this->getFuncard()->loadResource(
            'background', null, 'illus-mask.png', $w, $h
        );
        $this->_illus->compositeImage(
            $mask, Imagick::COMPOSITE_DSTIN, 0, 0
        );
        
        // On insère !
        $this->getFuncard()->getCanvas()->compositeImage(
            $this->_illus, Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')),
            $this->getFuncard()->yc($this->getParameter('y'))
        );
        
    }
}