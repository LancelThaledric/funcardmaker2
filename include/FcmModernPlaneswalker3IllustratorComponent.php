<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant la ligne de l'illustrateur sur des cartes planeswalker
 *
 * Parameters : 
 * - x : position x (center)
 * - y : position y (baseline)
 * - brushsize : font size of brush
 * - color : text color
 * - altcolor : text color for shadow
 * - size : font size in em basesize
 * - text : text to display
 * - font : font name
 */

class FcmModernPlaneswalker3IllustratorComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    const BRUSH_SHINE_OFFSET = -0.09033423667; // -1. / 1107. * 100
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        if(empty($this->getParameter('text'))) return;
        
        self::$draw->push();
        
        // Brush Metrics
        self::$draw->setFont(self::$fontManager->getFont('magicsymbols'));
        self::$draw->setFontSize($this->getFuncard()->fsc($this->getParameter('brushsize')));
        
        $brushmetrics = $this->getFuncard()->getCanvas()->queryFontMetrics(self::$draw, 'il');
        
        // Text Metrics
        self::$draw->setFillColor($this->getParameter('color'));
        self::$draw->setFont(self::$fontManager->getFont($this->getParameter('font')));
        self::$draw->setFontSize($this->getFuncard()->fsc($this->getParameter('size')));
        
        $textmetrics = $this->getFuncard()->getCanvas()->queryFontMetrics(self::$draw, $this->getParameter('text'));
        
        $totalWidth = $brushmetrics['textWidth'] + $textmetrics['textWidth'];
        
        // Draw the text
        self::$draw->setFillColor($this->getParameter('color'));
        self::$draw->setFont(self::$fontManager->getFont($this->getParameter('font')));
        self::$draw->setFontSize($this->getFuncard()->fsc($this->getParameter('size')));
        
        $this->getFuncard()->getCanvas()->annotateImage(
            self::$draw,
            $this->getFuncard()->xc($this->getParameter('x')) - $totalWidth / 2 + $brushmetrics['textWidth'],
            $this->getFuncard()->yc($this->getParameter('y')),
            0,
            $this->getParameter('text')
        );
        
        // Draw the paintbrush
        self::$draw->setFont(self::$fontManager->getFont('magicsymbols'));
        self::$draw->setFontSize($this->getFuncard()->fsc($this->getParameter('brushsize')));
        
        $this->getFuncard()->getCanvas()->annotateImage(
            self::$draw,
            $this->getFuncard()->xc($this->getParameter('x')) - $totalWidth / 2,
            $this->getFuncard()->yc($this->getParameter('y')),
            0,
            'l'
        );
        
        self::$draw->setFillColor($this->getParameter('altcolor'));
        
        $this->getFuncard()->getCanvas()->annotateImage(
            self::$draw,
            $this->getFuncard()->xc($this->getParameter('x')) - $totalWidth / 2,
            $this->getFuncard()->yc($this->getParameter('y')) + $this->getFuncard()->yc(self::BRUSH_SHINE_OFFSET),
            0,
            'i'
        );
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('color', 'black');
        $this->setParameter('altcolor', 'white');
        $this->setParameter('size', 29. / 36.);
        $this->setParameter('text', '');
        $this->setParameter('font', 'matrix');
        $this->setParameter('brushsize', 26. / 36.);
    }
    
    public function configure(){ return false; }
    
}