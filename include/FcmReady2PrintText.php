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
    
    /**
     * Génère la liste des lignes à partir d'un texte
     */
    public static function text2Lines($text){
        
        $array = preg_split('#(\R+)#m', $text, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        // On a séparé les lignes. On transforme les lignes en FcmTextLine
        $array = array_map('FcmTextLine::createNuggets', $array);
        
        return $array;
    }
    
    /**
     * Crée un tableau de nuggets par ligne
     */
    public static function createNuggets($text){
        
        $nuggets = self::splitNuggets($text);
        $nuggets = array_map('FcmAbstractTextNugget::createNugget', $nuggets);
        
        return new FcmTextLine($nuggets);
    }
    
    /**
     * Découpe le texte sélectionné en plusieurs nuggets
     */
    public static function splitNuggets($text){
        
        $regex = FcmManaNugget::$regex;
        return preg_split($regex, $text, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
    }
    
    /**
     * Constructor
     */
    public function __construct($nuggets){
        $this->_nuggets = $nuggets;
    }
    
    /** Array des nuggets de texte **/
    private $_nuggets;
    
}