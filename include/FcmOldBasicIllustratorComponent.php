<?php

require_once('include/FcmSingleLineComponent.php');

/**
 * Component affichant une ligne de texte (Titre, type, etc.)
 *
 * Parameters (from SingleLine) : 
 * - x : position x
 * - y : position y
 * - color : text color
 * - size : font size in em basesize
 * - text : text to display
 * - font : font name
 * - align : aligmement : 'left', 'right' or 'center'. Default left.
 */

class FcmOldBasicIllustratorComponent extends FcmSingleLineComponent {
    
    // self::$draw est disponible par héritage
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function apply(){
        //var_dump($this);
        if(empty($this->getParameter('text'))) return;
        
        self::$draw->push();
        
        $this->setParameter('text', 'Illus. '.$this->getParameter('text'));
        
        parent::apply();
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('color', 'black');
        $this->setParameter('size', 1);
        $this->setParameter('text', '');
        $this->setParameter('font', 'matrix');
        $this->setParameter('align', 'left');
    }
    
    public function configure(){ return false; }
    
}