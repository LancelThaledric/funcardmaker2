<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmModernPlaneswalker3BorderComponent.php');
require_once('include/FcmModernPlaneswalker3TextureComponent.php');
require_once('include/FcmModernPlaneswalker3EdgingComponent.php');
require_once('include/FcmBackgroundLayerComponent.php');
require_once('include/FcmSingleLineComponent.php');
require_once('include/FcmManaCostComponent.php');

//* Template Moderne Planeswalker 3 capacités

class FcmModernPlaneswalker3 extends FcmFuncard {
    
    //* Constructeur
    public function __construct($data = null){
        parent::__construct(self::DEFAULT_WIDTH, self::DEFAULT_HEIGHT, $data);
    }
    
    /***********************************************************************
    * NOM DU TEMPLATE
    ************************************************************************/
    protected static $_templateName = 'modern-planeswalker3';
    
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
            'background-texture' => 'r',
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
            'cm' => new FcmManaCostComponent($this, 100),
        ];
        
        // Gestion du fond
        $this->_customBackground = $this->hasField('background-custom');
        if($this->_customBackground){
            $components['background'] = new FcmCustomBackgroundComponent($this, 0);
        } else {
            $components['background'] = new FcmModernPlaneswalker3TextureComponent($this, 0);
            $components['edging'] = new FcmBackgroundLayerComponent($this, 5);
            $components['titlebox'] = new FcmBackgroundLayerComponent($this, 10);
            $components['typebox'] = new FcmBackgroundLayerComponent($this, 10);
            $components['border'] = new FcmModernPlaneswalker3BorderComponent($this, 1);
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
                'x' => 44. / 791. * 100,
                'y' => 44. / 1107. * 100,
                'w' => 706. / 791. * 100,
                'h' => 988. / 1107. * 100,
                'type' => 'edging',
            ],
            'titlebox' => [
                'x' => 57. / 791. * 100,
                'y' => 49. / 1107. * 100,
                'w' => 679. / 791. * 100,
                'h' => 58. / 1107. * 100,
                'type' => 'titlebox',
            ],
            'typebox' => [
                'x' => 57. / 791. * 100,
                'y' => 641. / 1107. * 100,
                'w' => 679. / 791. * 100,
                'h' => 54. / 1107. * 100,
                'type' => 'typebox',
            ],
            'title' => [
                'x' => 73. / 791. * 100,
                'y' => 93. / 1107. * 100,
                'size' => 48. / 36.,
            ],
            'type' => [
                'x' => 82. / 791. * 100,
                'y' => 680. / 1107. * 100,
                'size' => 40. / 36.,
            ],
            'cm' => [
                'x' => 725. / 791. * 100,
                'y' => 59. / 1107. * 100,
                'size' => 44. / 36.,
                'shadowx' => -1. / 791. * 100,
                'shadowy' => 5. / 1107. * 100,
                'largeManaOffset' => -4. / 1107. * 100
            ],
        ];
    }
    
    /***********************************************************************
    * LISTENING LIST
    ************************************************************************/
    public function initListeningList(){
        $this->getComponent('edging')->listen('name');
        $this->getComponent('edging')->listen('method');
        $this->getComponent('titlebox')->listen('method');
        $this->getComponent('typebox')->listen('method');
    }
    
    /***********************************************************************
    * PUSH COMPONENTS DATA
    ************************************************************************/
    //* Envoie les champs aux components
    public function pushComponentsData(){
        // Fond généré
        $this->pushParameter('background', 'name', $this->getField('background-texture'));
        $this->pushParameter('edging', 'name', $this->getField('background-edging'));
        $this->pushParameter('titlebox', 'name', $this->getField('background-boxes'));
        $this->pushParameter('typebox', 'name', $this->getField('background-boxes'));
        // Fond personnalisé
        $this->pushParameter('background', 'file', $this->getField('background-custom'));
        // Titre et type
        $this->pushParameter('title', 'text', $this->getField('title'));
        $this->pushParameter('type', 'text', $this->getField('type'));
        // Mana cost
        $this->pushParameter('cm', 'text', $this->getField('cm'));
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

FcmModernPlaneswalker3::staticInitDefaultFields();
FcmModernPlaneswalker3::staticInitDefaultParameters();
