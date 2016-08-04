<?php

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmMultiLineComponent.php');

/**
 * Component affichant la boîte de capacité (capa & ta)
 *
 * Parameters : 
 * - x{n}, y{n}, w{n}, h{n} : bounding-box of nth case
 * - text{n} : capacity text of the nth case
 * - fontsize : font size in em basesize
 * - fontcapa : font to use for capacity text
 * - fontta : font to use for ambient text and italic capacity
 * - title : title of the card (to replace ~this~ tags)
 */

class FcmMultiCapaboxComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    // En dessous de cette taille de police (en pixels) le texte ne sera pas rendu.
    const MIN_COMPUTED_FONT_SIZE = 10;
    
    private $_capaComponents = [];
    private $_nbComponents = 0;
    private $_totalHeights = [];
    private $_expectedHeights = [];
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function configure(){
        
        $title = $this->getParameter('title');
        
        // Etape 1, on compte et crée le nb de cases à créer
        // $n numéro du component 1..n
        for($this->_nbComponents = 0, $n = 1 ; ; ++$this->_nbComponents, ++$n){
            
            // On passe toutes les cases où nous n'aovns pas tous les paramètres requis
            if(  !$this->hasParameter('x'.$n)
              || !$this->hasParameter('y'.$n)
              || !$this->hasParameter('w'.$n)
              || !$this->hasParameter('h'.$n)
              ) break;
            
            // Si la case n'a pas de paramètre text, on en créé un vide.
            if(!$this->hasParameter('text'.$n)) $this->setParameter('text'.$n, '');
            
            // On créé la case, on lui file les paramètres nécessaire au calcul de la hauteur et de mise en place du texte.
            $this->_capaComponents[$this->_nbComponents] = new FcmMultiLineComponent($this->getFuncard());
            $this->_capaComponents[$this->_nbComponents]->setParameter('font', $this->getParameter('fontcapa'));
            $this->_capaComponents[$this->_nbComponents]->setParameter('fontItalic', $this->getParameter('fontta'));
            
            $this->_capaComponents[$this->_nbComponents]->setParameter('text', $this->getParameter('text'.$n));
            // On remplace les tags ~this~ par le titre de la cate
            if(!empty($title)){
                $this->_capaComponents[$this->_nbComponents]->setParameter(
                    'text', str_replace(
                        '~this~', $title,
                        $this->_capaComponents[$this->_nbComponents]->getParameter('text')
                    )
                );
            }
            
            $this->_capaComponents[$this->_nbComponents]->setParameter('x', $this->getParameter('x'.$n));
            $this->_capaComponents[$this->_nbComponents]->setParameter('w', $this->getParameter('w'.$n));
            // le Y et H restent à calculer.
            $this->_capaComponents[$this->_nbComponents]->configure();
            
            // On sauvegarde toutefois la hauteur maximum atteignable pour chaque case.
            $this->_expectedHeights[$this->_nbComponents] = $this->getFuncard()->yc($this->getParameter('h'.$n));
            $this->_totalHeights[$this->_nbComponents] = 0;
            
        }
        
        // C'est lors de la configuration qu'on va calculer les tailles de polices et prérendre le texte.
        // Le apply() ne se charge que de l'affichage et de la fusion des calques.
        
        // Pour les n components, il reste encore les params y, h et fontsize à calculer
        // Nous allons donc faire une boucle pour calculer la bonne taille de police.
        $this->computeOptimalFontSize();
        //var_dump($this->_capaComponent);
        return false;
    
    }
    
    public function apply(){
        //var_dump($this);
        self::$draw->push();
        
        // Là c'est du debug ! On affiche le BBOX de la capabox !
        /*$capabox_bbox = new Imagick();
        $capabox_bbox->newImage(
            $this->getFuncard()->xc($this->getParameter('w')),
            $this->getFuncard()->yc($this->getParameter('h')),
            'rgba(0,0,255,0.2)'
        );
        $this->getFuncard()->getCanvas()->compositeImage(
            $capabox_bbox, Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')),
            $this->getFuncard()->yc($this->getParameter('y'))
        );*/
        
        // On effectue le rendu
        for($i=0 ; $i<$this->_nbComponents ; ++$i){
            $yoffset = ($this->getFuncard()->yc($this->getParameter('h'.($i+1)))
                    - $this->_totalHeights[$i])
                   / 2.;
            
            $this->_capaComponents[$i]->setParameter('y', $this->getParameter('y'.($i+1)) + $this->getFuncard()->reverse_yc($yoffset));
            $this->_capaComponents[$i]->setParameter('h', $this->getFuncard()->reverse_yc($this->_totalHeights[$i]));

            $this->_capaComponents[$i]->apply();
        }
        
        
        
        
        
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        
        $this->setParameter('fontsize', 1);
        $this->setParameter('textcapa', '');
        $this->setParameter('textta', '');
        $this->setParameter('fontcapa', 'mplantin');
        $this->setParameter('fontta', 'mplantin-italic');
        $this->setParameter('title', '');
        
    }
                                                       
    private function computeOptimalFontSize(){
        $ok = false;
        $fontsize = $this->getParameter('fontsize');
        $computed_font_size = $this->getFuncard()->fsc($fontsize);
        while(!$ok){
            //var_dump($computed_font_size);
            if($computed_font_size < self::MIN_COMPUTED_FONT_SIZE){    // En deça de cette valeur le texte est illisible
                $computed_font_size = 0;
                break;
            }
            
            $fontsize = $this->getFuncard()->reverse_fsc($computed_font_size);
            // $i 0..n-1
            for($i=0 ; $i<$this->_nbComponents ; ++$i){
                $ok = true;
                $this->_capaComponents[$i]->setParameter('fontsize', $fontsize);
                
                // On calcule la hauteur totale
                $this->_totalHeights[$i] = $this->computeHeight($i, $computed_font_size);
                
                if($this->_totalHeights[$i] > $this->_expectedHeights[$i]){
                    $ok = false;
                    // Pour calculer la nouvelle valeur de fontsize, on calcule le ratio hauteur obtenue/hauteur espérée.
                    // On rapproche ce ratio de 1 pour prendre en compte qu'on gagnera aussi de la place en largeur
                    // On multiplie par ce ratio.
                    // C'est une méthode logarithmique plus rapide que la méthode linéaire (en décrémentant petit à petit).
                    // ... Mais peut-être moins précise, à vérifier.
                    $ratio = $this->_expectedHeights[$i] / $this->_totalHeights[$i];
                    $computed_font_size = (int) ($computed_font_size * (1+$ratio) / 2. );
                    break;
                }
                if(!$ok) break; // pas besoin de tester les cases suivantes si celle-ci n'est déjà pas bonne.
                
            }
            
            // Si on est arrivés jusque-là, on est bons.
            
        }
        
        //echo 'ok ', $computed_font_size;
        for($i=0 ; $i<$this->_nbComponents ; ++$i){
            $this->_capaComponents[$i]->setParameter('fontsize', $fontsize);
        }
    }
    
    /**
     * Calcule la hauteur en pixels nécessaires pour afficher
     * $i 0..n-1
     */
    private function computeHeight($i, $fontsize){
        
        $computed_width = $this->getFuncard()->xc($this->getParameter('w'.($i+1)));
        return $this->_capaComponents[$i]->computeHeight($fontsize, $computed_width);
        
    }
}