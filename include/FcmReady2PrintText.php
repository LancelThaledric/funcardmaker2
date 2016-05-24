<?php

require_once('include/FcmTextNugget.php');

/**
 * Afficheur de texte multiligne. Utilisé par des composants comme FcmMultiLineText
 */

class FcmReady2PrintText{
    
    /** Array des lignes du texte à afficher. Les retours à la lignes ont déjà été calculés grâce aux données fournies au constructeur **/
    private $lines;
    
    /**
     * Constructeur.
     * @param $text le texte à traiter
     * @param $font la police à utiliser
     * @param $fontsize la taille de police à utiliser
     * @param $width la largeur de la boîte de texte.
     */
    public function __construct($text, $font, $fontsize, $width) {
        // TODO
    }
    
    public function printText(){
        // TODO
    }
    
}




/**
 * Curseur utilisé pour l'affichage et le calcul de géomtrie de texte
 */

class FcmTextCursor{
    
    public $x = 0, $y = 0;
    public $lineHeight = 1., $jumpLineHeight = 2.; // TODO revoir l'espacement des lignes et des paragraphes
    
}


/**
 * Une ligne de texte. C'est un array content plusieurs FcmTextNugget
 */
class FcmTextLine{
    
    /** Array des nuggets de texte **/
    private $nuggets;
    
}