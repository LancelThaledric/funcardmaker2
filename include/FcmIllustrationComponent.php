<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant l'illustration
 *
 * Parameters : 
 * - x : illusbox position x (top left)
 * - y : illusbox position y (top left)
 * - w : illusbox width
 * - h : illusbox height
 * - file : illustration filename   // Compulsory
 * - crop-x, crop-y, crop-w, crop-h : Crop geometry in percent
 */

class FcmIllustrationComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    protected $_illus;
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
        
        $this->_illus = null;
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
        
        // On resize
        $this->_illus->resizeImage(
            $this->getFuncard()->xc($this->getParameter('w')),
            $this->getFuncard()->yc($this->getParameter('h')),
            Imagick::FILTER_TRIANGLE, 1, false
        );
        
        // On insère !
        $this->getFuncard()->getCanvas()->compositeImage(
            $this->_illus, Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')),
            $this->getFuncard()->yc($this->getParameter('y'))
        );
        
    }
    
    //* Retourne l'axe déterminant de l'illustration (celui qui ne doit pas être croppé)
    //* true = X, false = Y
    public function getIllusDeterminantAxis(){
        // On détermine l'axe déterminant
        $iw = $this->_illus->getImageWidth();
        $ih = $this->_illus->getImageHeight();
        $iratio = $iw / $ih;
        $ratio = $this->getFuncard()->xc($this->getParameter('w'))
               / $this->getFuncard()->yc($this->getParameter('h'));
        if($iratio > $ratio) return false;
        return true;
    }
    
    public function hasCrop(){
        return $this->hasParameter('crop-x')
            && $this->hasParameter('crop-y')
            && $this->hasParameter('crop-w')
            && $this->hasParameter('crop-h');
    }
    
    public function customCrop(){
        $iw = $this->_illus->getImageWidth();
        $ih = $this->_illus->getImageHeight();
        $px = $this->getParameter('crop-x') / 100 * $iw;
        $py = $this->getParameter('crop-y') / 100 * $ih;
        $pw = $this->getParameter('crop-w') / 100 * $iw;
        $ph = $this->getParameter('crop-h') / 100 * $ih;
        
        $this->_illus->cropImage($pw, $ph, $px, $py);
    }
    
    public function autoCrop(){
        $ratio = $this->getFuncard()->xc($this->getParameter('w'))
               / $this->getFuncard()->yc($this->getParameter('h'));
        $axis = $this->getIllusDeterminantAxis();
        $iw = $this->_illus->getImageWidth();
        $ih = $this->_illus->getImageHeight();
        $px = $py = $pw = $ph = 0;
        
        if($axis){  // format portrait par rapport à l'illusbox
            $pw = $iw;
            $ph = $iw / $ratio;
            $px = 0;
            $py = ($ih - $ph) / 2;
        } else {    // format paysage par rapport à l'illusbox
            $pw = $ih * $ratio;
            $ph = $ih;
            $px = ($iw - $pw) / 2;
            $py = 0;
        }
        
        $this->_illus->cropImage($pw, $ph, $px, $py);
        
    }
    
    public function setDefaultParameters(){
        $this->setParameter('x', 70. / 791. * 100);
        $this->setParameter('y', 133. / 1107. * 100);
        $this->setParameter('w', 651. / 791. * 100);
        $this->setParameter('h', 480. / 1107. * 100);
    }
    
    public function configure(){ return false; }
    
}