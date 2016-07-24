<?php

require_once('include/FcmFuncardComponent.php');

/**
 * Classe gérant une ressource destinée aux fonds de carte (chargement, hybridation, etc)
 *
 * Parameters
 * - x, y, w, h : bounding box of the layer
 * - category : ressource category. Default is "background" (Yes, it's the component name) 
 * - type : folder name of the resource in the template resource folder.
 * - method : sub-folder name of the mask folder. Can be "vertical", "horizontal", "radial", etc.
 * - name : name/color of the resource, without extension. For hybridation, use the "/" separator
 * - extension : extension of the resource. Default is "png". (Keep png, please.)
 */

class FcmBackgroundLayerComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    private $_templateName;
    
    //* Tableau des couleurs à hybrider
    private $_colors;
    private $_nbColors;
    //* Tableau des resources chargées
    private $_resources;
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
        
        $this->_templateName = $this->getFuncard()->getTemplateName();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('category', 'background');
        $this->setParameter('extension', 'png');
    }
    
    public function configure(){ 
        // 1 : On sépare les différentes couleurs
        if(!$this->hasParameter('name')) return;
        
        $this->_nbColors = 0;
        $name = $this->getParameter('name');
        if(!empty($name)){
            $this->_colors = explode('/', $name, 21);
            $this->_nbColors = count($this->_colors);
        }
        
        // On n'est valide que si on a au moins une couleur
        if($this->_nbColors < 1) return null;
        
        // 2 : on charge les ressources
        $sizex = $this->getFuncard()->xc($this->getParameter('w'));
        $sizey = $this->getFuncard()->yc($this->getParameter('h'));
        
        foreach($this->_colors as $color){
            $this->_resources[] = $this->getFuncard()->loadResource(
                $this->getParameter('category'),
                $this->getParameter('type'),
                $color . '.' . $this->getParameter('extension'),
                $sizex, $sizey
            );
        }
        
        // 3 : On calcule le rendu hybride de ces ressources
        if(!$this->isMonocolor())
            $this->hybridation();
    }
    
    public function isMonocolor(){ return $this->_nbColors == 1; }
    
    //* Calcule le rendu hybride des ressources chargées
    public function hybridation(){
        
        // 1 : Il faut appliquer le bon masque à chaque ressource
        $i = 1; // Numéro de la ressource courante. De 1 à N.
        foreach($this->_resources as $image){
            
            $mask = $this->loadMask($i);
            $this->applyMask($image, $mask);
            ++$i;
        }
        
        // 2 : On fusionne une à une les resources
        // $1 Numéro de la ressource courante dans l'array. De 0 à N-1.
        for($i = 1 ; $i < $this->_nbColors ; ++$i){
            $this->_resources[0]->compositeImage(
                $this->_resources[$i], Imagick::COMPOSITE_BLEND, 0, 0
            );
        }
        // Le fond généré est dans $this->_resources[0].
    }
    
    //* Charge le masque de calque numéro $num
    public function loadMask($num){
        $mask = $this->getFuncard()->loadResource(
            'background',
            'mask/' . $this->getParameter('method'),
            $this->_nbColors . '-' . $num . '.png'
        );
        return $mask;
    }
    
    //* Applique le masque d'hybridation $mask à la ressource $image
    public function applyMask($image, $mask){
        $posx = $this->getFuncard()->xc($this->getParameter('x'));
        $posy = $this->getFuncard()->yc($this->getParameter('y'));
        
        // On place le masque sur le coin haut-gauche de la funcard, par rapport à la ressource à appliquer
        // Autrement dit, -x, -y.
        $image->compositeImage(
            $mask, Imagick::COMPOSITE_DSTIN, -$posx, -$posy
        );
    }
    
    public function apply(){
        
        // On applique seulement si on a une ressource à appliquer
        if(!isset($this->_resources[0])) return;
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $this->_resources[0],
            Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($this->getParameter('x')),
            $this->getFuncard()->yc($this->getParameter('y'))
        );
        
    }
    
}