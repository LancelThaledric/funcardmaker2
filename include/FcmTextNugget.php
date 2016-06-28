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
    public abstract function render($imagick, &$draw, &$cursor, $ready2Print, $metrics);
    
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
    
    public function render($imagick, &$draw, &$cursor, $ready2Print, $metrics){
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
    
    public static $regex = '#((\{[a-zA-Z0-9/]+\})+)#';
    public static $parseRegex = '#(\w/\w|2/\w|p\w|o+\w|\d+|chaos|\w)#'; // Note : le \w seul doit impérativement être à la fin de la regex. Les recherches se font dans l'ordre.
    public static $largeManaRegex = '#\w/\w|2/\w|p\w#';
    
    public static $existingManas = array(
        // Monocolor manas
        'w' => 'w.png',
        'u' => 'u.png',
        'b' => 'b.png',
        'r' => 'r.png',
        'g' => 'g.png',
        // Mono manas divers
        'c' => 'c.png',
        's' => 's.png',
        'ow' => 'oldw.png',
        // Tap manas
        't' => 'tap.png',
        'ot' => 'oldtap.png',
        'oot' => 'oldesttap.png',
        'q' => 'untap.png',
        // Number manas
        '0' => '0.png',
        '1' => '1.png',
        '2' => '2.png',
        '3' => '3.png',
        '4' => '4.png',
        '5' => '5.png',
        '6' => '6.png',
        '7' => '7.png',
        '8' => '8.png',
        '9' => '9.png',
        '10' => '10.png',
        '11' => '11.png',
        '12' => '12.png',
        '13' => '13.png',
        '14' => '14.png',
        '15' => '15.png',
        '16' => '16.png',
        '17' => '17.png',
        '18' => '18.png',
        '19' => '19.png',
        '20' => '20.png',
        '100' => '100.png',
        '1000000' => 'million.png',
        'i' => 'inf.png',
        'h' => 'half.png',
        // Letter manas
        'x' => 'x.png',
        'y' => 'y.png',
        'z' => 'z.png',
        // Hybrid manas
        'w/u' => 'wu.png',
        'w/b' => 'wb.png',
        'u/b' => 'ub.png',
        'u/r' => 'ur.png',
        'b/r' => 'br.png',
        'b/g' => 'bg.png',
        'r/g' => 'rg.png',
        'r/w' => 'rw.png',
        'g/w' => 'gw.png',
        'g/u' => 'gu.png',
        // Muber/hybrid manas
        '2/w' => '2w.png',
        '2/u' => '2u.png',
        '2/b' => '2b.png',
        '2/r' => '2r.png',
        '2/g' => '2g.png',
        // Phyrexian manas
        'pw' => 'pw.png',
        'pu' => 'pu.png',
        'pb' => 'pb.png',
        'pr' => 'pr.png',
        'pg' => 'pg.png'
    );
    
    /**
     * Les deux constantes suivantes indiquent la taille des manas par rapport à la taille de police.
     * Elles ont été calculées à partir de la police Magic Symbols 2008, ainsi que différents scans de taille variées.
     * Les valeurs sont donc une moyenne arbitraire choisie d'après ces données.
     */
    const SMALL_MANA_RATIO = 0.785;
    const LARGE_MANA_RATIO = 1.015;
    /**
     * Les deux constantes ci-dessous indiquent la distance entre la baseline et le haut du mana.
     * En effet les manas sont de simages et non une police, il faut donc les placer à la main.
     * Cette distance est représentée par un rapport disance / taille de police.
     */
    const SMALL_MANA_TOPLINE = 0.71;
    const LARGE_MANA_TOPLINE = 0.825;
    /**
     * Les deux constantes ci-dessous représentent l'écart entre deux manas.
     * Bien que l'image soit carrée, le caractère en lui-même est léèrement plus large afin que les manas ne soient pas collés.
     * Le rond du mana est ainsi centré dans sa bounding box horizontale.
     * Encore une fois, ce nombre est représenté en rapport avec la taille de police.
     */
    const SMALL_MANA_EFFECTIVE_WIDTH = 0.875;
    const LARGE_MANA_EFFECTIVE_WIDTH = 1.035;
    /**
     * Les deux constantes ci-dessous rajoutent de la longueur aux manas non-ronds.
     * La longueur additionnelle sera ajoutée à la longueur de base et est exprimée en ratio de taille de police.
     */
    const HUNDRED_ADDITIONNAL_SIZE = 0.88; // 176. / 200.
    const MILLION_ADDITIONNAL_SIZE = 4.08; // 816. / 200.
    
    private $_text; // texte brut
    private $_manas; // texte parsé en array de manas
    
    public function getText() { return $this->_text; }
    
    public function __construct($t){
        $this->_text = $t;
    }
    
    public function getCursorUpdates($imagick, $draw, $metrics, $cursor){
        $this->parseMana();
        $ret = [
            'x' => 0,
            'y' => 0
        ];
        foreach($this->_manas as $mana){
            //if(!self::isExistingMana($mana)) continue;
            if(self::isBraces($mana)) continue;
            if(self::isLargeMana($mana))
                $ret['x'] += (int)($metrics['characterHeight'] * self::LARGE_MANA_EFFECTIVE_WIDTH);
            elseif(self::isHundred($mana))
                $ret['x'] += (int)($metrics['characterHeight'] * self::SMALL_MANA_EFFECTIVE_WIDTH + ($metrics['characterHeight'] * self::HUNDRED_ADDITIONNAL_SIZE * self::SMALL_MANA_RATIO));
            elseif(self::isMillion($mana))
                $ret['x'] += (int)($metrics['characterHeight'] * self::SMALL_MANA_EFFECTIVE_WIDTH + ($metrics['characterHeight'] * self::MILLION_ADDITIONNAL_SIZE * self::SMALL_MANA_RATIO));
            else
                $ret['x'] += (int)($metrics['characterHeight'] * self::SMALL_MANA_EFFECTIVE_WIDTH);
        }
        
        return $ret;
    }
    
    public function render($imagick, &$draw, &$cursor, $ready2Print, $metrics){
        //var_dump($this);
        //var_dump($metrics);
        $tmpcur = [
            'x' => 0,
            'y' => 0
        ];
        foreach($this->_manas as $mana){
            //if(!self::isExistingMana($mana)) continue;
            if(self::isBraces($mana)) continue;
            // On charge le fichier
            $manaImage = new Imagick(self::getManaFile($mana));
            $size = 0; $yoffset = 0; $xoffset = 0; $advance = 0;
            if($manaImage->getNumberImages() == 0) continue;
            
            if(self::isLargeMana($mana)){
                // Rendu mana large
                $size = (int)($metrics['characterHeight'] * self::LARGE_MANA_RATIO);
                //var_dump($size);
                $manaImage->resizeImage(0, $size, Imagick::FILTER_LANCZOS, 1);
                $yoffset = (int)($metrics['characterHeight'] * self::LARGE_MANA_TOPLINE);
                $xoffset = (int)($metrics['characterHeight'] * ( self::LARGE_MANA_EFFECTIVE_WIDTH - self::LARGE_MANA_RATIO) / 2);
                $advance = (int)($metrics['characterHeight'] * self::LARGE_MANA_EFFECTIVE_WIDTH);
            } else {
                // Rendu mana small
                $size = (int)($metrics['characterHeight'] * self::SMALL_MANA_RATIO);
                //var_dump($size);
                $manaImage->resizeImage(0, $size, Imagick::FILTER_LANCZOS, 1);
                $yoffset = (int)($metrics['characterHeight'] * self::SMALL_MANA_TOPLINE);
                $xoffset = (int)($metrics['characterHeight'] * ( self::SMALL_MANA_EFFECTIVE_WIDTH - self::SMALL_MANA_RATIO) / 2);
                
                if(self::isHundred($mana))
                    $advance = (int)($metrics['characterHeight'] * self::SMALL_MANA_EFFECTIVE_WIDTH + ($metrics['characterHeight'] * self::HUNDRED_ADDITIONNAL_SIZE * self::SMALL_MANA_RATIO));
                elseif(self::isMillion($mana))
                    $advance = (int)($metrics['characterHeight'] * self::SMALL_MANA_EFFECTIVE_WIDTH + ($metrics['characterHeight'] * self::MILLION_ADDITIONNAL_SIZE * self::SMALL_MANA_RATIO));
                else
                    $advance = (int)($metrics['characterHeight'] * self::SMALL_MANA_EFFECTIVE_WIDTH);
            }
            
            $imagick->compositeImage(
                $manaImage, Imagick::COMPOSITE_OVER,
                $cursor->x + $tmpcur['x'] + $xoffset,
                $cursor->y + $tmpcur['y'] - $yoffset
            );
            $tmpcur['x'] += $advance;
        }
    }
    
    public function parseMana(){
        //$this->_text = str_replace(['{', '}'], '', $this->_text); // On utilise les { et } comme séparateurs de manas
        $this->_text = strtolower($this->_text);
        $this->_manas = preg_split(self::$parseRegex, $this->_text, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }
    
    public static function isLargeMana($text){
        return preg_match(self::$largeManaRegex, $text);
    }
    
    public function hasLargeManas(){
        return self::isLargeMana($this->_text);
    }
    
    public static function isHundred($text){
        return $text == '100';
    }
    
    public static function isMillion($text){
        return $text == '1000000';
    }
    
    public static function isExistingMana($text){
        return isset(self::$existingManas[$text]);
    }
    
    public static function isBraces($text){
        return preg_match('#(\{|\})+#', $text);
    }
    
    public static function getManaFile($text){
        if(!self::isExistingMana($text)) return realpath('resource/warning.png');
        return realpath('resource/mana/'.self::$existingManas[$text]);
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
    
    public function render($imagick, &$draw, &$cursor, $ready2Print, $metrics){
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
    
    public function render($imagick, &$draw, &$cursor, $ready2Print, $metrics){
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
    
    public function render($imagick, &$draw, &$cursor, $ready2Print, $metrics){
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
    
    public function render($imagick, &$draw, &$cursor, $ready2Print, $metrics){
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
    
    public function render($imagick, &$draw, &$cursor, $ready2Print, $metrics){
        $ready2Print->setItalicMode(false);
    }
    
}