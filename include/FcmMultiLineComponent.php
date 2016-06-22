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
    
    private $_lines = null;
    
    public function getLines() { return $this->_lines; }
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        self::$draw->push();
        
        // TODO
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        
        $this->setParameter('fontsize', 1);
        $this->setParameter('text', '');
        $this->setParameter('font', 'mplantin');
        
    }
    
    public function configure(){
        
        // On sépare les lignes entre elles
        $this->_lines = FcmTextLine::text2Lines($this->getParameter('text'));
        
        return true;
    
    }
    
    public function addLine($line){
        $this->_lines[] = $line;
    }
    
    public function addLines($lines){
        $this->_lines = array_merge($this->_lines, $lines);
    }
    
}