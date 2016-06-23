<?php

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmReady2PrintText.php');

/**
 * Component affichant plusieurs lignes de texte, éventuellement avec du mana dedans
 *
 * Parameters : 
 * - x : position x
 * - y : position y
 * - w : width of the box
 * - h : height of the box
 * - fontsize : font size in em basesize
 * - text : text to display
 * - font : font to use for text
 */

class FcmMultiLineComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    private $_nuggets = null;
    private $_ready2PrintText = null;
    
    public function getNuggets() { return $this->_nuggets; }
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        self::$draw->push();
        
        $this->_ready2PrintText->render();
        $capabox_rendered_text = $this->_ready2PrintText->getRendered();
            
        // On plaque le rendu sur l'image de la funcard
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $capabox_rendered_text, Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')),
            $this->getFuncard()->yc($this->getParameter('y'))
        );
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        
        $this->setParameter('fontsize', 1);
        $this->setParameter('text', '');
        $this->setParameter('font', 'mplantin');
        
    }
    
    public function configure(){
        
        // On sépare les lignes entre elles
        $this->_nuggets = self::text2Nuggets($this->getParameter('text'));
        
        return true;
    
    }
    
    public function addNugget($nugget){
        $this->_nuggets[] = $nugget;
    }
    
    public function addNuggets($nuggets){
        $this->_nuggets = array_merge($this->_nuggets, $nuggets);
    }
    
    /**
     * Calcule la hauteur en pixels nécessaires pour afficher 
     */
    public function computeHeight($fontsize, $width){
        
        $this->_ready2PrintText = new FcmReady2PrintText(
            $this->_nuggets,
            $this->getParameter('font'),
            $fontsize,
            $width
        );
        $this->_ready2PrintText->preRender();
        
        return $this->_ready2PrintText->getHeight();
    }
    
    
    /**
     * Génère la liste des nuggets à partir d'un texte
     */
    public static function text2Nuggets($text){
        
        $lines = preg_split('#(\R+)#m', $text, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        // On a séparé les lignes. On parcours chaque ligne
        $nuggets = array();
        
        foreach($lines as $k => $v){
            self::appendNuggets($nuggets, $v);
        }
        
        //var_dump($nuggets);
        
        return $nuggets;
    }
    
    
    /**
     * Ajoute les nuggets d'un texte au tableau des nuggets
     */
    public static function appendNuggets(&$nuggets, $text){
        
        $newnuggs = self::createNuggets($text);
        
        $nuggets = array_merge($nuggets, $newnuggs);
    }
    
    
    /**
     * Crée un tableau de nuggets par ligne
     */
    public static function createNuggets($text){
        
        
        
        $nuggets = self::splitNuggets($text);
        $nuggets = array_map('FcmAbstractTextNugget::createNugget', $nuggets);
        
        return $nuggets;
    }
    
    
    /**
     * Découpe le texte sélectionné en plusieurs nuggets
     */
    public static function splitNuggets($text){
        
        $regex = '#(\s+|(?:\{\w+\})+|</?i>)#';
        return preg_split($regex, $text, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
    }
    
    /**
     * Renvoie si le component est vide de texte.
     */
    public function isEmpty(){
        return empty($this->_nuggets);
    }
    
}