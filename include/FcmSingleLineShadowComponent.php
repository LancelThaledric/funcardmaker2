<?php

require_once('include/FcmSingleLineComponent.php');

/**
 * Component affichant une ligne de texte (Titre, type, etc.)
 *
 * Parameters : 
 * From FcmSingleLineComponent :
 * - x : position x
 * - y : position y
 * - color : shadow color
 * - size : font size in em basesize
 * - text : text to display
 * - font : font name
 * - align : txt alignment : right, left or center
 * New parameters :
 * - blur : blur radius
 * - strokewidth : stroke width
 */

class FcmSingleLineShadowComponent extends FcmSingleLineComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        if(empty($this->getParameter('text'))) return;
        
        self::$draw->push();
        
        self::$draw->setFillColor($this->getParameter('color'));
        self::$draw->setFont(self::$fontManager->getFont($this->getParameter('font')));
        self::$draw->setFontSize($this->getFuncard()->fsc($this->getParameter('size')));
        self::$draw->setStrokeWidth($this->getFuncard()->fsc($this->getParameter('strokewidth')));
        self::$draw->setStrokeColor($this->getParameter('color'));
        
        $align = $this->getParameter('align');
        if($align != 'left' && $align != 'right' && $align != 'center')
            $align = 'left';
        if($align == 'right')
            self::$draw->setTextAlignment(imagick::ALIGN_RIGHT);
        elseif($align == 'center')
            self::$draw->setTextAlignment(imagick::ALIGN_CENTER);
        
        $layer = $this->getFuncard()->createLayer();
        
        $layer->annotateImage(
            self::$draw,
            $this->getFuncard()->xc($this->getParameter('x')),
            $this->getFuncard()->yc($this->getParameter('y')),
            0,
            $this->getParameter('text')
        );
        
        $layer->blurImage($this->getFuncard()->fsc($this->getParameter('blur')), $this->getFuncard()->fsc($this->getParameter('blur')));
        
        $this->getFuncard()->getCanvas()->compositeImage($layer, Imagick::COMPOSITE_OVER, 0, 0);
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('color', 'black');
        $this->setParameter('size', 1);
        $this->setParameter('blur', 0);
        $this->setParameter('strokewidth', 0);
        $this->setParameter('text', '');
        $this->setParameter('font', 'matrix');
        $this->setParameter('align', 'left');
    }
    
    public function configure(){ return false; }
    
}