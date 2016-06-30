<?php

abstract class Funcard{
    
    /**
     * Correspond à la taille de police en % de la capabox par défaut.
     * C'est l'unité de mesure en em
     * Equivaut à 36px de taille de police pour une carte HD de 1107 pixels de hauteur
     * (D'après les bases Gimp et Toshop de Sovelis)
     */
    const BASE_FONT_SIZE = 0.032520325203252;
    
    const THUMBNAIL_SIZE_X = 460;
    const THUMBNAIL_SIZE_KO = 150;
    
    // Taille de la carte
    private $_width;
    private $_height;
    
    // nom du template
    private $_templateName;
    
    // Tableau des champs de la carte (Titre, type, capa, cm, F/E, etc.)
    private $_fields;
    
    // Canvas ImageMagick
    private $_canvas;
    
    // Accesseurs/Mutateurs de la taille de carte
    public function getWidth() { return $this->_width; }
    public function setWidth($w) { $this->_width = $w; }
    
    public function getHeight() { return $this->_height; }
    public function setHeight($h) { $this->_height = $h; }
    
    public function getTemplateName() { return $this->_templateName; }
    protected function setTemplateName($t) {$this->_templateName = $t;}
    
    public function getRatio() { return $this->_width / $this->_height; }
    
    // Accesseur / Mutateur des champs
    public function getField($field) {
        if(isset($this->_fields[$field]))
            return $this->_fields[$field];
        else
            return null;
    }
    public function setField($field, $value){ $this->_fields[$field] = $value; }
    
    // Accesseur canvas
    public function getCanvas() { return $this->_canvas; }
    
    /**
     * Constructeur
     */
    protected function __construct($width, $height, $defaults = true)
    {
        $this->_width = $width;
        $this->_height = $height;
        $this->_fields = array();
        if($defaults){
            $this->setDefaultFields();
        }
        
        $this->_canvas = new Imagick();
    }
    
    /**
     * Destructeur
     */
    function __destruct() {
        $this->_canvas->clear();
    }
    
    /**
     * Calcule le rendu de la funcard
     * method contient le type de rendu, afin de discerner le version HD de la version taille réduite
     */
    abstract public function render($method);
    
    /**
     * Convertit une position en % en pixels sur l'axe horizontal
     */
    public function getXCoord($x){
        return $this->_width / 100. * $x;
    }
    /**
     * Convertit une position en % en pixels sur l'axe vertical
     */
    public function getYCoord($y){
        return $this->_height / 100. * $y;
    }
    
    /**
     * Convertit la position en % donnée en position en pixels
     * $pos est un position esr un array associatif au format ['x' => ***, 'y' => ***]
     */
    public function getXYCoords($pos){
        return [
            'x' => $this->getXCoord($pos['x']),
            'y' => $this->getYCoord($pos['y'])
        ];
    }
    
    /**
     * Convertit la taille de police en em donnée en px
     * 1em correspond à la taille d'écrite de la capabox (taille par défaut)
     * (36px sur une carte de HD de hauteur 1107px, d'après les bases de Sovelis)
     */
    public function getFontSize($s){
        return round(self::BASE_FONT_SIZE * $this->_height * $s);
    }
    
    
    /**
     * Charge les données de la funcard depuis l'array passé en paramètre
     */
    public function loadFromData($data){
        
        //var_dump($data);
        
        if(isset($data['width']))
            $this->setWidth(intval($data['width']));
        if(isset($data['height']))
            $this->setHeight(intval($data['height']));
        
        if(isset($data['fields']) and is_array($data['fields'])){
            foreach($data['fields'] as $key => $value){
                //var_dump($key);
                //var_dump($value);
                if($value != '')
                    $this->_fields[$key] = $value;
            }
        }
    }
    
    /**
     * Créée les champs par défaut de la carte
     */
    abstract public function setDefaultFields();
    
    /**
     * découpe le texte (normalement capacité + TA) en mots
     */
    public function splitWords($text){
        $words = preg_split('#(\s+|\{\w+\}|</?i>)#', $text, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        return $words;
    }
    
    /**
     * découpe le texte-mana (normalement coût de mana) en manas séparés
     */
    public function splitManas($text){
        $words = preg_split('#(\d|[xyzwubrgs])#', $text, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        return $words;
    }
    
    /**
     * Miniaturise la carte pour le format jpg 150ko
     */
    public function miniaturize(){
        $this->_canvas->resizeImage(self::THUMBNAIL_SIZE_X, 0,
                                    Imagick::FILTER_LANCZOS, 1, false);
        $white = new Imagick();
        $white->newImage($this->_canvas->getImageWidth(),
                         $this->_canvas->getImageHeight(),
                         'white', 'miff');
        $white->compositeImage($this->_canvas, Imagick::COMPOSITE_OVER, 0, 0);
        
        // ensuite il faut choisir la qualité de compression
        // $white is my image
        // self::THUMBNAIL_SIZE_KO is 150

        $quality = 100;
        $white->setImageFormat('jpg');
        $white->setImageCompression(Imagick::COMPRESSION_JPEG);
        $white->setImageCompressionQuality($quality);
        $data = $white->getImageBlob();
        //var_dump(strlen($data));
        while(strlen($data) > self::THUMBNAIL_SIZE_KO * 1024 && $quality > 0){
            $quality--;
            $white->setImageCompressionQuality($quality);
            $data = $white->getImageBlob();
            //var_dump($quality);
            //var_dump(strlen($data));
        }

        $this->_canvas = $white;
    }
    
    /**
     * Calcule un nom defichier pour la carte
     */
    public function computeFileName($ext, $suffix = ''){
        
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $this->getField('title'));
        $name = preg_replace("/[^A-Za-z0-9]/", '_', $name);
        $name .= $suffix . '.' . $ext;
        return $name;
    }
    
    /**
     * Convertit la funcard en json
     */
    public function getJsonData(){
        $export = [];
        
        $export['template'] = $this->_templateName;
        $export['width'] = $this->_width;
        $export['height'] = $this->_height;
        $export['fields'] = [];
        
        foreach($this->_fields as $key => $val){
            $export['fields'][$key] = $val;
        }
        
        /*$var = get_object_vars($export);
        foreach($var as &$value){
           if(is_object($value) && method_exists($value,'getJsonData')){
              $value = $value->getJsonData();
           }
        }*/
        return json_readable_encode($export);
     }
}