<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant la boite de F/E et la FE
 * 
 * Parameters :
 * - color : lettre correspondant à la couleur de la boite
 * - textx, texty, textw, texth : géométrie de la boîte de texte. Le texte sera centré.
 * - text-color : couleur utilisée pour le texte
 * - text : le texte à afficher dans FEbox
 * - font : font name
 * - fontsize : font size
 */

class FcmModernBasicFEBoxComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    const STAR_FONT_SIZE_RATIO = 1.0416666666666666666666666666667; // 50 / 48
    const STAR_OFFSET = 0.2; // 10 (px) / 50 (star font size).
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        
        //var_dump($this->getParameter('text'));
        
        if(empty($this->getParameter('text'))) return;
        
        self::$draw->push();
        
        // Draw the box
        
        //var_dump($this->getParameter('color'));

        // Draw the text
        
        $this->drawFEText();
        
        self::$draw->pop();
        
    }
    
    public function setDefaultParameters(){
        $this->setParameter('text', '');
        $this->setParameter('text-color', 'black');
        $this->setParameter('font', 'matrix-smallcaps');
        $this->setParameter('fontsize', 48. / 36.);
        $this->setParameter('color', '');
    }
    
    private function drawFEText(){
        
        // On créé le canvas de texte
        $febox = new Imagick();
        $febox->newImage(
            $this->getFuncard()->xc($this->getParameter('textw')),
            $this->getFuncard()->yc($this->getParameter('texth')),
            'transparent', 'miff'
        );
        
        // On découpe le texte (Hé oui, il faut gérer le cas des fucking étoiles...)
        $fechars = preg_split('#(\*)#', $this->getParameter('text'), 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        //On précalcule les tailles de police (ça allège un peu le parpin de code)
        $numberssize = $this->getFuncard()->fsc($this->getParameter('fontsize'));
        $starssize = $numberssize * self::STAR_FONT_SIZE_RATIO;
        
        // On récupère les métriques des textes des deux différentes fontes
        self::$draw->setFont(self::$fontManager->getFont('matrix-smallcaps'));
        self::$draw->setFontSize($numberssize);
        $numbersMetrics = $febox->queryFontMetrics(self::$draw, '5'); // ça aurait être n'importe quel chiffre, moi j'ai choisi 5.
        self::$draw->setFont(self::$fontManager->getFont('mplantin'));
        self::$draw->setFontSize($starssize);
        $starsMetrics = $febox->queryFontMetrics(self::$draw, '*'); // Pareil, n'importe quel caractère fait l'affaire
        
        // On initialise les curseurs
        $cursor = [
            'x' => 0,
            'y' => $numbersMetrics['characterHeight']
        ];
        $metrics = null;
        
        foreach($fechars as $chars){
            self::$draw->push();
            if($chars == '*'){  // cas chiant de l'étoile
                self::$draw->setFont(self::$fontManager->getFont('mplantin'));
                self::$draw->setFontSize($starssize);
                $febox->annotateImage(self::$draw, $cursor['x'], $cursor['y'] + self::STAR_OFFSET * $starssize, 0, $chars);
                $metrics = $febox->queryFontMetrics(self::$draw, $chars);
                $cursor['x'] += $metrics['textWidth'];
            } else {
                self::$draw->setFont(self::$fontManager->getFont('matrix-smallcaps'));
                self::$draw->setFontSize($numberssize);
                $febox->annotateImage(self::$draw, $cursor['x'], $cursor['y'], 0, $chars);
                $metrics = $febox->queryFontMetrics(self::$draw, $chars);
                $cursor['x'] += $metrics['textWidth'];
            }
            self::$draw->pop();
        }
        
        // A la fin on plaque la febox sur le canvas global
        // On pense à décaler d'autant qu'il faut pour centrer le texte
        /*$this->getCanvas()->compositeImage(
            $febox, Imagick::COMPOSITE_OVER,
            $this->getXCoord($this->FEBOX_BBOX['x']) + ( $this->getXCoord($this->FEBOX_BBOX['w']) - $cursor['x'] ) / 2.,
            $this->getYCoord($this->FEBOX_BBOX['y'])
        );*/
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $febox, Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('textx')) + ( $this->getFuncard()->xc($this->getParameter('textw')) - $cursor['x'] ) / 2.,
            $this->getFuncard()->yc($this->getParameter('texty'))
        );
        
    }
    
    public function configure(){ return false; }
}
