<?php

require_once('include/FcmSingleLineShadowComponent.php');

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

class FcmOldBasicIllustratorShadowComponent extends FcmSingleLineShadowComponent {
    
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
        parent::setDefaultParameters();
    }
    
    public function configure(){ return false; }
    
}