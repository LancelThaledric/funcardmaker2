<?php

require_once('include/FcmFontManager.php');

/**
 * Gère un component d'une funcard (titre, illustration, capacité, CM, etc.)
 * Une funcard contient un array associatif de components.
 */

abstract class FcmFuncardComponent {
    
    //* Renderer ImageMagick
    public static $draw;
    
    //* Font Manager
    public static $fontManager;
    
    public static function static_init(){
        self::$draw = new ImagickDraw();
        self::$draw->setTextEncoding('UTF-8');
        
        // Load fonts
        self::$fontManager = new FcmFontManager();
        self::$fontManager->setFont('matrix', realpath('resource/font/matrix-bold.ttf'));
        self::$fontManager->setFont('matrix-smallcaps', realpath('resource/font/matrix-bold-small-caps.ttf'));
        self::$fontManager->setFont('beleren', realpath('resource/font/beleren-bold.ttf'));
        self::$fontManager->setFont('beleren-smallcaps', realpath('resource/font/belerensmallcaps-bold.ttf'));
        self::$fontManager->setFont('mplantin', realpath('resource/font/mplantin.ttf'));
        self::$fontManager->setFont('mplantin-italic', realpath('resource/font/mplantin-italic.ttf'));
        self::$fontManager->setFont('magicsymbols', realpath('resource/font/magic-symbols-2008.ttf'));
    }
    
    //* La funcard
    private $_funcard;
    public function getFuncard() { return $this->_funcard; }
    
    //* Liste des paramètres du component
    private $_parameters;
    public function getParameter($name) {
        if(!$this->hasParameter($name))
            throw new ErrorException('getParameter() : Missing parameter "'.$name.'".', E_USER_WARNING);
        return $this->_parameters[$name];
    }
    public function setParameter($name, $value){
        $this->_parameters[$name] = $value;
    }
    public function updateParameter($name, $value){
        if(isset($value))
            $this->_parameters[$name] = $value;
    }
    public function setParameters($params){
        foreach($params as $key => $value){
            $this->setParameter($key, $value);
        }
    }
    public function hasParameter($name){
        return isset($this->_parameters[$name]);
    }
    
    //* Priorité - le plus faible est le plus prioritaire
    private $_priority;
    public function getPriority() { return $this->_priority; }
    public function setPriority($p) { $this->_priority = $p; }
    
    //* Liste d'écoute - Tous les noms de paramètres à checker les changements depuis les autres paramètres
    private $_listenlist;
    //* Retourne si le component écoute le paramètre
    public function listens($name) { return in_array($name, $this->_listenlist); }
    //* Fait écouter le paramètre
    public function listen($name) {
        if(!$this->listens($name)) $_listenlist[] = $name;
    } 
    
    //* Configure le component son l'application
    public abstract function configure();
    //* Applique le component sur la funcard
    public abstract function apply();
    
    //* Compare deux components
    public static function compare(FcmFuncardComponent $a, FcmFuncardComponent $b){
        return $a->_priority - $b->_priority;
    }
    
    //* Constructeur
    public function __construct($funcard, $priority = 0){
        $this->_parameters = [];
        $this->_priority = $priority;
        $this->_listenlist = [];
        $this->setDefaultParameters();
        $this->_funcard = $funcard;
    }
    
    //* Paramètres par défaut à la création du component
    public abstract function setDefaultParameters();
}

FcmFuncardComponent::static_init();