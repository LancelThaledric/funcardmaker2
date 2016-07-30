<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmModernPlaneswalker3BorderComponent.php');
require_once('include/FcmModernPlaneswalker3TextureComponent.php');
require_once('include/FcmModernPlaneswalker3EdgingComponent.php');
require_once('include/FcmBackgroundLayerComponent.php');
require_once('include/FcmSingleLineComponent.php');
require_once('include/FcmManaCostComponent.php');
require_once('include/FcmExtensionSymbolComponent.php');
require_once('include/FcmModernPlaneswalker3IllustrationComponent.php');
require_once('include/FcmImageComponent.php');
require_once('include/FcmLoyaltyCostComponent.php');
require_once('include/FcmMultiCapaboxComponent.php');

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
            'se' => new FcmExtensionSymbolComponent($this, 100),
            'illustration' => new FcmModernPlaneswalker3IllustrationComponent($this, 50),
            'illustration-frame' => new FcmImageComponent($this, 70),
            'capabox-frame' => new FcmImageComponent($this, 70),
            'capabox-background' => new FcmImageComponent($this, 60),
            'loyalty1' => new FcmLoyaltyCostComponent($this, 100),
            'loyalty2' => new FcmLoyaltyCostComponent($this, 100),
            'loyalty3' => new FcmLoyaltyCostComponent($this, 100),
            'loyalty-base' => new FcmLoyaltyCostComponent($this, 100),
            'capabox' => new FcmMultiCapaboxComponent($this, 100)
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
            'se' => [
                'x' => 718. / 791. * 100,
                'y' => 647. / 1107. * 100,
                'h' => 42. / 1107. * 100
            ],
            'illustration' => [
                'x' => 64. / 791. * 100,
                'y' => 113. / 1107. * 100,
                'w' => 665. / 791. * 100,
                'h' => 890. / 1107. * 100
            ],
            'illustration-frame' => [
                'x' => 63. / 791. * 100,
                'y' => 112. / 1107. * 100,
                'w' => 667. / 791. * 100,
                'h' => 522. / 1107. * 100,
                'category' => 'background',
                'name' => 'illus-frame'
            ],
            'capabox-frame' => [
                'x' => 104. / 791. * 100,
                'y' => 702. / 1107. * 100,
                'w' => 620. / 791. * 100,
                'h' => 302. / 1107. * 100,
                'category' => 'background',
                'name' => 'capabox-frame'
            ],
            'capabox-background' => [
                'x' => 105. / 791. * 100,
                'y' => 703. / 1107. * 100,
                'w' => 618. / 791. * 100,
                'h' => 300. / 1107. * 100,
                'category' => 'background',
                'name' => 'capabox-background'
            ],
            'loyalty1' => [
                'x' => 89. / 791. * 100,
                'y' => 759. / 1107. * 100,
                'fontsize' => 32. / 36.
            ],
            'loyalty2' => [
                'x' => 89. / 791. * 100,
                'y' => 859. / 1107. * 100,
                'fontsize' => 32. / 36.
            ],
            'loyalty3' => [
                'x' => 89. / 791. * 100,
                'y' => 954. / 1107. * 100,
                'fontsize' => 32. / 36.
            ],
            'loyalty-base' => [
                'x' => 691. / 791. * 100,
                'y' => 1029. / 1107. * 100,
                'fontsize' => 46. / 36.,
                'imagewidth' => 115. / 791. * 100,
                'direction' => 'base',
                'dots' => false
            ],
            'capabox' => [
                'x1' => 154. / 791. * 100,
                'y1' => 713. / 1107. * 100,
                'w1' => 559. / 791. * 100,
                'h1' => 80. / 1107. * 100,
                
                'x2' => 154. / 791. * 100,
                'y2' => 808. / 1107. * 100,
                'w2' => 559. / 791. * 100,
                'h2' => 85. / 1107. * 100,
                
                'x3' => 154. / 791. * 100,
                'y3' => 905. / 1107. * 100,
                'w3' => 559. / 791. * 100,
                'h3' => 93. / 1107. * 100,
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
        // Extension symbol
        $this->pushParameter('se', 'name', $this->getField('se-extension'));
        $this->pushParameter('se', 'rarity', $this->getField('se-rarity'));
        $this->pushParameter('se', 'file', $this->getField('se-custom'));
        // Illustration
        $this->pushParameter('illustration', 'file', $this->getField('illustration'));
        $this->pushParameter('illustration', 'crop-x', $this->getField('illuscrop-x'));
        $this->pushParameter('illustration', 'crop-y', $this->getField('illuscrop-y'));
        $this->pushParameter('illustration', 'crop-w', $this->getField('illuscrop-w'));
        $this->pushParameter('illustration', 'crop-h', $this->getField('illuscrop-h'));
        // Loyalties
        $this->pushParameter('loyalty1', 'text', $this->getField('capa1-cost'));
        $this->pushParameter('loyalty2', 'text', $this->getField('capa2-cost'));
        $this->pushParameter('loyalty3', 'text', $this->getField('capa3-cost'));
        $this->pushParameter('loyalty-base', 'text', $this->getField('loyalty-base'));
        // Capacités
        $this->pushParameter('capabox', 'text1', $this->getField('capa1'));
        $this->pushParameter('capabox', 'text2', $this->getField('capa2'));
        $this->pushParameter('capabox', 'text3', $this->getField('capa3'));
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
