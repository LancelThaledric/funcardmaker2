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
 * - fontta : font to use for ambient text and italic capacity
 */

class FcmCapaboxComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    const MIN_COMPUTED_FONT_SIZE = 10;
    
    private $_capaComponent;
    private $_taComponent;
    private $_totalHeight;
    
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
        
        // On effectue le rendu
        $yoffset = ($this->getFuncard()->yc($this->getParameter('h'))
                    - $this->_totalHeight)
                   / 2.;
        
        
        $this->_capaComponent->setParameter('y', $this->getParameter('y') + $this->getFuncard()->reverse_yc($yoffset));
        $this->_capaComponent->setParameter('h', $this->getFuncard()->reverse_yc($this->_totalHeight));
        
        $this->_capaComponent->apply();
        
        
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
        $this->_capaComponent->setParameter('fontItalic', $this->getParameter('fontta'));
        $this->_capaComponent->setParameter('text', $this->getParameter('textcapa'));
        $this->_taComponent->setParameter('text', $this->getParameter('textta'));
        $this->_capaComponent->setParameter('x', $this->getParameter('x'));
        $this->_capaComponent->setParameter('w', $this->getParameter('w'));
        
        // Pour les deux components, il reste encore les params y, h et fontsize à calculer
        // Nous allons donc faire une boucle pour calculer la bonne taille de police.
        
        $this->_capaComponent->configure();
        $this->_taComponent->configure();
        // là on fusionne les lignes de la capa et du ta
        $this->fuseCapaTa();
        
        $this->computeOptimalFontSize();
        //var_dump($this->_capaComponent);
        
        return false;
    
    }
                                                       
    private function computeOptimalFontSize(){
        $ok = false;
        $fontsize = $this->getParameter('fontsize');
        $computed_font_size = $this->getFuncard()->fsc($fontsize);
        $expectedHeight = $this->getFuncard()->yc($this->getParameter('h'));
        while(!$ok){
            //var_dump($computed_font_size);
            if($computed_font_size < self::MIN_COMPUTED_FONT_SIZE){    // En deça de cette valeur le etxte est illisible
                $computed_font_size = 0;
                break;
            }
            
            $fontsize = $this->getFuncard()->reverse_fsc($computed_font_size);
            $this->_capaComponent->setParameter('fontsize', $fontsize);
            $this->_taComponent->setParameter('fontsize', $fontsize);
            
            // On calcule la heuteur totale
            $this->_totalHeight = $this->computeHeight($computed_font_size);
            //var_dump('total height = '.$totalheight . ', ' . $expectedHeight . ' expected');
            
            // On regarde si on a pas dépassé
            if($this->_totalHeight > $expectedHeight){
                $ok = false;
                // Pour calculer la nouvelle valeur de fontsize, on calcule le ratio hauteur obtenue/hauteur espérée.
                // On rapproche ce ratio de 1 pour prendre en compte qu'on gagnera aussi de la place en largeur
                // On multiplie par ce ratio.
                // C'est une méthode logarithmique plus rapide que la méthode linéaire (en décrémentant petit à petit).
                // ... Mais peut-être moins précise, à vérifier.
                $ratio = $expectedHeight / $this->_totalHeight;
                //var_dump('ratio : '.$ratio.', multiply by '.(1+$ratio) / 2.);
                $computed_font_size = (int) ($computed_font_size * (1+$ratio) / 2. );
                /*$computed_font_size--;*/
            } else {
                /* tout fonctionne */ $ok = true;
            }
            
        }
        
        //echo 'ok ', $computed_font_size;
        $this->_capaComponent->setParameter('fontsize', $fontsize);
    }
    
    /**
     * fusionne les lignes de texte du CapaCOmponent et du TaComponent
     */
    private function fuseCapaTa(){
        
        //var_dump($this->_capaComponent->getLines());
        //var_dump($this->_taComponent->getLines());
        
        if(!$this->_capaComponent->isEmpty() && !$this->_taComponent->isEmpty()){
            $this->_capaComponent->addNugget(new FcmNewSectionNugget());
        }

        if(!$this->_taComponent->isEmpty()){
            $this->_capaComponent->addNugget(new FcmBeginItalicNugget());
            $this->_capaComponent->addNuggets($this->_taComponent->getNuggets());
            $this->_capaComponent->addNugget(new FcmEndItalicNugget());
        }
        
        
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