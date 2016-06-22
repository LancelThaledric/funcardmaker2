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
        
        $this->_capaComponent = new FcmMultiLineComponent($funcard);
        $this->_taComponent = new FcmMultiLineComponent($funcard);
    }
    
    public function apply(){
        //var_dump($this);
        self::$draw->push();
        
        // Là c'est du debug ! On affiche le BBOX de la capabox !
        $capabox_bbox = new Imagick();
        $capabox_bbox->newImage(
            $this->getFuncard()->xc($this->getParameter('w')),
            $this->getFuncard()->yc($this->getParameter('h')),
            'rgba(0,0,255,0.2)'
        );
        $this->getFuncard()->getCanvas()->compositeImage(
            $capabox_bbox, Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')),
            $this->getFuncard()->yc($this->getParameter('y'))
        );
        
        
        // TODO
        //var_dump($this);
        
        
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        
        $this->setParameter('fontsize', 1);
        $this->setParameter('textcapa', '');
        $this->setParameter('textta', '');
        $this->setParameter('fontcapa', 'mplantin');
        $this->setParameter('fontta', 'mplantin-italic');
        
    }
    
    public function configure(){
        
        // C'est lors de la configuration qu'on va calculer les tailles de polices et prérendre le texte.
        // Le apply() ne se charge que de l'affichage et de la fusion des calques.
        
        // On fait hériter les paramètres aux components fils
        
        $this->_capaComponent->setParameter('font', $this->getParameter('fontcapa'));
        $this->_capaComponent->setParameter('text', $this->getParameter('textcapa'));
        $this->_capaComponent->setParameter('x', $this->getParameter('x'));
        $this->_capaComponent->setParameter('w', $this->getParameter('w'));
        
        $this->_taComponent->setParameter('font', $this->getParameter('fontta'));
        $this->_taComponent->setParameter('text', $this->getParameter('textta'));
        $this->_taComponent->setParameter('x', $this->getParameter('x'));
        $this->_taComponent->setParameter('w', $this->getParameter('w'));
        
        // Pour les deux components, il reste encore les params y, h et fontsize à calculer
        // Nous allons donc faire une boucle pour calculer la bonne taille de police.
        
        $this->computeOptimalFontSize();
        
        return false;
    
    }
                                                       
    private function computeOptimalFontSize(){
        $ok = false;
        $fontsize = $this->getParameter('fontsize');
        $computed_font_size = $this->getFuncard()->fsc($fontsize);
        while(!$ok){
            $fontsize = $this->getFuncard()->reverse_fsc($computed_font_size);
            $this->_capaComponent->setParameter('fontsize', $fontsize);
            $this->_taComponent->setParameter('fontsize', $fontsize);
            
            $this->_capaComponent->configure();
            $this->_taComponent->configure();
            // là on fusionne les lignes de la capa et du ta
            $this->fuseCapaTa();
            
            $totalheight = $this->computeHeight($computed_font_size);
            
            /* en attendant que tout fonctionne */ $ok = true;
        }
    }
    
    /**
     * fusionne les lignes de texte du CapaCOmponent et du TaComponent
     */
    private function fuseCapaTa(){
        
        //var_dump($this->_capaComponent->getLines());
        //var_dump($this->_taComponent->getLines());
        
        $this->_capaComponent->addNugget(new FcmNewSectionNugget());
        $this->_capaComponent->addNuggets($this->_taComponent->getNuggets());
        
        //var_dump($this->_capaComponent->getNuggets());
    }
    
    /**
     * Calcule la hauteur en pixels nécessaires pour afficher 
     */
    private function computeHeight($fontsize){
        
        $computed_width = $this->getFuncard()->xc($this->getParameter('w'));
        return $this->_capaComponent->computeHeight($fontsize, $computed_width);
        
    }
}