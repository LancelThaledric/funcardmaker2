<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmSingleLineComponent.php');
require_once('include/FcmBorderComponent.php');
require_once('include/FcmBackgroundLayerComponent.php');
require_once('include/FcmCustomBackgroundComponent.php');
require_once('include/FcmIllustrationComponent.php');
require_once('include/FcmCapaboxComponent.php');
require_once('include/FcmManaCostComponent.php');
require_once('include/FcmExtensionSymbolComponent.php');
require_once('include/FcmIllustratorComponent.php');
require_once('include/FcmModernBasicFEBoxComponent.php');

//* Template Modern Basic

class FcmModernBasic extends FcmFuncard {
    
    //* Constructeur
    public function __construct($data = null){
        parent::__construct(self::DEFAULT_WIDTH, self::DEFAULT_HEIGHT, $data);
    }
    
    /***********************************************************************
    * NOM DU TEMPLATE
    ************************************************************************/
    protected static $_templateName = 'modern-basic';
    
    /***********************************************************************
    * TAILLE DU TEMPLATE
    ************************************************************************/
    const DEFAULT_WIDTH = 791;
    const DEFAULT_HEIGHT = 1107;
    
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
            $components['background'] = new FcmCustomBackgroundComponent($this, 0);
        } else {
            $components['background'] = new FcmBackgroundLayerComponent($this, 0);
            $components['edging'] = new FcmBackgroundLayerComponent($this, 1);
            $components['titlebox'] = new FcmBackgroundLayerComponent($this, 2);
            $components['typebox'] = new FcmBackgroundLayerComponent($this, 2);
            $components['border'] = new FcmBorderComponent($this, 10);
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
            'border' => [],
            'background' => [
                'method' => 'horizontal',
                'type' => 'base',
                'x' => 41. / 791. * 100,
                'y' => 41. / 1107. * 100,
                'w' => 709. / 791. * 100,
                'h' => 1025. / 1107. * 100,
            ],
            'edging' => [
                'x' => 50. / 791. * 100,
                'y' => 55. / 1107. * 100,
                'w' => 691. / 791. * 100,
                'h' => 946. / 1107. * 100,
                'type' => 'edging'
            ],
            'titlebox' => [
                'x' => 56. / 791. * 100,
                'y' => 61. / 1107. * 100,
                'w' => 679. / 791. * 100,
                'h' => 64. / 1107. * 100,
                'type' => 'titlebox'
            ],
            'typebox' => [
                'x' => 60. / 791. * 100,
                'y' => 621. / 1107. * 100,
                'w' => 671. / 791. * 100,
                'h' => 62. / 1107. * 100,
                'type' => 'typebox'
            ],
            'title' => [
                'x' => 73. / 791. * 100,
                'y' => 108. / 1107. * 100,
                'size' => 48. / 36.
            ],
            'type' => [
                'x' => 80. / 791. * 100,
                'y' => 664. / 1107. * 100,
                'size' => 40. / 36.
            ],
            'capabox' => [
                'x' => 79. / 791. * 100,
                'y' => 700. / 1107. * 100,
                'w' => 632. / 791. * 100,
                'h' => 283. / 1107. * 100
            ],
            'cm' => [
                'x' => 723. / 791. * 100,
                'y' => 74. / 1107. * 100,
                'size' => 44. / 36.,
                'shadowx' => -1. / 791. * 100,
                'shadowy' => 5. / 1107. * 100,
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
    
    /***********************************************************************
    * LISTENING LIST
    ************************************************************************/
    public function initListeningList(){
        $this->getComponent('illus')->listen('color');
        $this->getComponent('illus')->listen('altcolor');
        $this->getComponent('copyright')->listen('color');
        $this->getComponent('fe')->listen('color');
    }
    
    /***********************************************************************
    * PUSH COMPONENTS DATA
    ************************************************************************/
    //* Envoie les champs aux components en tant que paramètres
    public function pushComponentsData(){
        
        // Fond personnalisé
        if($this->_customBackground){
            $this->pushParameter('background', 'file', $this->getField('background-custom'));
        }
        // Fond généré
        else {
            $this->pushParameter('background', 'name', $this->getField('background-base'));
            $this->pushParameter('edging', 'name', $this->getField('background-edging'));
            $this->pushParameter('titlebox', 'name', $this->getField('background-boxes'));
            $this->pushParameter('typebox', 'name', $this->getField('background-boxes'));
        }
        
        // Titre et type
        $this->pushParameter('title', 'text', $this->getField('title'));
        $this->pushParameter('type', 'text', $this->getField('type'));
        // Illustration
        $this->pushParameter('illustration', 'file', $this->getField('illustration'));
        $this->pushParameter('illustration', 'crop-x', $this->getField('illuscrop-x'));
        $this->pushParameter('illustration', 'crop-y', $this->getField('illuscrop-y'));
        $this->pushParameter('illustration', 'crop-w', $this->getField('illuscrop-w'));
        $this->pushParameter('illustration', 'crop-h', $this->getField('illuscrop-h'));
        // Capacité / TA
        $this->pushParameter('capabox', 'textcapa', $this->getField('capa'));
        $this->pushParameter('capabox', 'textta', $this->getField('ta'));
        // Mana cost
        $this->pushParameter('cm', 'text', $this->getField('cm'));
        // Extension symbol
        $this->pushParameter('se', 'name', $this->getField('se-extension'));
        $this->pushParameter('se', 'rarity', $this->getField('se-rarity'));
        $this->pushParameter('se', 'file', $this->getField('se-custom'));
        // Illustrator
        $this->pushParameter('illus', 'text', $this->getField('illustrator'));
        // Copyright
        $this->pushParameter('copyright', 'text', $this->getField('copyright'));
        // F/E
        $this->pushParameter('fe', 'text', $this->getField('fe'));
    }
    
    /***********************************************************************
    * BEFORE INIT
    ************************************************************************/
    //* Dernières vérifications avant l'inition
    public function beforeInit(){
        // Dans le cas du fond personnalisé, il faut précharger l'image pour connaître la taille du canvas
        if($this->_customBackground){
            $this->getComponent('background')->loadImage();
        }
    }
}

FcmModernBasic::staticInitDefaultFields();
FcmModernBasic::staticInitDefaultParameters();
