<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmBorderComponent.php');
require_once('include/FcmOldBasicBackgroundComponent.php');
require_once('include/FcmCustomBackgroundComponent.php');

//* Template Moderne Basique

class FcmOldBasic extends FcmFuncard {
    
    //* Nom du template
    const TEMPLATE_NAME = 'old-basic';
    
    //* Taille par défault
    const DEFAULT_WIDTH = 787;
    const DEFAULT_HEIGHT = 1087;
    
    //* fond perso ?
    private $_customBackground = false;
    
    //* Constructeur
    public function __construct($data = null, $init = true){
        parent::__construct(self::DEFAULT_WIDTH, self::DEFAULT_HEIGHT, false, $data);
        $this->setTemplateName(self::TEMPLATE_NAME);
        
        //fiels
        $this->initDefaultFields();
        $this->setDefaults();
        $this->import($data);
        
        //components
        $this->initComponents();
        $this->pushComponentsData();
        
        //component parameters
        $this->initDefaultParameters();
        $this->setDefaultParameters();
        
        $this->initListeningList();
        //var_dump($this->_fields);

        $this->beforeInit();
        
        $this->init();
        
        $this->configureComponents();
        
        //var_dump($this);
    }
    
    //* Champs par défaut
    public function initDefaultFields(){
        $this->_defaults = [
            'border' => 'black',
            'background-base' => 'r',
        ];
    }
    
    //* Components
    public function initComponents(){
        $this->_components = [
            
        ];
        
        // Gestion du fond
        $this->_customBackground = $this->hasField('background-custom');
        if($this->_customBackground){
            $this->_components['background'] = new FcmCustomBackgroundComponent($this, 0);
        } else {
            $this->_components['background'] = new FcmOldBasicBackgroundComponent($this, 0);
            $this->_components['border'] = new FcmBorderComponent($this, 1);
        }
        
    }
    
    //* Parameters par défaut
    public function initDefaultParameters(){
        $this->_defaultParameters = [
            
        ];
        
        // Bordure uniquement si fond real
        if(!$this->_customBackground){
            $this->_defaultParameters['border'] = [
                'thickness' => 41. / 787. * 100
            ];
        }
    }
    
    //* Liste d'écoute
    public function initListeningList(){
        // TODO liste d'écoute pour les cartes old-basic
        
    }
    
    //* Envoie les champs aux components
    public function pushComponentsData(){
        // Fond généré
        $this->updateParameter('background', 'base-color', $this->getField('background-base'));
        $this->updateParameter('background', 'capabox-color', $this->getField('background-capabox'));
        // Fond personnalisé
        $this->updateParameter('background', 'file', $this->getField('background-custom'));
    }
    
    //* Dernières vérificatiosn avant l'inition
    public function beforeInit(){
        // Dans le cas du fond personnalisé, il faut précharger l'image pour connaître la taille du canvas
        if($this->_customBackground){
            $this->_components['background']->loadImage();
        }
    }
}