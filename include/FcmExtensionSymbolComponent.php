<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant le symbole d'édition
 *
 * Parameters : 
 * - x : position x (côté droit)
 * - y : position y (côté haut)
 * - h : hauteur du SE
 * - name : ID of the SE like "group/extension" (not for custom SE)
 * - rarity : 'c', 'u', 'r', 'm' ou 's' of the official SE. (not for custom SE)
 * - file : if custom SE, url of the image
 */

class FcmExtensionSymbolComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    private $_imagick = null; // Image loaded of the SE
    private $_custom = '';
    
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        if($this->_imagick === null || $this->_imagick->getNumberImages() == 0) return;
        
        self::$draw->push();
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $this->_imagick, Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')) - $this->_imagick->getImageWidth(),
            $this->getFuncard()->yc($this->getParameter('y'))
        );
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('rarity', 'c');
        $this->setParameter('name', '');
        $this->setParameter('file', '');
    }
    
    public function configure(){
        // Le but, c'est de charger la bonne image (y compris pour SE custom)
        $extension = $this->getParameter('name');
        $rarity = $this->getParameter('rarity');
        $this->_custom = $this->getParameter('file');
        if(empty($extension) && empty($this->_custom)) return;
        
        if($this->isCustom()){
            $this->loadCustomSE($this->_custom);
        } else {
            $this->loadOfficialSE($extension, $rarity);
        }
        $this->resizeSE();
        
        return [
            'type' => [
                'marginright' => $this->_imagick->getImageWidth()
            ],
            'typeshadow' => [
                'marginright' => $this->_imagick->getImageWidth()
            ]
        ];
        
    }
    
    private function loadOfficialSE($extension, $rarity){
        $filename = $extension . '-' . $rarity . '.png';
        $filepath = realpath('resource/se/'.$filename);
        $this->_imagick = new Imagick(realpath($filepath));
    }
    
    private function loadCustomSE($filename){
        $filepath = realpath('uploads/'.$filename);
        $this->_imagick = new Imagick(realpath($filepath));
    }
    
    private function resizeSE(){
        $this->_imagick->resizeImage(
            0,
            $this->getFuncard()->yc($this->getParameter('h')),
            Imagick::FILTER_TRIANGLE,
            1
        );
    }
    
    private function isCustom(){
        return !empty($this->_custom);
    }
    
}