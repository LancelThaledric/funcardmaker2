<?php

/**
 * Représente une image traitée par le SMFFCM2.
 */

class FcmFcRender {
    
    //* Taille des miniatures en largeur et en poids (Ko)
    const THUMBNAIL_SIZE_X = 460;
    const THUMBNAIL_SIZE_KO = 150;
    
    //* Taille de police de base
    const BASE_FONT_SIZE = 0.032520325203252;
    
    //* Surface de rendu ImageMagick
    private $_canvas;
    public function getCanvas() { return $this->_canvas; }
    
    //* Nom de fichier
    private $_filename; // nom sans extension et sans chemin
    private $_extension;
    public function getFilename(){
        return $this->_filename . '.' . $this->_extension;
    }
    public function setFilename($f) { $this->_filename = $f; }
    
    //* Dimensions de l'image
    private $_width;
    private $_height;
    
    public function getWidth() { return $this->_width; }
    public function setWidth($w) { $this->_width = $w; }
    
    public function getHeight() { return $this->_height; }
    public function setHeight($h) { $this->_height = $h; }
    
    public function getRatio() { return $this->_width / $this->_height; }
    
    //* Conversions des coordonnées % en pixels
    //* Les paramètres sont en % de la largeur/hauteur de l'image
    //* La valeur le retour est en px
    
    public function xc($x) { return round($this->_width / 100. * $x); }
    public function reverse_xc($x) { return $x / 100. * $this->_width ; }
    public function yc($y) { return round($this->_height / 100. * $y); }
    public function reverse_yc($y) { return $y / 100. * $this->_height; }
    // $xy est un array de la forme ['x', 'y']
    public function xyc($xy) {
        return [
            'x' => $this->xc($xy['x']),
            'y' => $this->yc($xy['y'])
        ];
    }
    public function reverse_xyc($xy) {
        return [
            'x' => $this->reverse_xc($xy['x']),
            'y' => $this->reverse_yc($xy['y'])
        ];
    }
    
    //* Conversion des taille de texte
    public function fsc($fs) { return self::BASE_FONT_SIZE * $this->_height * $fs; }
    public function reverse_fsc($fs) { return $fs / $this->_height / self::BASE_FONT_SIZE; }
    
    //* Retire les caractères spéciaux du nom de fichier
    public function filenameSpecialChars(){
        $this->_filename = preg_replace("/[^A-Za-z0-9]/", '_', $this->_filename);
    }
    
    //* Charge une image depuis un fichier
    //* $uri est le chemin vers le fichier dans le dossier temporaire, $path est le chemin/nom de fichier original.
    public function fromFile($uri, $path){
        try {
            $this->_canvas = new Imagick($uri);
            $this->_width = $this->_canvas->getImageWidth();
            $this->_height = $this->_canvas->getImageHeight();
            $pathinfo = pathinfo($path);
            $this->_extension = $pathinfo['extension'];
            $this->_filename = $pathinfo['filename'];
            $this->filenameSpecialChars();
        } catch (ImagickException $e){
            //echo $e;
        }
    }
    
    //* Constructeur
    //* Peut être appelé de deux manières : avec un fichier en paramètre (uri et nom original),
    //* ou avec deux paramètres width et height, créant ainsi une image entièrement transparente.
    
    public function __construct(){
        $numargs = func_num_args();
        if($numargs > 2)
            throw new ErrorException('Bad number of arguments to the constructor of FcmFcRender', E_USER_WARNING);
    
        $this->_canvas = null;
        $this->_width = 0;
        $this->_height = 0;
        $this->_filename = '';
        $this->_extension = '';
        
        if($numargs == 0) return;
        
        $args = func_get_args();
        if(is_string($args[0]) && is_string($args[1])){
            $this->fromFile($args[0], $args[1]);
        } elseif (is_numeric($args[0]) && is_numeric($args[1])){
            $this->_width = $args[0];
            $this->_height = $args[1];
        } else {
            throw new ErrorException('Bad arguments of constructor of FcmFcRender', E_USER_WARNING);
        }
    }
    
    //* Fonction d'initailisation : créé le canvas avec les données chargées
    //* Ne l'utiliser que dans le cas d'une instanciation de Funcard : fromFile génère aussi le canvas
    public function init(){
        if($this->_canvas == null){
            $this->_canvas = new Imagick();
            $this->_canvas->newImage($this->_width, $this->_height, 'transparent', 'png');
            $this->_filename = 'image';
            $this->_extension = 'png';
        }
    }
    
    //* Effectue le rendu de l'image
    public function render(){
        return $this->_canvas;
    }
    
    //* Miniaturise l'image
    public function miniaturize(){
        $this->_canvas->resizeImage(self::THUMBNAIL_SIZE_X, 0,
                                    Imagick::FILTER_LANCZOS, 1, false);
        $white = new Imagick();
        $white->newImage($this->_canvas->getImageWidth(),
                         $this->_canvas->getImageHeight(),
                         'white', 'jpg');
        $white->compositeImage($this->_canvas, Imagick::COMPOSITE_OVER, 0, 0);
        
        // ensuite il faut choisir la qualité de compression
        // $white is my image
        // self::THUMBNAIL_SIZE_KO is 150

        $quality = 100;
        $white->setImageFormat('jpg');
        $white->setImageCompression(Imagick::COMPRESSION_JPEG);
        $white->setImageCompressionQuality($quality);
        $data = $white->getImageBlob();
        while(strlen($data) > self::THUMBNAIL_SIZE_KO * 1024 && $quality > 0){
            $quality--;
            $white->setImageCompressionQuality($quality);
            $data = $white->getImageBlob();
        }

        $this->_canvas = $white;
        $this->_filename .= '-thumb';
        $this->_extension = 'jpg';
    }
    
    //* Crée une image vierge de la taille du rendu, appelé un "calque"
    public function createLayer($fill = 'transparent'){
        $layer = new Imagick();
        $layer->newImage($this->_width, $this->_height, $fill, 'png');
        return $layer;
    }
    
}