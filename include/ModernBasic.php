<?php

require_once('Funcard.php');

use PHPImageWorkshop\ImageWorkshop;

class ModernBasic extends Funcard{
    
    
    // Voici ces ajustements arbitraires
    const DEVIL_LAST_LINE_HEIGHT = 1.1;     // hauteur de la dernière ligne pour savoir si on a encore de la place
    const DEVIL_VERTICAL_ALIGN = 0.2;       // hauteur de la dernière ligne pour l'alignement
    
    // Répertoire des fonds
    private $BACKGROUND_DIR;
    
    // Position des éléments
    private $TITLE_POS;
    private $TYPE_POS;
    private $ILLUSTRATOR_POS;
    private $ILLUSTRATOR_PAINTBRUSH_POS;
    private $COPYRIGHT_POS;
    private $SE_POS_TR;
    
    private $CAPABOX_BBOX;
    private $FEBOX_BBOX;
    private $CMBOX_BBOX;
    private $ILLUSBOX_BBOX;
    
    private $SE_HEIGHT;
    
    // Taille de police
    private $TITLE_FONT_SIZE;
    private $TYPE_FONT_SIZE;
    private $FE_FONT_SIZE;
    private $FESTAR_FONT_SIZE;
    private $FESTAR_OFFSET;
    private $CM_FONT_SIZE;
    private $ILLUSTRATOR_FONT_SIZE;
    private $ILLUSTRATOR_PAINTBRUSH_FONT_SIZE;
    private $COPYRIGHT_FONT_SIZE;
    
    // Couleur des manas
    private $MANA_COLORS;
    
    // Drawer ImageMagick
    private $_draw;
    
    // Compteur de balises <i> dans la capabox
    private $_iNumber;
    
    // Text Over sampling : L'antialiaseur d'ImageMagick est déguelasse, alors on va oversampler le texte par 4
    private $TEXT_OVERSAMPLING;
    private $MANA_KERNING_OFFSET;
    private $TEXT_INTERLINE_CORRECTION;
    
    public function __construct($width, $height, $defaults = true){
        parent::__construct($width, $height, $defaults);
        $this->_draw = new ImagickDraw();
        $this->_draw->setTextEncoding ('UTF-8');
        
        $this->setTemplateName('modern-basic');
        
        $this->BACKGROUND_DIR = $this->getTemplateName();
        
        // Les valeurs en % sont calculées à partr d'une carte de tille 791*1107px
        $this->TITLE_POS = [
            'x' => (73. / 791.) * 100,
            'y' => (108. / 1107.) * 100
        ];
        
        $this->TYPE_POS = [
            'x' => (80. / 791.) * 100,
            'y' => (664. / 1107.) * 100
        ];
        
        $this->CAPABOX_BBOX = [
            'x' => (78. / 791.) * 100,
            'y' => (700. / 1107.) * 100,
            'w' => (634. / 791.) * 100,
            'h' => (284. / 1107.) * 100
        ];
        
        $this->FEBOX_BBOX = [
            'x' => (588. / 791.) * 100,
            'y' => (981. / 1107.) * 100,
            'w' => (145. / 791.) * 100,
            'h' => (60. / 1107.) * 100
        ];
        
        $this->CMBOX_BBOX = [
            'x' => (73. / 791.) * 100,
            'y' => (64. / 1107.) * 100,
            'w' => (650. / 791.) * 100,
            'h' => (49. / 1107.) * 100
        ];
        
        $this->ILLUSBOX_BBOX = [
            'x' => (70. / 791.) * 100,
            'y' => (133. / 1107.) * 100,
            'w' => (651. / 791.) * 100,
            'h' => (480. / 1107.) * 100
        ];
        
        $this->ILLUSTRATOR_POS = [
            'x' => (124. / 791.) * 100,
            'y' => (1034. / 1107.) * 100
        ];
        
        $this->ILLUSTRATOR_PAINTBRUSH_POS = [
            'x' => (65. / 791.) * 100,
            'y' => (1035. / 1107.) * 100
        ];
        
        $this->COPYRIGHT_POS = [
            'x' => (67. / 791.) * 100,
            'y' => (1058. / 1107.) * 100
        ];
        
        $this->SE_POS_TR = [
            'x' => (718. / 791.) * 100,
            'y' => (630. / 1107.) * 100
        ];
        
        $this->SE_HEIGHT = (44. / 1107.) * 100;
        
        $this->TITLE_FONT_SIZE = (48. / 36.);
        $this->TYPE_FONT_SIZE = (40. / 36.);
        $this->FE_FONT_SIZE = (48. / 36.);
        $this->FESTAR_FONT_SIZE = (50. / 36.);
        $this->FESTAR_OFFSET = (10. / 1107.) * 100;   // Décalage vers le bas par rapport au texte normal de la f/e
        $this->CM_FONT_SIZE = (44. / 36.);
        $this->ILLUSTRATOR_FONT_SIZE = (29. / 36.);
        $this->ILLUSTRATOR_PAINTBRUSH_FONT_SIZE = (26. / 36.);
        $this->COPYRIGHT_FONT_SIZE = (18. / 36.);
        
        $this->MANA_COLORS = [
            'c' => 'rgb(203, 198, 193)',
            'w' => 'rgb(251, 249, 217)',
            'u' => 'rgb(193, 217, 237)',
            'b' => 'rgb(186, 177, 171)',
            'r' => 'rgb(242, 153, 113)',
            'g' => 'rgb(166, 197, 151)',
            'q' => 'black'
        ];
        
        $this->_iNumber = 0;
        
        $this->TEXT_OVERSAMPLING = 4;
        $this->MANA_KERNING_OFFSET = 1.042;
        $this->TEXT_INTERLINE_CORRECTION = 1;
    }
    
    /**
     * Créée les champs par défaut de la carte
     */
    public function setDefaultFields(){
        $this->setField('background-base', 'r');
        $this->setField('background-edgings', '');
        $this->setField('background-boxes', '');
        $this->setField('title', 'Titre');
        $this->setField('type', 'Type : sous-type');
        $this->setField('capa', '');
        $this->setField('ta', '');
        $this->setField('fe', '');
        $this->setField('cm', '');
        $this->setField('illustrator', 'N\'oubliez pas l\'illustrateur !');
        $this->setField('copyright', '');
        $this->setField('illustration', '');
        $this->setField('illuscrop-x', null);
        $this->setField('illuscrop-y', null);
        $this->setField('illuscrop-w', null);
        $this->setField('illuscrop-h', null);
        $this->setField('se-extension', '');
        $this->setField('se-rarity', '');
        $this->setField('se-custom', '');
    }
    
    public function render($method){
        
        $this->getCanvas()->newImage($this->getWidth(), $this->getHeight(), 'transparent', 'miff');
        
        $this->drawBackground();
        
        //$this->drawTitle($this->getField('title'));
        $this->drawTitle($this->getField('title'));
        $this->drawType($this->getField('type'));
        
        $this->drawCapacity($this->getField('capa'),
                            $this->getField('ta')
                           );
        
        $this->drawFe($this->getField('fe'));
        $this->drawCm($this->getField('cm'));
        
        $this->drawIllustrator($this->getField('illustrator'));
        $this->drawCopyright($this->getField('copyright'));
        
        $this->drawIllustration($this->getField('illustration'));
        $this->drawExtensionSymbol($this->getField('se-extension'), $this->getField('se-rarity'), $this->getField('se-custom'));
        
        $this->getCanvas()->setImageFormat('png');
        
        if($method == 'thumbnail') $this->miniaturize();
        
        return $this->getCanvas();
    }
    
    public function drawBackground(){
        
        // La dernière couche uniquement doit vérifier si la carte est une créature
        $count = 0;
        if(!empty($this->getField('background-edgings'))) ++$count;
        if(!empty($this->getField('background-boxes'))) ++$count;
        
        $this->drawBackgroundPart('base', $count == 0);
        --$count;
        
        if(!empty($this->getField('background-edgings'))){
            $this->drawBackgroundPart('edgings', $count == 0);
            --$count;
        }
        
        if(!empty($this->getField('background-boxes')))
            $this->drawBackgroundPart('boxes', true);
    }
    
    public function drawBackgroundPart($part, $checkCreature = true){
    
        // Le background est composé de plusieurs couches : base -> edgings -> boxes
        
        $background_base_name = $this->getField('background-'.$part);
        
        if($checkCreature && $this->isCreature() && file_exists(realpath('resource/backgrounds/' . $this->BACKGROUND_DIR . '/' . $part . '/' . $background_base_name . '-creature.png'))){
            $background_base_name .= '-creature';
        } 
        $background_base_path = realpath('resource/backgrounds/' . $this->BACKGROUND_DIR . '/' . $part . '/' . $background_base_name . '.png');
        
        try{
            $background = new Imagick(realpath($background_base_path));
            
            $bgwidth = $background->getImageWidth();
            $bgheight = $background->getImageHeight();
            if($bgwidth != $this->getWidth() || $bgheight != $this->getHeight()){
                $background->resizeImage($this->getWidth(), $this->getHeight(), Imagick::FILTER_TRIANGLE, 1, false);
            }
            $this->getCanvas()->compositeImage($background, Imagick::COMPOSITE_OVER, 0, 0);
            
        } catch (ImagickException $e){
            //echo $e;
        }
          
        
    }
    
    public function drawTitle($text){
        $this->_draw->setFillColor('black');
        $this->_draw->setFont(realpath('resource/font/matrix-bold.ttf'));
        $this->_draw->setFontSize($this->getFontSize($this->TITLE_FONT_SIZE));
        
        $pos = $this->getXYCoords($this->TITLE_POS);
        
        $this->getCanvas()->annotateImage($this->_draw, $pos['x'], $pos['y'], 0, $text);
    }
    
    public function drawType($text){
        $this->_draw->setFillColor('black');
        $this->_draw->setFont(realpath('resource/font/matrix-bold.ttf'));
        $this->_draw->setFontSize($this->getFontSize($this->TYPE_FONT_SIZE));
        
        $pos = $this->getXYCoords($this->TYPE_POS);
        
        $this->getCanvas()->annotateImage($this->_draw, $pos['x'], $pos['y'], 0, $text);
    }
    
    public function drawCapacity($text, $ta){
        // C'est ça le plus compliqué ! Héhéhé :D
        
        if(empty($text) && empty($ta)) return;
        
        // On créée la zone de texte
        $capabox = new Imagick();
        $capabox->newImage(
            $this->getXCoord($this->CAPABOX_BBOX['w']) * $this->TEXT_OVERSAMPLING,
            $this->getYCoord($this->CAPABOX_BBOX['h'] + 2) * $this->TEXT_OVERSAMPLING,     // on ajoute 2% de la hauteur de carte, sinon des fois lettres du bas sont coupées
            'transparent', 'miff'
        );
        $this->_draw->push();
        $this->_draw->setFillColor('black');
        $this->_draw->setFont(realpath('resource/font/mplantin.ttf'));
        $fontsize = 1;
        $this->_draw->setFontSize($this->getFontSize($fontsize) * $this->TEXT_OVERSAMPLING); 
        
        // Puis on découpe le texte à chaque espace
        $words = $this->splitWords($text);
        $wordsTA = $this->splitWords($ta);
        //var_dump($words);
        $loop = true;
        $cursor = [];
        $count = 0;
        while ($loop && $count < 50){
            $drawTA = true;
            ++$count;
            // Maintenant, on initialise le $cursor
            // Il se souvient de la position à écrire et se met à jour à chaque mot
            // C'est grâce à lui qu'on va écrire les mots les uns à la suite des autres
            // Et qu'on va savoir s'il faut aller à la ligne
            // On regarde les dimensiosn d'un caractère
            $metrics = $capabox->queryFontMetrics($this->_draw, 'x');
            $cursor = [
                'x' => 0,
                'y' => $metrics['characterHeight'],
                'lineheight' => $metrics['characterHeight'],         // espace en deux lignes
                'interline' => $metrics['characterHeight'] * 1.4,    // espace entre deux paragraphes
                'taseparator' => $metrics['characterHeight'] * 1.8   // space avant la texte d'ambiance
            ];
            //var_dump($fontsize);
            //Là on fait la capacité
            if(!empty($text)){
                foreach($words as $word){
                    $loop = !($this->printWord($word, $cursor, $capabox));
                    if($loop){
                        $drawTA = false;
                        break;
                    }
                }
            }
            
            //Là on fait le TA
            if($drawTA && !empty($ta)){
                if(!empty($text)){
                    // Là il faut insérer un espace entre la capacité et le TA
                    $cursor['y'] += $cursor['taseparator'] * $this->TEXT_INTERLINE_CORRECTION;
                    $cursor['x'] = 0;
                }
                
                $this->_draw->push();
                $this->_draw->setFont(realpath('resource/font/mplantin-italic.ttf'));
                foreach($wordsTA as $word){
                    $loop = !($this->printWord($word, $cursor, $capabox));
                    if($loop) break;
                }
                $this->_draw->pop();
            }
            
            
            if($loop){
                // Si on a manqué de place, on diminue la taille de police et en recommence !
                $capabox->clear();
                $capabox->newImage(
                    $this->getXCoord($this->CAPABOX_BBOX['w']) * $this->TEXT_OVERSAMPLING,
                    $this->getYCoord($this->CAPABOX_BBOX['h'] + 2) * $this->TEXT_OVERSAMPLING,     // on ajoute 2% de la hauteur de carte, sinon des fois lettres du bas sont coupées
                    'transparent', 'miff'
                );
                $fontsize -= (1. / $this->getFontSize(1));
                $this->_draw->setFontSize($this->getFontSize($fontsize) * $this->TEXT_OVERSAMPLING);
            }
        }
        
        $endHeight = $cursor['y'] + $cursor['lineheight'] * self::DEVIL_VERTICAL_ALIGN;
        
        // On désample l'image une fois que le texte est rendu
        if($this->TEXT_OVERSAMPLING != 1){
            $capabox->resizeImage($capabox->getImageWidth() / $this->TEXT_OVERSAMPLING,
                                  $capabox->getImageHeight() / $this->TEXT_OVERSAMPLING,
                                  Imagick::FILTER_TRIANGLE, 1, false);
            $endHeight /= $this->TEXT_OVERSAMPLING;
        }
        
        // A la fin on plaque la capabox sur le canvas global
        $this->getCanvas()->compositeImage(
            $capabox, Imagick::COMPOSITE_OVER,
            $this->getXCoord($this->CAPABOX_BBOX['x']),
            $this->getYCoord($this->CAPABOX_BBOX['y']) + ($this->getYCoord($this->CAPABOX_BBOX['h']) - $endHeight) / 2.
        );
        
        $this->_draw->pop();
    }
    
    /**
     * Insère le caractère dans la capabox. Retourne vrai tant qu'il y a encore de la place.
     */
    public function printWord($word, &$cursor, $capabox){
        // On teste si on n'a pas débordé
        //var_dump($cursor['y'] + $cursor['lineheight'] * self::DEVIL_LAST_LINE_HEIGHT); // La correction du démon ! (Sinon des fois ça rétrécit alors qu'il y a encore un peu de place)
        if( ($cursor['y'] + $cursor['lineheight'] * self::DEVIL_LAST_LINE_HEIGHT) > $this->getYCoord($this->CAPABOX_BBOX['h']) * $this->TEXT_OVERSAMPLING){
            // Si c'est le cas... Bah... On est marrons !
            // Il faut tout recommencer avec une police plus petite !
            //var_dump('MANQUE DE PLACE !!');
            //var_dump($cursor['y'] + $cursor['lineheight']);
            return false;
        }
        
        // On teste quelle est la nature du mot :
        // Cas possibles : Texte - Un mana (unique) - Retour à la ligne
        
        // On teste si c'est du mana
        if(preg_match('#^\{(\w+)\}$#iU', $word)){   //Le mana
            $this->printMana($word, $cursor, $capabox);
            return true;
        }
        
        // On teste si c'est un retour à la ligne
        if(preg_match('#^(\r\n?|\n)$#', $word)){  // retour à la ligne
            $cursor['y'] += $cursor['lineheight'] * $this->TEXT_INTERLINE_CORRECTION;
            $cursor['x'] = 0;
            return true;
        }
        
        // On teste si c'est un multi-retour à la ligne (saut de capacité)
        if(preg_match('#(\r\n?|\n)#', $word)){  // retour à la ligne
            $cursor['y'] += $cursor['interline'] * $this->TEXT_INTERLINE_CORRECTION;
            $cursor['x'] = 0;
            return true;
        }
        
        // On teste si c'est une balise <i>
        if($word == '<i>'){
            $this->_draw->push();
            $this->_draw->setFont(realpath('resource/font/mplantin-italic.ttf'));
            $this->_iNumber++;
            return true;
        }
        // On teste si c'est une balise </i>
        if($this->_iNumber > 0 && $word == '</i>'){
            $this->_draw->pop();
            $this->_iNumber--;
            return true;
        }
        
        // Sinon c'est un mot normal ou un espace
        // Alors on teste si on doit aller à la ligne
        $newline = false;
        $metrics = $capabox->queryFontMetrics($this->_draw, $word);
        if(($cursor['x'] + $metrics['textWidth']) > $this->getXCoord($this->CAPABOX_BBOX['w']) * $this->TEXT_OVERSAMPLING ){
            $cursor['y'] += $cursor['lineheight'] * $this->TEXT_INTERLINE_CORRECTION;
            $cursor['x'] = 0;
            $newline = true;
        }
        // Si la chaine est un espace et qu'on est retourné à la ligne, on affiche pas
        if($newline && preg_match('#^\s+$#', $word)) return;
        
        // On écrit le texte et on avance le curseur
        $capabox->annotateImage($this->_draw,
                                $cursor['x'],
                                $cursor['y'],
                                0,
                                $word);
        $cursor['x'] += $metrics['textWidth'];
        
        return true;
    }
    
    public function printMana($word, &$cursor, $capabox){
        // TODO
        $this->_draw->push();
        
        // On regarde de quelle lettre il s'agit
        // Possibilités : w u b r g s x y z i t q nombres
        $validletter = false;
        $letter = trim($word, '{}');
        //On teste si la lettre est valide
        if(preg_match('#^([wubrgsxyzitq]|\d+)$#', $letter)){
            $validletter = true;
            // Polices des manas
            $this->_draw->setFont(realpath('resource/font/magic-symbols-2008.ttf'));
            // SI c'est un vrai mana, il faut afficher non pas la lettre mais un rond
            $word = 'o';
            //on choppe la couleur du mana
            $color = $this->MANA_COLORS['c'];
            if(isset($this->MANA_COLORS[$letter]))
                $color = $this->MANA_COLORS[$letter];
            $this->_draw->setFillColor($color);
        }
        
        // Checker la fin de ligne
        $metrics = $capabox->queryFontMetrics($this->_draw, $word);
        if(($cursor['x'] + $metrics['textWidth']) > $this->getXCoord($this->CAPABOX_BBOX['w']) * $this->TEXT_OVERSAMPLING){
            $cursor['y'] += $cursor['lineheight'];
            $cursor['x'] = 0;
        }
        
        $capabox->annotateImage($this->_draw,
                                $cursor['x'],
                                $cursor['y'],
                                0,
                                $word);
        
        // Si c'est un vrai mana, il faut aussi qu'on mette le symbole de mana
        if($validletter){
            if($letter == 's'){     // mana snow
                $this->_draw->setFillColor('black');
                $capabox->annotateImage($this->_draw,
                                        $cursor['x'],
                                        $cursor['y'],
                                        0,
                                        'S');
                $this->_draw->setFillColor('white');
                $capabox->annotateImage($this->_draw,
                                        $cursor['x'],
                                        $cursor['y'],
                                        0,
                                        's');
            } elseif($letter == 'q') {      // untap
                $this->_draw->setFillColor('white');
                $capabox->annotateImage($this->_draw,
                                        $cursor['x'],
                                        $cursor['y'],
                                        0,
                                        'q');
            } else {            
            $this->_draw->setFillColor('black');
            $capabox->annotateImage($this->_draw,
                                    $cursor['x'],
                                    $cursor['y'],
                                    0,
                                    $letter);
            }
        }
        
        $cursor['x'] += $metrics['textWidth'] * $this->MANA_KERNING_OFFSET;
        
        
        $this->_draw->pop();
    }
    
    
    public function isCreature(){
        return !empty($this->getField('fe'));
    }
    
    public function drawFe($text){
        $febox = new Imagick();
        $febox->newImage(
            $this->getXCoord($this->FEBOX_BBOX['w']),
            $this->getYCoord($this->FEBOX_BBOX['h']),
            'transparent', 'miff'
        );
        $this->_draw->push();
        $this->_draw->setFillColor('black');
        
        // On doit texte les étoiles (franchement les étoiles c'est chiant)
        // On séprare les étoiles du teste du texte
        $fechars = preg_split('#(\*)#', $text, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        // On mesure un caractère des deux polices
        $this->_draw->setFont(realpath('resource/font/matrix-bold-small-caps.ttf'));
        $this->_draw->setFontSize($this->getFontSize($this->FE_FONT_SIZE));
        $numbersMetrics = $febox->queryFontMetrics($this->_draw, '5');
        $this->_draw->setFont(realpath('resource/font/mplantin.ttf'));
        $this->_draw->setFontSize($this->getFontSize($this->FESTAR_FONT_SIZE));
        $starsMetrics = $febox->queryFontMetrics($this->_draw, '*');
        
        // On initialise le curseur, mais celui-ci ne gère pas les retours à la ligne, pratique !
        $cursor = [
            'x' => 0,
            'y' => $numbersMetrics['characterHeight']
        ];
        $metrics = null;
        foreach($fechars as $chars){
            $this->_draw->push();
            if($chars == '*'){  // cas chiant de l'étoile
                $this->_draw->setFont(realpath('resource/font/mplantin.ttf'));
                $this->_draw->setFontSize($this->getFontSize($this->FESTAR_FONT_SIZE));
                $febox->annotateImage($this->_draw, $cursor['x'], $cursor['y'] + $this->getYCoord($this->FESTAR_OFFSET), 0, $chars);
                $metrics = $febox->queryFontMetrics($this->_draw, $chars);
                $cursor['x'] += $metrics['textWidth'];
            } else {
                $this->_draw->setFont(realpath('resource/font/matrix-bold-small-caps.ttf'));
                $this->_draw->setFontSize($this->getFontSize($this->FE_FONT_SIZE));
                $febox->annotateImage($this->_draw, $cursor['x'], $cursor['y'], 0, $chars);
                $metrics = $febox->queryFontMetrics($this->_draw, $chars);
                $cursor['x'] += $metrics['textWidth'];
            }
            $this->_draw->pop();
        }
        
        //$febox->annotateImage($this->_draw, 0, 0, 0, $text);
        
        
        // A la fin on plaque la febox sur le canvas global
        // On pense à décaler d'autant qu'il faut pour centrer le texte
        $this->getCanvas()->compositeImage(
            $febox, Imagick::COMPOSITE_OVER,
            $this->getXCoord($this->FEBOX_BBOX['x']) + ( $this->getXCoord($this->FEBOX_BBOX['w']) - $cursor['x'] ) / 2.,
            $this->getYCoord($this->FEBOX_BBOX['y'])
        );
        
        $this->_draw->pop();
    }
    
    public function drawCm($text){
        $cmbox = new Imagick();
        $cmbox->newImage(
            $this->getXCoord($this->CMBOX_BBOX['w']) * $this->TEXT_OVERSAMPLING,
            $this->getYCoord($this->CMBOX_BBOX['h']) * $this->TEXT_OVERSAMPLING,
            'transparent', 'miff'
        );
        $this->_draw->push();
        
        // On utilise magic Symbols 2008 (En espérant qu'il existe une nouvelle version parce que ça commence à dater sérieux)
        $this->_draw->setFont(realpath('resource/font/magic-symbols-2008.ttf'));
        $this->_draw->setFontSize($this->getFontSize($this->CM_FONT_SIZE) * $this->TEXT_OVERSAMPLING);
        
        $chars = $this->splitManas($text);
        $metrics = $cmbox->queryFontMetrics($this->_draw, 'o');
        $cursor = [
            'x' => 0,
            'y' => $metrics['characterHeight'],
            'lineheight' => 1
        ];
        
        foreach($chars as $char){
         
            if(preg_match('#(\d|[xyzwubrgs])#', $char)){
                $this->printMana($char, $cursor, $cmbox);
            } else {
                $this->_draw->push();
                $this->_draw->setFont(realpath('resource/font/mplantin.ttf'));
                $metrics = $cmbox->queryFontMetrics($this->_draw, $char);
                $cmbox->annotateImage($this->_draw,
                                      $cursor['x'],
                                      $cursor['y'],
                                      0,
                                      $char);
                $cursor['x'] += $metrics['textWidth'];
                $this->_draw->pop();
            }
            
        }
        
        // On désample l'image une fois que le texte est rendu
        if($this->TEXT_OVERSAMPLING != 1){
            $cmbox->resizeImage($cmbox->getImageWidth() / $this->TEXT_OVERSAMPLING,
                                $cmbox->getImageHeight() / $this->TEXT_OVERSAMPLING,
                                Imagick::FILTER_TRIANGLE, 1, false);
            $cursor['x'] /= 4;  //Pour l'alignage à droite
        }
        
        $cmboxshadow = clone $cmbox;
        $cmboxshadow->thresholdimage(1 * Imagick::getQuantum(), Imagick::CHANNEL_ALL);
        
        
        
        // A la fin on plaque la cmbox sur le canvas global
        // On pense à décaler d'autant qu'il faut pour aligner la texte à droite
        $this->getCanvas()->compositeImage(
            $cmboxshadow, Imagick::COMPOSITE_OVER,
            $this->getXCoord($this->CMBOX_BBOX['x']) + ( $this->getXCoord($this->CMBOX_BBOX['w']) - $cursor['x'] ) - 1,
            $this->getYCoord($this->CMBOX_BBOX['y']) + 4
        );
        
        $this->getCanvas()->compositeImage(
            $cmbox, Imagick::COMPOSITE_OVER,
            $this->getXCoord($this->CMBOX_BBOX['x']) + ( $this->getXCoord($this->CMBOX_BBOX['w']) - $cursor['x'] ),
            $this->getYCoord($this->CMBOX_BBOX['y'])
        );
        
        
        $this->_draw->pop();
    }
    
    public function drawIllustrator($text){
        $this->_draw->push();
        
        $invertedcolors = $this->getField('background-base') == 'b' || $this->getField('background-base') == 'l';
        $this->_draw->setFillColor($invertedcolors ? 'white' : 'black');
        
        $this->_draw->setFont(realpath('resource/font/matrix-bold.ttf'));
        $this->_draw->setFontSize($this->getFontSize($this->ILLUSTRATOR_FONT_SIZE));
        
        // le texte
        $pos = $this->getXYCoords($this->ILLUSTRATOR_POS);
        $this->getCanvas()->annotateImage($this->_draw, $pos['x'], $pos['y'], 0, $text);
        
        // le pinceau
        $pos = $this->getXYCoords($this->ILLUSTRATOR_PAINTBRUSH_POS);
        $this->_draw->setFont(realpath('resource/font/magic-symbols-2008.ttf'));
        $this->_draw->setFontSize($this->getFontSize($this->ILLUSTRATOR_PAINTBRUSH_FONT_SIZE));
        $this->getCanvas()->annotateImage($this->_draw, $pos['x'], $pos['y'], 0, 'l');
        $this->_draw->setFillColor($invertedcolors ? 'black' : 'white');
        $this->getCanvas()->annotateImage($this->_draw, $pos['x'], $pos['y']-1, 0, 'i');
        
        $this->_draw->pop();
    }
    
    public function drawCopyright($text){
        $this->_draw->push();
        $invertedcolors = $this->getField('background-base') == 'b' || $this->getField('background-base') == 'l';
        $this->_draw->setFillColor($invertedcolors ? 'white' : 'black');
        $this->_draw->setFont(realpath('resource/font/mplantin.ttf'));
        $this->_draw->setFontSize($this->getFontSize($this->COPYRIGHT_FONT_SIZE));
        
        $pos = $this->getXYCoords($this->COPYRIGHT_POS);
        $this->getCanvas()->annotateImage($this->_draw, $pos['x'], $pos['y'], 0, $text);
        
        
        $this->_draw->pop();
    }
    
    public function drawIllustration($filename){
        if(empty($filename)) return;
        $filepath = realpath('uploads/'.$filename);
        
        // bloc try : si le fichier n'est pas chargé, le script ne plantera pas.
        try{
            $illustration = new Imagick($filepath);
            
            // On resize l'image
            // 1 : On calcule quel est l'axe déterminant
            $illusratio = $illustration->getImageWidth() / $illustration->getImageHeight();
            $illusboxratio = $this->getXCoord($this->ILLUSBOX_BBOX['w']) / $this->getYCoord($this->ILLUSBOX_BBOX['h']);
            //var_dump($illusratio);
            $axis = true;  // true = X, false = Y
            if($illusratio > $illusboxratio)
                $axis = false;
            
            //var_dump($axis);
            
            
            
            // 2 On croppe l'image
            //var_dump($this->getField('illuscrop-x'));
            //var_dump($this->getField('illuscrop-y'));
            //var_dump($this->getField('illuscrop-w'));
            //var_dump($this->getField('illuscrop-h'));
            
            $px = $py = $pw = $ph = 0;
            if(is_null($this->getField('illuscrop-x'))
            || is_null($this->getField('illuscrop-y'))
            || is_null($this->getField('illuscrop-w'))
            || is_null($this->getField('illuscrop-h'))
              )
            {
                if($axis){  // format portait par rapport au cadre illustration
                    $pw = $illustration->getImageWidth();
                    $ph = $illustration->getImageWidth() / $illusboxratio;
                    $px = 0;
                    $py = ($illustration->getImageHeight() - $ph) / 2.;
                    //var_dump('AUTO CENTER PORTRAIT');
                }
                else{   // format paysage
                    $pw = $illustration->getImageHeight() * $illusboxratio;
                    $ph = $illustration->getImageHeight();
                    $px = ($illustration->getImageWidth() - $pw) / 2.;
                    $py = 0;
                    //var_dump('AUTO CENTER PAYSAGE');
                }
                //var_dump('AUTO CENTER');
            } else {
                $pw = $this->getField('illuscrop-w') / 100 * $illustration->getImageWidth();
                $ph = $this->getField('illuscrop-h') / 100 * $illustration->getImageHeight();
                $px = $this->getField('illuscrop-x') / 100 * $illustration->getImageWidth();
                $py = $this->getField('illuscrop-y') / 100 * $illustration->getImageHeight();
                //var_dump('CUSTOM');
            }
            
            
            //var_dump($pw);
            //var_dump($ph);
            //var_dump($px);
            //var_dump($py);
            
            $illustration->cropImage($pw, $ph, $px, $py);

            // 3 On resize
            
            $illustration->resizeImage($this->getXCoord($this->ILLUSBOX_BBOX['w']),
                                       $this->getYCoord($this->ILLUSBOX_BBOX['h']),
                                       Imagick::FILTER_TRIANGLE, 1, false);
            
            
            
            //var_dump($illustration->getImageWidth());
            //var_dump($illustration->getImageHeight());
            
            // 4 On plaque l'illustration sur la funcard
            $this->getCanvas()->compositeImage(
                $illustration, Imagick::COMPOSITE_OVER,
                $this->getXCoord($this->ILLUSBOX_BBOX['x']),
                $this->getYCoord($this->ILLUSBOX_BBOX['y'])
            );
            
            
        } catch (ImagickException $e){
            //echo $e;
        }
    }
    
    public function drawExtensionSymbol($extension, $rarity, $custom){
        //var_dump($extension);
        //var_dump($rarity);
        //var_dump($custom);
        
        if((!$extension || !$rarity) && !$custom) return;
        
        $filename = ''; $filepath = '';
        if(!$custom){
            $filename = $extension . '-' . $rarity . '.png';
            $filepath = realpath('resource/se/'.$filename);
        } else {
            $filepath = realpath('uploads/'.$custom);
        }
        
        //var_dump($filepath);
        
        // bloc try : si le fichier n'est pas chargé, le script ne plantera pas.
        try{
            $se = new Imagick($filepath);
            
            $se->resizeImage(0,
                            $this->getYCoord($this->SE_HEIGHT),
                            Imagick::FILTER_TRIANGLE, 1);
            
            $this->getCanvas()->compositeImage(
                $se, Imagick::COMPOSITE_OVER,
                $this->getXCoord($this->SE_POS_TR['x']) - $se->getImageWidth(),
                $this->getYCoord($this->SE_POS_TR['y'])
            );
            
        } catch (ImagickException $e){
            //echo $e;
        }
    }
}