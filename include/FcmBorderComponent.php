<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant une ligne de texte (Titre, type, etc.)
 *
 * Parameters : 
 * - color : border color
 * - radius : border radius in border thickness size
 * - thickness : border size
 */

class FcmBorderComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        self::$draw->push();
        
        $thickness = $this->getFuncard()->xc($this->getParameter('thickness'));
        $radius = $thickness * $this->getParameter('radius');
        
        // On dessine un rectangle arrondi
        $layer = $this->getFuncard()->createLayer();
        
        self::$draw->setFillColor($this->getParameter('color'));
        // Ici le -1 sur les dimensions du coin bas-droite est obligatoire,
        // On dirait un bug d'Imagick.
        self::$draw->roundRectangle(
            0, 0, $this->getFuncard()->getWidth()-1, $this->getFuncard()->getHeight()-1, $radius, $radius
        );
        
        $layer->drawImage(self::$draw);
        
        self::$draw->resetVectorGraphics();
        
        // Puis on le "creuse"
        $mask = $this->getFuncard()->createLayer();
        self::$draw->setFillColor('white');
        self::$draw->rectangle(
            $thickness, $thickness,
            $this->getFuncard()->getWidth() - $thickness - 1,
            $this->getFuncard()->getHeight() - $thickness - 1
        );
        $mask->drawImage(self::$draw);
        
        $layer->compositeImage($mask, Imagick::COMPOSITE_DSTOUT, 0, 0);
        
        $this->getFuncard()->getCanvas()->compositeImage($layer, Imagick::COMPOSITE_OVER, 0, 0);
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('color', 'black');
        $this->setParameter('radius', 0.90);
        $this->setParameter('thickness', (41. / 791) * 100);
    }
    
}