<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmSingleLineComponent.php');
require_once('include/FcmBorderComponent.php');
require_once('include/FcmModernBasicBackgroundComponent.php');
require_once('include/FcmCustomBackgroundComponent.php');
require_once('include/FcmIllustrationComponent.php');

//* Template Moderne Basique

class FcmModernBasic extends FcmFuncard {
    
    //* Nom du template
    const TEMPLATE_NAME = 'modern-basic';
    
    //* Taille par défault
    const DEFAULT_WIDTH = 791;
    const DEFAULT_HEIGHT = 1107;
    
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
            'title' => new FcmSingleLineComponent($this, 100),
            'type' => new FcmSingleLineComponent($this, 100),
            'illustration' => new FcmIllustrationComponent($this, 100)
        ];
        
        // Gestion du fond
        $this->_customBackground = $this->hasField('background-custom');
        if($this->_customBackground){
            $this->_components['background'] = new FcmCustomBackgroundComponent($this, 0);
        } else {
            $this->_components['background'] = new FcmModernBasicBackgroundComponent($this, 0);
            $this->_components['border'] = new FcmBorderComponent($this, 1);
        }
        
    }
    
    //* Parameters par défaut
    public function initDefaultParameters(){
        $this->_defaultParameters = [
            'title' => [
                'x' => (73. / 791.) * 100,
                'y' => (108. / 1107.) * 100,
                'size' => 48. / 36.
            ],
            'type' => [
                'x' => (80. / 791.) * 100,
                'y' => (664. / 1107.) * 100,
                'size' => 40. / 36.
            ],
            'border' => []
        ];
    }
    
    //* Liste d'écoute
    public function initListeningList(){
        // TODO liste d'écoute pour les cartes modern-basic
    }
    
    //* Envoie les champs aux components
    public function pushComponentsData(){
        // Titre et type
        $this->updateParameter('title', 'text', $this->getField('title'));
        $this->updateParameter('type', 'text', $this->getField('type'));
        // Fond généré
        $this->updateParameter('background', 'base-color', $this->getField('background-base'));
        $this->updateParameter('background', 'edging-color', $this->getField('background-edging'));
        $this->updateParameter('background', 'box-color', $this->getField('background-boxes'));
        // Fond personnalisé
        $this->updateParameter('background', 'file', $this->getField('background-custom'));
        // Illustration
        $this->updateParameter('illustration', 'file', $this->getField('illustration'));
        $this->updateParameter('illustration', 'crop-x', $this->getField('illuscrop-x'));
        $this->updateParameter('illustration', 'crop-y', $this->getField('illuscrop-y'));
        $this->updateParameter('illustration', 'crop-w', $this->getField('illuscrop-w'));
        $this->updateParameter('illustration', 'crop-h', $this->getField('illuscrop-h'));
    }
    
    //* Dernières vérificatiosn avant l'inition
    public function beforeInit(){
        // Dans le cas du fond personnalisé, il faut précharger l'image pour connaître la taille du canvas
        if($this->_customBackground){
            $this->_components['background']->loadImage();
        }
    }
}