<?php

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmMultiLineComponent.php');

/**
 * Component affichant la boîte de capacité (capa & ta)
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
    
    private $_capaComponent;
    private $_taComponent;
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
        
        $this->_capaCoponent = new FcmMultiLineComponent($funcard);
        $this->_taCoponent = new FcmMultiLineComponent($funcard);
    }
    
    public function apply(){
        //var_dump($this);
        self::$draw->push();
        
        // TODO
        //var_dump($this);
        
        
        
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
    
    public function configure(){
        
        // C'est lors de la configuration qu'on va calculer les tailles de polices et prérendre le texte.
        // Le apply() ne se charge que de l'affichage et de la fusion des calques.
        
        // Nous allons donc faire une boucle pour calculer la bonne taille de police.
        
        // TODO
        
        return false;
    
    }
    
}