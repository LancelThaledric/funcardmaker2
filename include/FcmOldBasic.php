<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmBorderComponent.php');
require_once('include/FcmOldBasicBackgroundComponent.php');
require_once('include/FcmCustomBackgroundComponent.php');
require_once('include/FcmSingleLineComponent.php');
require_once('include/FcmSingleLineShadowComponent.php');
require_once('include/FcmManaCostComponent.php');
require_once('include/FcmCapaboxComponent.php');
require_once('include/FcmOldBasicIllustratorComponent.php');
require_once('include/FcmOldBasicIllustratorShadowComponent.php');
require_once('include/FcmExtensionSymbolComponent.php');

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
            'illustration' => new FcmIllustrationComponent($this, -1),
            'title' => new FcmSingleLineComponent($this, 100),
            'type' => new FcmSingleLineComponent($this, 100),
            'titleshadow' => new FcmSingleLineShadowComponent($this, 50),
            'typeshadow' => new FcmSingleLineShadowComponent($this, 50),
            'cm' => new FcmManaCostComponent($this, 50),
            'capabox' => new FcmCapaboxComponent($this, 50),
            'fe' => new FcmSingleLineComponent($this, 100),
            'feshadow' => new FcmSingleLineComponent($this, 50),
            'illus' => new FcmOldBasicIllustratorComponent($this, 100),
            'illusshadow' => new FcmOldBasicIllustratorShadowComponent($this, 50),
            'copyright' => new FcmSingleLineComponent($this, 100),
            'se' => new FcmExtensionSymbolComponent($this, 100)
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
            'illustration' => [
                'x' => 93. / 787. * 100,
                'y' => 106. / 1087. * 100,
                'w' => 602. / 787. * 100,
                'h' => 485. / 1087. * 100
            ],
            'title' => [
                'x' => 91. / 787. * 100,
                'y' => 85. / 1087. * 100,
                'size' => 45. / 36.,
                'color' => 'white',
                'font' => 'magicmedieval'
            ],
            'type' => [
                'x' => 81. / 787. * 100,
                'y' => 638. / 1087. * 100,
                'size' => 36. / 36.,
                'color' => 'white',
                'font' => 'mplantin'
            ],
            'titleshadow' => [
                'x' => 92. / 787. * 100,
                'y' => 86. / 1087. * 100,
                'size' => 45. / 36.,
                'color' => 'rgba(0,0,0,0.4)',
                'font' => 'magicmedieval',
                'strokewidth' => 1. / 36.,
                'blur' => 0
            ],
            'typeshadow' => [
                'x' => 82. / 787. * 100,
                'y' => 639. / 1087. * 100,
                'size' => 36. / 36.,
                'color' => 'rgba(0,0,0,0.4)',
                'font' => 'mplantin',
                'strokewidth' => 1. / 36.,
                'blur' => 0
            ],
            'cm' => [
                'x' => 726. / 787. * 100,
                'y' => 49. / 1087. * 100,
                'size' => 49. / 36.,
                'shadowx' => 0, // Pas d'ombre
                'shadowy' => 0,
                'largeManaOffset' => 0
            ],
            'capabox' => [
                'x' => 100. / 787. * 100,
                'y' => 666. / 1087. * 100,
                'w' => 588. / 787. * 100,
                'h' => 285. / 1087. * 100,
                'fontsize' => 39. / 36.
            ],
            'fe' => [
                'x' => 719. / 787. * 100,
                'y' => 1020. / 1087. * 100,
                'size' => 49. / 36.,
                'color' => 'white',
                'font' => 'plantin-bold',
                'align' => 'right'
            ],
            'feshadow' => [
                'x' => 722. / 787. * 100,
                'y' => 1022. / 1087. * 100,
                'size' => 49. / 36.,
                'color' => 'rgba(0,0,0,0.8)',
                'font' => 'plantin-bold',
                'align' => 'right'
            ],
            'illus' => [
                'x' => 390. / 787. * 100,
                'y' => 1003. / 1087. * 100,
                'size' => 31. / 36.,
                'color' => 'white',
                'font' => 'mplantin',
                'align' => 'center'
            ],
            'illusshadow' => [
                'x' => 391. / 787. * 100,
                'y' => 1004. / 1087. * 100,
                'size' => 31. / 36.,
                'color' => 'rgba(0,0,0,0.4)',
                'font' => 'mplantin',
                'align' => 'center',
                'strokewidth' => 1. / 36.,
                'blur' => 0
            ],
            'copyright' => [
                'x' => 391. / 787. * 100,
                'y' => 1025. / 1087. * 100,
                'size' => 18. / 36.,
                'color' => 'white',
                'font' => 'mplantin',
                'align' => 'center'
            ],
            'se' => [
                'x' => 701. / 787. * 100,
                'y' => 603. / 1087. * 100,
                'h' => 43. / 1087. * 100
            ]
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
        $this->_components['copyright']->listen('color');
    }
    
    //* Envoie les champs aux components
    public function pushComponentsData(){
        // Fond généré
        $this->updateParameter('background', 'base-color', $this->getField('background-base'));
        $this->updateParameter('background', 'capabox-color', $this->getField('background-capabox'));
        // Fond personnalisé
        $this->updateParameter('background', 'file', $this->getField('background-custom'));
        // Illustration
        $this->updateParameter('illustration', 'file', $this->getField('illustration'));
        $this->updateParameter('illustration', 'crop-x', $this->getField('illuscrop-x'));
        $this->updateParameter('illustration', 'crop-y', $this->getField('illuscrop-y'));
        $this->updateParameter('illustration', 'crop-w', $this->getField('illuscrop-w'));
        $this->updateParameter('illustration', 'crop-h', $this->getField('illuscrop-h'));
        // Titre et type
        $this->updateParameter('title', 'text', $this->getField('title'));
        $this->updateParameter('type', 'text', $this->getField('type'));
        $this->updateParameter('titleshadow', 'text', $this->getField('title'));
        $this->updateParameter('typeshadow', 'text', $this->getField('type'));
        // Coût de mana
        $this->updateParameter('cm', 'text', $this->getField('cm'));
        // Capacité / TA
        $this->updateParameter('capabox', 'textcapa', $this->getField('capa'));
        $this->updateParameter('capabox', 'textta', $this->getField('ta'));
        $this->updateParameter('capabox', 'title', $this->getField('title'));
        // F/E
        $this->updateParameter('fe', 'text', $this->getField('fe'));
        $this->updateParameter('feshadow', 'text', $this->getField('fe'));
        // Illustrator
        $this->updateParameter('illus', 'text', $this->getField('illustrator'));
        $this->updateParameter('illusshadow', 'text', $this->getField('illustrator'));
        // Copyright
        $this->updateParameter('copyright', 'text', $this->getField('copyright'));
        // Extension symbol
        $this->updateParameter('se', 'name', $this->getField('se-extension'));
        $this->updateParameter('se', 'rarity', $this->getField('se-rarity'));
        $this->updateParameter('se', 'file', $this->getField('se-custom'));
    }
    
    //* Dernières vérificatiosn avant l'inition
    public function beforeInit(){
        // Dans le cas du fond personnalisé, il faut précharger l'image pour connaître la taille du canvas
        if($this->_customBackground){
            $this->_components['background']->loadImage();
        }
    }
}