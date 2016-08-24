<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmSingleLineComponent.php');
require_once('include/FcmSingleLineTitleComponent.php');
require_once('include/FcmBorderComponent.php');
require_once('include/FcmBackgroundLayerComponent.php');
require_once('include/FcmCustomBackgroundComponent.php');
require_once('include/FcmIllustrationComponent.php');
require_once('include/FcmCapaboxComponent.php');
require_once('include/FcmManaCostComponent.php');
require_once('include/FcmExtensionSymbolComponent.php');
require_once('include/FcmIllustratorComponent.php');
require_once('include/FcmModernBasicFEBoxComponent.php');
require_once('include/FcmModernBasicBackgroundBaseComponent.php');
require_once('include/FcmModernBasicBackgroundFeboxComponent.php');

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
            'title' => new FcmSingleLineTitleComponent($this, 100),
            'type' => new FcmSingleLineComponent($this, 100),
            'illustration' => new FcmIllustrationComponent($this, 100),
            'capabox' => new FcmCapaboxComponent($this, 100),
            'cm' => new FcmManaCostComponent($this, 50),
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
            $components['background'] = new FcmModernBasicBackgroundBaseComponent($this, 0);
            $components['edging'] = new FcmBackgroundLayerComponent($this, 1);
            $components['titlebox'] = new FcmBackgroundLayerComponent($this, 2);
            $components['typebox'] = new FcmBackgroundLayerComponent($this, 2);
            $components['febox'] = new FcmModernBasicBackgroundFeboxComponent($this, 2);
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
                'method' => 'horizontal'
            ],
            'edging' => [
                'x' => 50. / 791. * 100,
                'y' => 55. / 1107. * 100,
                'w' => 691. / 791. * 100,
                'h' => 946. / 1107. * 100,
                'type' => 'edging',
                'method' => 'horizontal'
            ],
            'titlebox' => [
                'x' => 56. / 791. * 100,
                'y' => 61. / 1107. * 100,
                'w' => 679. / 791. * 100,
                'h' => 64. / 1107. * 100,
                'type' => 'titlebox',
                'method' => 'horizontal'
            ],
            'typebox' => [
                'x' => 60. / 791. * 100,
                'y' => 621. / 1107. * 100,
                'w' => 671. / 791. * 100,
                'h' => 62. / 1107. * 100,
                'type' => 'typebox',
                'method' => 'horizontal'
            ],
            'febox' => [
                'method' => 'horizontal'
            ],
            'title' => [
                'x' => 73. / 791. * 100,
                'y' => 69. / 1107. * 100,
                'w' => 648. / 791. * 100,
                'h' => 49. / 1107. * 100,
                'size' => 48. / 36.,
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
        
        // Uniquement pour fond généré
        if(!$this->_customBackground){
            // Prononcé par FcmModernBasicBackgroundBaseComponent
            $this->getComponent('illus')->listen('color');
            $this->getComponent('illus')->listen('altcolor');
            $this->getComponent('copyright')->listen('color');
        }
        
        // Prononcé par FcmManaCostComponent
        $this->getComponent('title')->listen('marginright');
    }
    
    /***********************************************************************
    * PUSH COMPONENTS DATA
    ************************************************************************/
    //* Envoie les champs aux components en tant que paramètres
    public function pushComponentsData(){
        
        // Fond personnalisé
        if($this->_customBackground){
            $this->userParameter('background', 'file', $this->getField('background-custom'));
        }
        // Fond généré
        else {
            $this->userParameter('background', 'name', $this->getField('background-base'));
            $this->userParameter('edging', 'name', $this->getField('background-edging'));
            $this->userParameter('titlebox', 'name', $this->getField('background-boxes'));
            $this->userParameter('typebox', 'name', $this->getField('background-boxes'));
        }
        
        // Titre et type
        $this->userParameter('title', 'text', $this->getField('title'));
        $this->userParameter('type', 'text', $this->getField('type'));
        // Illustration
        $this->userParameter('illustration', 'file', $this->getField('illustration'));
        $this->userParameter('illustration', 'crop-x', $this->getField('illuscrop-x'));
        $this->userParameter('illustration', 'crop-y', $this->getField('illuscrop-y'));
        $this->userParameter('illustration', 'crop-w', $this->getField('illuscrop-w'));
        $this->userParameter('illustration', 'crop-h', $this->getField('illuscrop-h'));
        // Capacité / TA
        $this->userParameter('capabox', 'textcapa', $this->getField('capa'));
        $this->userParameter('capabox', 'textta', $this->getField('ta'));
        $this->userParameter('capabox', 'title', $this->getField('title'));
        // Mana cost
        $this->userParameter('cm', 'text', $this->getField('cm'));
        // Extension symbol
        $this->userParameter('se', 'name', $this->getField('se-extension'));
        $this->userParameter('se', 'rarity', $this->getField('se-rarity'));
        $this->userParameter('se', 'file', $this->getField('se-custom'));
        // Illustrator
        $this->userParameter('illus', 'text', $this->getField('illustrator'));
        // Copyright
        $this->userParameter('copyright', 'text', $this->getField('copyright'));
        // F/E
        $this->userParameter('fe', 'text', $this->getField('fe'));
        if(!$this->_customBackground){
            $this->userParameter('febox', 'name', $this->getField('background-base'));
            $this->userParameter('febox', 'name', $this->getField('background-edging'));
            $this->userParameter('febox', 'name', $this->getField('background-boxes'));
            $this->userParameter('febox', 'name', $this->getField('background-febox'));
        }
        if(empty($this->getField('fe'))){ // On masque la Febox si on n'a pas rentré de F/E
            $this->userParameter('febox', 'visible', false);
        }
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
