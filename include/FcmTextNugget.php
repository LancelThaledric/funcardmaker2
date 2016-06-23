<?php

/**
 * Portion de texte. Dans une ligne de texte, les TextNuggets s'enchainent.
 */
abstract class FcmAbstractTextNugget{
    
    // TODO
    
    /**
     * Transforme la chaine $text en Nugget du bon type
     */
    public static function createNugget($text){
        
        // on doit choisir le bon type de nugget
        if(preg_match(FcmManaNugget::$regex, $text))
            return new FcmManaNugget($text);
        
        if(preg_match(FcmNewParagraphNugget::$regex, $text))
            return new FcmNewParagraphNugget();
        
        if(preg_match(FcmNewLineNugget::$regex, $text))
            return new FcmNewLineNugget();
        
        if(preg_match(FcmBeginItalicNugget::$regex, $text))
            return new FcmBeginItalicNugget();
        
        if(preg_match(FcmEndItalicNugget::$regex, $text))
            return new FcmEndItalicNugget();
        
        return new FcmTextNugget($text);
        
    }
    
    /**
     * Génère les coordonnées X / Y à appliquer au cursor lors du rendu de la nugget
     */
    public abstract function getCursorUpdates($imagick, $draw, $metrics, $cursor);
    public abstract function render($imagick, &$draw, &$cursor, &$ready2Print);
    
}



/**
 * TextNugget de type texte
 */
class FcmTextNugget extends FcmAbstractTextNugget{
    
    private $_text;
    
    public function getText() { return $this->_text; }
    
    public function __construct($t){
        $this->_text = $t;
    }
    
    public function getCursorUpdates($imagick, $draw, $metrics, $cursor){
        $customMetrics = $imagick->queryFontMetrics($draw, $this->_text);
        return [
            'x' => $customMetrics['textWidth'],
            'y' => 0
        ];
    }
    
    public function render($imagick, &$draw, &$cursor, &$ready2Print){
        // On dessine le texte!
        $imagick->annotateImage($draw,
                                $cursor->x,
                                $cursor->y,
                                0,
                                $this->_text);
        //var_dump('displaying "'.$this->_text.'" at '.$cursor->x.'/'.$cursor->y);
    }
    
}



/**
 * TextNugget de type mana
 */
class FcmManaNugget extends FcmAbstractTextNugget{
    
    public static $regex = '#((\{\w+\})+)#';
    
    private $_text;
    
    public function getText() { return $this->_text; }
    
    public function __construct($t){
        $this->_text = $t;
    }
    
    public function getCursorUpdates($imagick, $draw, $metrics, $cursor){
        $customMetrics = $imagick->queryFontMetrics($draw, $this->_text);
        return [
            'x' => $customMetrics['textWidth'],
            'y' => 0
        ];
    }
    
    public function render($imagick, &$draw, &$cursor, &$ready2Print){
        // TODO
    }
}

/**
 * TextNugget de type Nouvelle ligne
 */
class FcmNewLineNugget extends FcmAbstractTextNugget{
    
    public static $regex = '#^(\R)$#m';
    
    public function getCursorUpdates($imagick, $draw, $metrics, $cursor){
        return [
            'x' => 0,
            'y' => $metrics['characterHeight'] * $cursor->lineHeight
        ];
    }
    
    public function render($imagick, &$draw, &$cursor, &$ready2Print){
        // Nothing
    }
    
}

/**
 * TextNugget de type Nouveau paragraphe
 */
class FcmNewParagraphNugget extends FcmAbstractTextNugget{
    
    public static $regex = '#^(\R{2,})$#m';
    
    public function getCursorUpdates($imagick, $draw, $metrics, $cursor){
        return [
            'x' => 0,
            'y' => $metrics['characterHeight'] * $cursor->newParagraphHeight
        ];
    }
    
    public function render($imagick, &$draw, &$cursor, &$ready2Print){
        // Nothing
    }
    
}

/**
 * TextNugget de type Nouvelle section.
 * Sert notamment à séparer les capacité du texte d'ambiance.
 */
class FcmNewSectionNugget extends FcmAbstractTextNugget{
    
    public static $regex = null;
    
    public function getCursorUpdates($imagick, $draw, $metrics, $cursor){
        return [
            'x' => 0,
            'y' => $metrics['characterHeight'] * $cursor->newSectionHeight
        ];
    }
    
    public function render($imagick, &$draw, &$cursor, &$ready2Print){
        // Nothing
    }
    
}

/**
 * TextNugget de type Begin Italique.
 * Sert activer le texte italique.
 */
class FcmBeginItalicNugget extends FcmAbstractTextNugget{
    
    public static $regex = '#(<i>)#';
    
    public function getCursorUpdates($imagick, $draw, $metrics, $cursor){
        return [
            'x' => 0,
            'y' => 0
        ];
    }
    
    public function render($imagick, &$draw, &$cursor, &$ready2Print){
        $ready2Print->setItalicMode(true);
    }
    
}

/**
 * TextNugget de type Begin Italique.
 * Sert activer le texte italique.
 */
class FcmEndItalicNugget extends FcmAbstractTextNugget{
    
    public static $regex = '#(</i>)#';
    
    public function getCursorUpdates($imagick, $draw, $metrics, $cursor){
        return [
            'x' => 0,
            'y' => 0
        ];
    }
    
    public function render($imagick, &$draw, &$cursor, &$ready2Print){
        $ready2Print->setItalicMode(false);
    }
    
}