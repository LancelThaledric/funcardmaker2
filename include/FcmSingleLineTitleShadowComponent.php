<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant une ligne de titre. Il sera centré verticalement dans sa boite de texte selon la hauteur du caratère 'T'.
 *
 * Parameters : 
 * From FcmSingleLineTitleComponent :
 * - x, y, w, h : box geometry
 * - marginleft, marginright, margintop, marginbottom : margins to apply to the box, in PX
 * - color : shadow color
 * - size : font size in em basesize
 * - text : text to display
 * - font : font name
 * - align : aligmement : 'left', 'right' or 'center'. Default left.
* New parameters :
 * - blur : blur radius
 * - strokewidth : stroke width
 */

class FcmSingleLineTitleShadowComponent extends FcmSingleLineTitleComponent {
    
    // self::$draw est disponible par héritage
    
    // En dessous de cette taille de police (en pixels) le texte ne sera pas rendu.
    const MIN_COMPUTED_FONT_SIZE = 10;
    private $_totalWidth;
    private $_imagickDummy;
    private $_x, $_y, $_w, $_h;
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
        
        $this->_imagickDummy = new Imagick();
    }
    
    public function apply(){
        //var_dump($this);
        if(empty($this->getParameter('text'))) return;
        
        self::$draw->push();
        
        // Etape 0 : les données
        
        $this->_x = $this->getFuncard()->xc($this->getParameter('x')) + $this->getParameter('marginleft');
        $this->_y = $this->getFuncard()->yc($this->getParameter('y')) + $this->getParameter('margintop');
        $this->_w = $this->getFuncard()->xc($this->getParameter('w')) - $this->getParameter('marginleft') - $this->getParameter('marginright');
        $this->_h = $this->getFuncard()->yc($this->getParameter('h')) - $this->getParameter('margintop') - $this->getParameter('marginbottom');
        
        // Etape 1 la fonte
        
        self::$draw->setFillColor($this->getParameter('color'));
        self::$draw->setFont(self::$fontManager->getFont($this->getParameter('font')));
        self::$draw->setFontSize($this->getFuncard()->fsc($this->getParameter('size')));
        self::$draw->setStrokeWidth($this->getFuncard()->fsc($this->getParameter('strokewidth')));
        self::$draw->setStrokeColor($this->getParameter('color'));
        
        // On calcule la taille du texte optimale
        
        $this->computeOptimalFontSize();
        
        // Etape 2 : On checke la taille du texte
        
        $metrics = $this->_imagickDummy->queryFontMetrics(self::$draw, $this->getParameter('text'));
        
        // Theight est la hauteur de la lettre 'T' majuscule. C'est à partir de cette mesure que l'on va centrer le texte verticalement
        $metrics['Theight'] = $metrics['ascender'] + $metrics['descender'];
        
        $relativey = ($this->_h + $metrics['Theight']) / 2.;
        
        // Etape 3 : Alignement
        $align = $this->getParameter('align');
        if($align != 'left' && $align != 'right' && $align != 'center')
            $align = 'left';
        if($align == 'right')
            self::$draw->setTextAlignment(imagick::ALIGN_RIGHT);
        elseif($align == 'center')
            self::$draw->setTextAlignment(imagick::ALIGN_CENTER);
        
        // Etape 4 : Dessin
        
        $layer = $this->getFuncard()->createLayer();
        
        $layer->annotateImage(
            self::$draw,
            $this->_x,
            $this->_y + (int) $relativey,
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
        $this->setParameter('valign', 'middle');
        $this->setParameter('marginleft', '0');
        $this->setParameter('marginright', '0');
        $this->setParameter('margintop', '0');
        $this->setParameter('marginbottom', '0');
    }
    
    public function configure(){ return false; }
    
    private function computeOptimalFontSize(){
        $ok = false;
        $fontsize = $this->getParameter('size');
        $computed_font_size = $this->getFuncard()->fsc($fontsize);
        $expectedWidth = $this->_w;
        while(!$ok){
            //var_dump($computed_font_size);
            if($computed_font_size < self::MIN_COMPUTED_FONT_SIZE){    // En deça de cette valeur le etxte est illisible
                $ok = true;
                break;
            }
            
            self::$draw->setFontSize($computed_font_size);
            
            // On calcule la largeur totale
            $this->_totalWidth = $this->_imagickDummy->queryFontMetrics(self::$draw, $this->getParameter('text'))['textWidth'];
            //var_dump('total height = '.$totalheight . ', ' . $expectedHeight . ' expected');
            
            // On regarde si on a pas dépassé
            if($this->_totalWidth > $expectedWidth){
                $ok = false;
                // Pour calculer la nouvelle valeur de fontsize, on calcule le ratio hauteur obtenue/hauteur espérée.
                // On rapproche ce ratio de 1 pour prendre en compte qu'on gagnera aussi de la place en largeur
                // On multiplie par ce ratio.
                // C'est une méthode logarithmique plus rapide que la méthode linéaire (en décrémentant petit à petit).
                // ... Mais peut-être moins précise, à vérifier.
                $ratio = $expectedWidth / $this->_totalWidth;
                //var_dump('ratio : '.$ratio.', multiply by '.(1+$ratio) / 2.);
                $computed_font_size = (int) ($computed_font_size * (1+$ratio) / 2. );
                /*$computed_font_size--;*/
            } else {
                /* tout fonctionne */ $ok = true;
            }
        }
    }
    
}