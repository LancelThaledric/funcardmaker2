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
        self::$fontManager->setFont('plantin-bold', realpath('resource/font/plantin-bold.ttf'));
        self::$fontManager->setFont('magicsymbols', realpath('resource/font/magic-symbols-2008.ttf'));
        self::$fontManager->setFont('magicmedieval', realpath('resource/font/magic-medieval.ttf'));
    }
    
    //* La funcard qui contient notre component
    private $_funcard;
    public function getFuncard() { return $this->_funcard; }
    
    //* Liste des paramètres du component
    private $_parameters;
    
    //* Liste des paramètres user-defined (ils ne pourront plus être changés)
    private $_lockedParameters;
    
    //* Retourne la valeur du paramètre $name s'il existe. Sinon émet une exception.
    public function getParameter($name) {
        if(!$this->hasParameter($name))
            throw new ErrorException('getParameter() : Missing parameter "'.$name.'".', E_USER_WARNING);
        return $this->_parameters[$name];
    }
    
    //* Crée ou modifie le paramètre $name avec la valeur $value.
    public function setParameter($name, $value){
        if($this->isLocked($name)) return;
        $this->_parameters[$name] = $value;
    }
    
    //* Crée ou modifie le paramètre $name avec la valeur $value, à moins que $value soit indéfinie (ou null).
    public function pushParameter($name, $value){
        if($this->isLocked($name)) return;
        if(isset($value))
            $this->_parameters[$name] = $value;
    }
    
    //* Créé ou modifie le paramètre, à moins que $value soit vide.
    //* Le paramètre est ainsi verrouillé si la valeur a bien ét créée/modifiée.
    public function userParameter($name, $value){
        if(isset($value)){
            $this->_parameters[$name] = $value;
            $this->lockParameter($name);
        }
    }
    
    //* Modifie le paramètre $name avec la valeur $value. Ne fait rien si le paramètre n'est pas déjà défini.
    public function updateParameter($name, $value){
        if($this->isLocked($name)) return;
        if($this->hasParameter($name))
            $this->_parameters[$name] = $value;
    }
    
    //* Créé ou modifie les paramètres. $params est de la forme [nomParamètre => valeur].
    public function setParameters($params){
        if($this->isLocked($name)) return;
        foreach($params as $key => $value){
            $this->setParameter($key, $value);
        }
    }
    
    //* Retourne true ou false selon si le paramètre est défini.
    public function hasParameter($name){
        return isset($this->_parameters[$name]);
    }
    
    //* Créé un paramètre $name avec la valeur $value. Ne fait rien si le paramètre existe déjà et est non-vide.
    public function createParameter($name, $value){
        if(!$this->hasParameter($name) || empty($this->getParameter($name)))
            $this->_parameters[$name] = $value;
    }
    
    //* Verrouille le paramètre. Ainsi il ne pourra plus être modifié sauf par userParameter().
    public function lockParameter($name){ $this->lockedParameters[$name] = true; }
    
    //* Déverrouille le paramètre.
    public function unlockParameter($name){ unset($this->lockedParameters[$name]); }
    
    //* Renvoie si le paramètre est verrouillé
    public function isLocked($name){ return isset($this->lockedParameters[$name]); }
    
    //* Priorité - le plus faible est le plus prioritaire (en bas de la pile des calques)
    private $_priority;
    public function getPriority() { return $this->_priority; }
    public function setPriority($p) { $this->_priority = $p; }
    
    //* Liste d'écoute - Tous les noms de paramètres à checker les changements depuis les autres paramètres
    private $_listenlist;
    //* Retourne si le component écoute le paramètre
    public function listens($name) {
        return in_array($name, $this->_listenlist);
    }
    //* Fait écouter le paramètre
    public function listen($name) {
        if(!$this->listens($name)) $this->_listenlist[] = $name;
    } 
    
    //* Configure le component son l'application
    public abstract function configure();
    //* Applique le component sur la funcard
    public abstract function apply();
    
    //* Compare deux components selon leur priorité
    public static function compare(FcmFuncardComponent $a, FcmFuncardComponent $b){
        return $a->_priority - $b->_priority;
    }
    
    //* Constructeur
    public function __construct($funcard, $priority = 0){
        $this->_parameters = [];
        $this->_lockedParameters = [];
        $this->_priority = $priority;
        $this->_listenlist = [];
        $this->setDefaultParameters();
        $this->_funcard = $funcard;
    }
    
    //* Paramètres par défaut à la création du component
    public abstract function setDefaultParameters();
}

FcmFuncardComponent::static_init();