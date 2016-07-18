<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant le coût de mana
 *
 * Parameters : 
 * - x : position x (Côté droit)
 * - y : position y (Baseline)
 * - size : font size in em basesize
 * - text : unparsed manas to display
 * - shadowx : x offset of shadow
 * - shadowy : y offset of shadow
 * - largeManaOffset : y offset if there is large manas
 */

class FcmManaCostComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    const EXTERNAL_PADDING = 10;
    
    private $_nugget; // Le Mana Nugget à rendre
    private $_width = 0;
    private $_metrics;
    private $_imagick;
    private $_imagickShadow;
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        if($this->_width == 0) return;
        
        self::$draw->push();
        
        
        $cursor = new FcmTextCursor();
        $cursor->y = $this->_metrics['ascender'] + self::EXTERNAL_PADDING;
        // On calcule le rendu
        $this->_nugget->render($this->_imagick, self::$draw, $cursor, null, $this->_metrics); // 4e paramètre inutile pour du rendu de mana
        
        // Si le CM contient de gros manas, alors il doit être légèrement décalé
        $yoffset = 0;
        if($this->_nugget->hasLargeManas())
            $yoffset += $this->getFuncard()->xc($this->getParameter('largeManaOffset'));
        
        // On calcule et on plaque l'ombre
        if($this->getParameter('shadowx') != 0 && $this->getParameter('shadowx') != 0){
            $this->_imagickShadow = clone $this->_imagick;
            $this->_imagickShadow->thresholdimage(1 * Imagick::getQuantum(), Imagick::CHANNEL_ALL);

            $this->getFuncard()->getCanvas()->compositeImage(
                $this->_imagickShadow, Imagick::COMPOSITE_OVER,
                $this->getFuncard()->xc($this->getParameter('x')) - $this->_width + $this->getFuncard()->xc($this->getParameter('shadowx')),
                $this->getFuncard()->yc($this->getParameter('y')) - self::EXTERNAL_PADDING + $yoffset + $this->getFuncard()->xc($this->getParameter('shadowy'))
            );
        }
        
        // On plaque le rendu sur l'image de la funcard
        $this->getFuncard()->getCanvas()->compositeImage(
            $this->_imagick, Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')) - $this->_width,
            $this->getFuncard()->yc($this->getParameter('y')) - self::EXTERNAL_PADDING + $yoffset
        );
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('size', 48. / 36.);
        $this->setParameter('text', '');
    }
    
    public function configure(){
        self::$draw->setFont(self::$fontManager->getFont('mplantin'));
        self::$draw->setFontSize($this->getFuncard()->fsc($this->getParameter('size')));
        
        // On va utiliser le code des Nuggets de type ManaNugget pour gérer le CM
        $this->_nugget = new FcmManaNugget($this->getParameter('text'));
        
        // Il faut calculer la largeur pour aligner le CM à droite.
        $this->computeWidth();
        if($this->_width == 0) return;
        
        $this->_imagick = new Imagick();
        $this->_imagick->newImage($this->_width, $this->_metrics['characterHeight'] + 2 * self::EXTERNAL_PADDING, 'none', 'miff');
    }
    
    private function computeWidth(){
        $this->_metrics = $this->getFuncard()->getCanvas()->queryFontMetrics(self::$draw, 'x');
        $this->_width = $this->_nugget->getCursorUpdates(null, null, $this->_metrics, null)['x']; // seulement besoin du 3e paramètre pour du mana
        //var_dump($this->_width);
    }
    
}