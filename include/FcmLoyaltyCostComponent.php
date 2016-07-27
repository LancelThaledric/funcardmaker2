<?php

require_once('include/FcmSingleLineComponent.php');

/**
 * Component affichant un coût de loyauté
 *
 * Parameters : 
 * - x : position x (text-related)
 * - y : position y (text-related)
 * - color : text color
 * - fontsize : font size in em basesize
 * - imagewidth : image width in px (height is auto-calculated)
 * - text : text to display
 * - font : font name
 * - direction : "up", "down", "none", or "base".
 * - dots : true or false. Default true. Displays dots after le loyalty.
 */

class FcmLoyaltyCostComponent extends FcmFuncardComponent {
    
    // self::$draw est disponible par héritage
    
    public static $OFFSETS_Y = null;
    public static $OFFSETS_X = null;
    
    public static $DOTS_OFFSET_X = null;
    public static $DOTS_OFFSET_Y = null;
    
    public static $BASE_OFFSET_Y_MULTICHAR = null;
    
    public static function static_init(){
        self::$OFFSETS_Y = [
            'up' => 49. / 77.,
            'down' => 38. / 77.,
            'none' => 0.5846,
            'base' => 0.7671
        ];
        self::$OFFSETS_X = [
            'up' => 0.5288,
            'down' => 0.5288,
            'none' => 0.5288,
            'base' => 0.5217
        ];
        
        self::$DOTS_OFFSET_X = 64. / 1107. * 100;
        self::$DOTS_OFFSET_Y = 0;
        
        self::$BASE_OFFSET_Y_MULTICHAR = -3. / 1107. * 100;
    }
    
    
    private $_resource = null; // Image de loyauté utilisée
    private $_dotsComponent = null; // Composant de dots
    
    public function __construct($funcard, $priority = 0) {
        parent::__construct($funcard, $priority);
    }
    
    public function configure(){
        
        if($this->getParameter('text') === '') return;
        $text = $this->getParameter('text');
        
        // 1 : On auto-détermine la direction si elle n'est pas donnée
        if(empty($this->getParameter('direction'))){
            if($text[0] === '+') $this->setParameter('direction', 'up');
            elseif($text[0] === '-') $this->setParameter('direction', 'down');
            else $this->setParameter('direction', 'none');
        }
        
        // 2 : On charge la ressource
        $imagewidth = $this->getFuncard()->xc($this->getParameter('imagewidth'));
        $imageheight = 'auto';
        $this->_resource = $this->getFuncard()->loadResource(
            'background',
            'loyalty',
            $this->getParameter('direction') . '.png',
            $imagewidth, $imageheight
        );
        
        // 3 : S'il faut afficher les dots, on configure le component
        if($this->getParameter('dots') == true){
            $this->_dotsComponent = new FcmSingleLineComponent($this->getFuncard());
            
            $x = $this->getParameter('x') + self::$DOTS_OFFSET_X;
            $y = $this->getParameter('y') + self::$DOTS_OFFSET_Y;
            
            $this->_dotsComponent->setParameter('x', $x);
            $this->_dotsComponent->setParameter('y', $y);
            $this->_dotsComponent->setParameter('color', 'black');
            $this->_dotsComponent->setParameter('size', 46. / 36. );
            $this->_dotsComponent->setParameter('text', ':');
            $this->_dotsComponent->setParameter('font', 'mplantin');
            
            $this->_dotsComponent->configure();
        }
    }
    
    public function apply(){
        //var_dump($this);
        if($this->getParameter('text') === '') return;
        if(!isset($this->_resource)) return;
        
        self::$draw->push();
        
        self::$draw->setFillColor($this->getParameter('color'));
        self::$draw->setFont(self::$fontManager->getFont($this->getParameter('font')));
        self::$draw->setFontSize($this->getFuncard()->fsc($this->getParameter('fontsize')));
        self::$draw->setTextAlignment(imagick::ALIGN_CENTER);
        
        $x = $this->getParameter('x');
        $y = $this->getParameter('y');
        
        $this->getFuncard()->getCanvas()->compositeImage(
            $this->_resource,
            Imagick::COMPOSITE_OVER,
            $this->getFuncard()->xc($x) - $this->_resource->getImageWidth() * self::$OFFSETS_X[$this->getParameter('direction')],
            $this->getFuncard()->yc($y) - $this->_resource->getImageHeight() * self::$OFFSETS_Y[$this->getParameter('direction')]
        );
        
        if($this->getParameter('direction') == 'base'
           && strlen($this->getParameter('text')) > 1)
            $y += self::$BASE_OFFSET_Y_MULTICHAR;
        
        $this->getFuncard()->getCanvas()->annotateImage(
            self::$draw,
            $this->getFuncard()->xc($x),
            $this->getFuncard()->yc($y),
            0,
            $this->getParameter('text')
        );
        
        if($this->getParameter('dots') == true){
            $this->_dotsComponent->apply();
        }
        
        self::$draw->pop();
    }
    
    public function setDefaultParameters(){
        $this->setParameter('color', 'white');
        $this->setParameter('font-size', 1);
        $this->setParameter('imagewidth', 104. / 791. * 100);
        $this->setParameter('text', '');
        $this->setParameter('direction', '');
        $this->setParameter('font', 'plantin-bold');
        $this->setParameter('dots', true);
    }
    
}

FcmLoyaltyCostComponent::static_init();
