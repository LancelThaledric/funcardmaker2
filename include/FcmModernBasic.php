<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmSingleLineComponent.php');
require_once('include/FcmBorderComponent.php');
require_once('include/FcmModernBasicBackgroundComponent.php');
require_once('include/FcmCustomBackgroundComponent.php');
require_once('include/FcmIllustrationComponent.php');
require_once('include/FcmCapaboxComponent.php');
require_once('include/FcmManaCostComponent.php');
require_once('include/FcmExtensionSymbolComponent.php');
require_once('include/FcmIllustratorComponent.php');
require_once('include/FcmModernBasicFEBoxComponent.php');

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
            'title' => new FcmSingleLineComponent($this, 100),
            'type' => new FcmSingleLineComponent($this, 100),
            'illustration' => new FcmIllustrationComponent($this, 100),
            'capabox' => new FcmCapaboxComponent($this, 100),
            'cm' => new FcmManaCostComponent($this, 100),
            'se' => new FcmExtensionSymbolComponent($this, 100),
            
            'illus' => new FcmIllustratorComponent($this, 200),
            'copyright' => new FcmSingleLineComponent($this, 200),
            'fe' => new FcmModernBasicFEBoxComponent($this, 200)
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
            'border' => [],
            'capabox' => [
                'x' => 79. / 791. * 100,
                'y' => 700. / 1107. * 100,
                'w' => 632. / 791. * 100,
                'h' => 283. / 1107. * 100
            ],
            'cm' => [
                'x' => 723. / 791. * 100,
                'y' => 75. / 1107. * 100,
                'size' => 44. / 36.,
                'shadowx' => -1. / 791. * 100,
                'shadowy' => 4. / 1107. * 100,
                'largeManaOffset' => -4. / 1107. * 100
            ],
            'se' => [
                'x' => 718. / 791. * 100,
                'y' => 630. / 1107. * 100,
                'h' => 44. / 1107. * 100
            ],
            'illus' => [
                'x' => 124. / 791. * 100,
                'y' => 1034. / 1107. * 100,
                'brushx' => 65. / 791. * 100,
                'brushy' => 1035. / 1107. * 100,
                'size' => 29. / 36.,
                'brushsize' => 26. / 36.
            ],
            'copyright' => [
                'x' => (67. / 791.) * 100,
                'y' => (1057. / 1107.) * 100,
                'size' => 18. / 36.,
                'font' => 'mplantin'
            ],
            'fe' => [
                'x' => 570. / 791. * 100,
                'y' => 973. / 1107. * 100,
                'w' => 173. / 791. * 100,
                'h' => 93. / 1107. * 100,
                'textx' => 588. / 791. * 100,
                'texty' => 981. / 1107. * 100,
                'textw' => 145. / 791. * 100,
                'texth' => 60. / 1107. * 100
            ]
        ];
    }
    
    //* Liste d'écoute
    public function initListeningList(){
        // TODO liste d'écoute pour les cartes modern-basic
        $this->_components['illus']->listen('color');
        $this->_components['illus']->listen('altcolor');
        $this->_components['copyright']->listen('color');
        $this->_components['fe']->listen('color');
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
        // Capacité / TA
        $this->updateParameter('capabox', 'textcapa', $this->getField('capa'));
        $this->updateParameter('capabox', 'textta', $this->getField('ta'));
        // Mana cost
        $this->updateParameter('cm', 'text', $this->getField('cm'));
        // Extension symbol
        $this->updateParameter('se', 'name', $this->getField('se-extension'));
        $this->updateParameter('se', 'rarity', $this->getField('se-rarity'));
        $this->updateParameter('se', 'file', $this->getField('se-custom'));
        // Illustrator
        $this->updateParameter('illus', 'text', $this->getField('illustrator'));
        // Copyright
        $this->updateParameter('copyright', 'text', $this->getField('copyright'));
        // F/E
        $this->updateParameter('fe', 'text', $this->getField('fe'));
    }
    
    //* Dernières vérificatiosn avant l'inition
    public function beforeInit(){
        // Dans le cas du fond personnalisé, il faut précharger l'image pour connaître la taille du canvas
        if($this->_customBackground){
            $this->_components['background']->loadImage();
        }
    }
}