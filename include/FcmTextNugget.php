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
            return new FcmManaNugget(trim($text, '{}'));
        
        if(preg_match(FcmNewParagraphNugget::$regex, $text))
            return new FcmNewParagraphNugget();
        
        if(preg_match(FcmNewLineNugget::$regex, $text))
            return new FcmNewLineNugget();
        
        return new FcmTextNugget($text);
        
    }
    
}



/**
 * TextNugget de type texte
 */
class FcmTextNugget extends FcmAbstractTextNugget{
    
    private $text;
    
    public function __construct($t){
        $this->text = $t;
    }
    
}



/**
 * TextNugget de type mana
 */
class FcmManaNugget extends FcmAbstractTextNugget{
    
    public static $regex = '#(\{.+\})#U';
    
    private $text;
    
    public function __construct($t){
        $this->text = $t;
    }
    
}

/**
 * TextNugget de type Nouvelle ligne
 */
class FcmNewLineNugget extends FcmAbstractTextNugget{
    
    public static $regex = '#^(\R)$#m';
    
}

/**
 * TextNugget de type Nouveau paragraphe
 */
class FcmNewParagraphNugget extends FcmAbstractTextNugget{
    
    public static $regex = '#^(\R{2,})$#m';
    
}

/**
 * TextNugget de type Nouvelle section.
 * Sert notamment à séparer les capacité du texte d'ambiance.
 */
class FcmNewSectionNugget extends FcmAbstractTextNugget{
    
    public static $regex = null;
    
}