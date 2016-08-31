<?php

require_once('include/FcmFuncard.php');

require_once('include/FcmFuncardComponent.php');
require_once('include/FcmModernPlaneswalker3BorderComponent.php');
require_once('include/FcmModernPlaneswalker3TextureComponent.php');
require_once('include/FcmBackgroundLayerComponent.php');
require_once('include/FcmSingleLineComponent.php');
require_once('include/FcmSingleLineTitleComponent.php');
require_once('include/FcmManaCostComponent.php');
require_once('include/FcmExtensionSymbolComponent.php');
require_once('include/FcmModernPlaneswalker3IllustrationComponent.php');
require_once('include/FcmImageComponent.php');
require_once('include/FcmLoyaltyCostComponent.php');
require_once('include/FcmMultiCapaboxComponent.php');
require_once('include/FcmModernPlaneswalker3IllustratorComponent.php');

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
            'title' => new FcmSingleLineTitleComponent($this, 100),
            'type' => new FcmSingleLineTitleComponent($this, 100),
            'cm' => new FcmManaCostComponent($this, 90),
            'se' => new FcmExtensionSymbolComponent($this, 90),
            'illustration' => new FcmModernPlaneswalker3IllustrationComponent($this, 50),
            'illustration-frame' => new FcmImageComponent($this, 70),
            'loyalty1' => new FcmLoyaltyCostComponent($this, 100),
            'loyalty2' => new FcmLoyaltyCostComponent($this, 100),
            'loyalty3' => new FcmLoyaltyCostComponent($this, 100),
            'loyalty-base' => new FcmLoyaltyCostComponent($this, 100),
            'capabox' => new FcmMultiCapaboxComponent($this, 100),
            'illus' => new FcmModernPlaneswalker3IllustratorComponent($this, 100),
            'copyright' => new FcmSingleLineComponent($this, 100)
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
            $components['capabox-frame'] = new FcmImageComponent($this, 70);
            $components['capabox-background'] = new FcmImageComponent($this, 60);
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
                'y' => 52. / 1107. * 100,
                'w' => 649. / 791. * 100,
                'h' => 54. / 1107. * 100,
                'size' => 48. / 36.,
            ],
            'type' => [
                'x' => 82. / 791. * 100,
                'y' => 642. / 1107. * 100,
                'w' => 636. / 791. * 100,
                'h' => 53. / 1107. * 100,
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
                'x' => 690. / 791. * 100,
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
            'illus' => [
                'x' => 395. / 791. * 100,
                'y' => 1039. / 1107. * 100,
                'size' => 29. / 36.,
                'brushsize' => 26. / 36.,
                'color' => 'white',
                'altcolor' => 'none'
            ],
            'copyright' => [
                'x' => 395. / 791. * 100,
                'y' => 1060. / 1107. * 100,
                'size' => 18. / 36.,
                'color' => 'white',
                'font' => 'mplantin',
                'align' => 'center'
            ]
        ];
    }
    
    /***********************************************************************
    * LISTENING LIST
    ************************************************************************/
    public function initListeningList(){
        if(!$this->_customBackground){
            // Prononcé par FcmModernPlaneswalker3TextureComponent
            $this->getComponent('edging')->listen('name');
            $this->getComponent('edging')->listen('method');
            $this->getComponent('titlebox')->listen('method');
            $this->getComponent('typebox')->listen('method');
        }
        
        // Prononcé par FcmManaCostComponent
        $this->getComponent('title')->listen('marginright');
        
        // Prononcé par FcmExtensionSymbolComponent
        $this->getComponent('type')->listen('marginright');
    }
    
    /***********************************************************************
    * PUSH COMPONENTS DATA
    ************************************************************************/
    //* Envoie les champs aux components
    public function pushComponentsData(){
        
        // Fond personnalisé
        if($this->_customBackground){
            $this->userParameter('background', 'file', $this->getField('background-custom'));
        }
        // Fond généré
        else{
            $this->userParameter('background', 'name', $this->getField('background-texture'));
            $this->userParameter('edging', 'name', $this->getField('background-edging'));
            $this->userParameter('titlebox', 'name', $this->getField('background-boxes'));
            $this->userParameter('typebox', 'name', $this->getField('background-boxes'));
        }
        
        // Titre et type
        $this->userParameter('title', 'text', $this->getField('title'));
        $this->userParameter('type', 'text', $this->getField('type'));
        // Mana cost
        $this->userParameter('cm', 'text', $this->getField('cm'));
        // Extension symbol
        $this->userParameter('se', 'name', $this->getField('se-extension'));
        $this->userParameter('se', 'rarity', $this->getField('se-rarity'));
        $this->userParameter('se', 'file', $this->getField('se-custom'));
        // Illustration
        $this->userParameter('illustration', 'file', $this->getField('illustration'));
        $this->userParameter('illustration', 'crop-x', $this->getField('illuscrop-x'));
        $this->userParameter('illustration', 'crop-y', $this->getField('illuscrop-y'));
        $this->userParameter('illustration', 'crop-w', $this->getField('illuscrop-w'));
        $this->userParameter('illustration', 'crop-h', $this->getField('illuscrop-h'));
        // Loyalties
        $this->userParameter('loyalty1', 'text', $this->getField('capa1-cost'));
        $this->userParameter('loyalty2', 'text', $this->getField('capa2-cost'));
        $this->userParameter('loyalty3', 'text', $this->getField('capa3-cost'));
        $this->userParameter('loyalty-base', 'text', $this->getField('loyalty-base'));
        // Capacités
        $this->userParameter('capabox', 'text1', $this->getField('capa1'));
        $this->userParameter('capabox', 'text2', $this->getField('capa2'));
        $this->userParameter('capabox', 'text3', $this->getField('capa3'));
        $this->userParameter('capabox', 'title', $this->getField('title'));
        // Illustrator
        $this->userParameter('illus', 'text', $this->getField('illustrator'));
        // Copyright
        $this->userParameter('copyright', 'text', $this->getField('copyright'));
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
