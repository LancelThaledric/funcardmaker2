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

//* Template Old Basic

class FcmOldBasic extends FcmFuncard {
    
    //* Constructeur
    public function __construct($data = null){
        parent::__construct(self::DEFAULT_WIDTH, self::DEFAULT_HEIGHT, $data);
    }
    
    /***********************************************************************
    * NOM DU TEMPLATE
    ************************************************************************/
    protected static $_templateName = 'old-basic';
    
    /***********************************************************************
    * TAILLE DU TEMPLATE
    ************************************************************************/
    const DEFAULT_WIDTH = 787;
    const DEFAULT_HEIGHT = 1087;
    
    /***********************************************************************
    * FIELDS
    ************************************************************************/
    //* Champs par défaut
    protected static $_defaultFields = [];
    public static function staticInitDefaultFields(){
        self::$_defaultFields = [
            'border' => 'black',
            'background-base' => 'r',
        ];
    }
    
    /***********************************************************************
    * VARIABLES MEMBRES ADDITIONELLES
    ************************************************************************/
    
    //* fond perso ?
    private $_customBackground = false;
    
    
    /***********************************************************************
    * COMPONENTS
    ************************************************************************/
    public function initComponents(){
        
        $components = [
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
            $components['background'] = new FcmCustomBackgroundComponent($this, 0);
        } else {
            $components['background'] = new FcmOldBasicBackgroundComponent($this, 0);
            $components['border'] = new FcmBorderComponent($this, 1);
        }
        
        // On envoie tout à la funcard
        $this->resetComponents($components);
    }
    
    /***********************************************************************
    * PARAMETERS
    ************************************************************************/
    //* Parameters par défaut
    protected static $_defaultParameters = [];
    public static function staticInitDefaultParameters(){
        self::$_defaultParameters = [
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
            ],
            'border' => [
                'thickness' => 41. / 787. * 100
            ]
        ];
        
    }
    
    /***********************************************************************
    * LISTENING LIST
    ************************************************************************/
    public function initListeningList(){
        $this->getComponent('copyright')->listen('color');
    }
    
    /***********************************************************************
    * PUSH COMPONENTS DATA
    ************************************************************************/
    
    //* Envoie les champs aux components
    public function pushComponentsData(){
        // Fond généré
        $this->pushParameter('background', 'base-color', $this->getField('background-base'));
        $this->pushParameter('background', 'capabox-color', $this->getField('background-capabox'));
        // Fond personnalisé
        $this->pushParameter('background', 'file', $this->getField('background-custom'));
        // Illustration
        $this->pushParameter('illustration', 'file', $this->getField('illustration'));
        $this->pushParameter('illustration', 'crop-x', $this->getField('illuscrop-x'));
        $this->pushParameter('illustration', 'crop-y', $this->getField('illuscrop-y'));
        $this->pushParameter('illustration', 'crop-w', $this->getField('illuscrop-w'));
        $this->pushParameter('illustration', 'crop-h', $this->getField('illuscrop-h'));
        // Titre et type
        $this->pushParameter('title', 'text', $this->getField('title'));
        $this->pushParameter('type', 'text', $this->getField('type'));
        $this->pushParameter('titleshadow', 'text', $this->getField('title'));
        $this->pushParameter('typeshadow', 'text', $this->getField('type'));
        // Coût de mana
        $this->pushParameter('cm', 'text', $this->getField('cm'));
        // Capacité / TA
        $this->pushParameter('capabox', 'textcapa', $this->getField('capa'));
        $this->pushParameter('capabox', 'textta', $this->getField('ta'));
        // F/E
        $this->pushParameter('fe', 'text', $this->getField('fe'));
        $this->pushParameter('feshadow', 'text', $this->getField('fe'));
        // Illustrator
        $this->pushParameter('illus', 'text', $this->getField('illustrator'));
        $this->pushParameter('illusshadow', 'text', $this->getField('illustrator'));
        // Copyright
        $this->pushParameter('copyright', 'text', $this->getField('copyright'));
        // Extension symbol
        $this->pushParameter('se', 'name', $this->getField('se-extension'));
        $this->pushParameter('se', 'rarity', $this->getField('se-rarity'));
        $this->pushParameter('se', 'file', $this->getField('se-custom'));
    }
    
    /***********************************************************************
    * BEFORE INIT
    ************************************************************************/
    //* Dernières vérificatiosn avant l'inition
    public function beforeInit(){
        // Dans le cas du fond personnalisé, il faut précharger l'image pour connaître la taille du canvas
        if($this->_customBackground){
            $this->getComponent('background')->loadImage();
        }
    }
}

FcmOldBasic::staticInitDefaultFields();
FcmOldBasic::staticInitDefaultParameters();
