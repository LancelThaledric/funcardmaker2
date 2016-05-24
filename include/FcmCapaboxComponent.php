<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Component affichant une ligne de texte (Titre, type, etc.)
 *
 * Parameters : 
 * - x : position x
 * - y : position y
 * - w : width of the box
 * - h : height of the box
 * - padding : internal padding in the box
 * - fontsize : font size in em basesize
 * - textcapa : capacity text to display
 * - textta : ambient text to display
 * - fontcapa : font to use for capacity text
 * - fontta : font to use for ambient text
 */

class FcmCapaboxComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
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
        
        $this->setParameter('padding', 11. / 791. * 100);
        $this->setParameter('fontsize', 1);
        $this->setParameter('textcapa', '');
        $this->setParameter('textta', '');
        $this->setParameter('fontcapa', 'mplantin');
        $this->setParameter('fontta', 'mplantin-italic');
        
    }
    
}