<?php

require_once('include/FcmTextNugget.php');

/**
 * Afficheur de texte multiligne. Utilisé par des composants comme FcmMultiLineText
 */

class FcmReady2PrintText{
    
    /** Array des nuggets du texte à afficher. **/
    private $_nuggets;
    private $_font;
    private $_fontsize;
    private $_width, $_height;
    private $_charMetrics;  //line height is ['chararcterHeight']
    private $_imagick, $_imagickdraw;
    
    public function getHeight() { return $this->_height; }
    
    /**
     * Constructeur.
     * @param $nuggets le texte à traiter, découpé en FcmTextLines
     * @param $font la police à utiliser
     * @param $fontsize la taille de police à utiliser en px.
     * @param $width la largeur de la boîte de texte en px.
     */
    public function __construct($nuggets, $font, $fontsize, $width) {
        $this->_nuggets = $nuggets;
        $this->_font = FcmMultiLineComponent::$fontManager->getFont($font);
        $this->_fontsize = $fontsize;
        $this->_width = $width;
        $this->_height = null;
        
        $this->_imagick = new Imagick();
        $this->_imagickdraw = new ImagickDraw();
        
        $this->_imagickdraw->setFont(realpath($this->_font));
        $this->_imagickdraw->setFontSize($this->_fontsize);
        $this->_charMetrics = $this->_imagick->queryFontMetrics($this->_imagickdraw, 'xD');
    }
    
    public function printText(){
        // TODO
    }
    
    /**
     * Effectue les tâches de pré-rendu : place les retours à ligne aux bons endroits
     * et calcule la hauteur totale du texte.
     */
    public function preRender(){
        $cursor = new FcmTextCursor();
        var_dump($this->_charMetrics);
        
        $count = count($this->_nuggets);
        for($i = 0 ; $i < $count ; ++$i){
            $this->preRenderNugget($i);
        }
    }
    
    /**
     * Effectue le pré-rendu de la nugget numéro i (démarre à 0)
     */
    private function preRenderNugget($i){
        var_dump($this->_nuggets[$i]);
        
    }
    
    
    
}




/**
 * Curseur utilisé pour l'affichage et le calcul de géomtrie de texte
 */

class FcmTextCursor{
    
    public $x = 0, $y = 0;
    public $lineHeight = 1., $newLParagraphHeight = 2., $newSectionHeight = 3.; // TODO revoir l'espacement des lignes et des paragraphes
    
}

