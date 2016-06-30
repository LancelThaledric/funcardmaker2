<?php

require_once('include/functions.php');
require_once('include/FcmFcRender.php');

/**
 * Représente une funcard créée avec le SMFFCM2.
 * C'est la brique principale, une classe abstraite. Pour créer une funcard, il faut instancier un des templates.
 */

abstract class FcmFuncard extends FcmFcRender{
    
    //* Le nom du template de la funcard. Doit être définie par la sous-classe.
    private $_templateName;
    public function getTemplateName() { return $this->_templateName; }
    protected function setTemplateName($t) {$this->_templateName = $t;}
    
    //* La liste des champs (informations de création) de la funcard. C'est un tableau associatif.
    protected $_fields;
    
    //* La liste des valeurs par défault des champs
    protected $_defaults;
    
    //* Accesseur / Mutateur des champs de la funcard
    public function hasField($field) { return isset($this->_fields[$field]); }
    public function getField($field) {
        if($this->hasField($field))
            return $this->_fields[$field];
        else
            return null;
    }
    public function setField($field, $value){ $this->_fields[$field] = $value; }
    
    //* La liste des components de la funcard. C'est un tableau associatif.
    protected $_components;
    
    //* Les paramètres par défaut
    protected $_defaultParameters;
    
    //* Gestion des components
    public function getComponents() { return $this->_components; }
    public function getComponent($name) {
        if(isset($this->_components[$name]))
            return $this->_components[$name];
        else
            return null;
    }
    public function setComponent($name, $component) { $this->_components[$name] = $component; }
    public function removeComponent($name) { unset($this->_components[$name]); }
    
    //* Constructeur
    public function __construct($width, $height, $init = true, $data = null){
        parent::__construct($width, $height);
        $this->_templateName = '';
        $this->_fields = [];
        $this->_defaults = [];
        $this->_components = [];
        $this->_defaultParameters = [];
        if($init){
            $this->setDefaults();
            $this->import($data);
            $this->init();
            $this->configureComponents();
        }
    }
    
    //* Charge les champs par défaut
    public function setDefaults(){
        foreach($this->_defaults as $key => $value){
            $this->setField($key, $value);
        }
    }
    
    //* Copie les paramètres par défaut
    public function setDefaultParameters(){
        $this->setParameters($this->_defaultParameters);
    }
    
    //* Retourne la valeur du paramètre
    public function getParameter($componentName, $parameter){
        if(!$this->hasComponent($componentName))
            throw new ErrorException('getParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
        return $this->getComponent($componentName)->getParameter($parameter);
    }
    
    //* Change un paramètre
    public function setParameter($componentName, $parameter, $value){
        if(!$this->hasComponent($componentName))
            throw new ErrorException('setParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
        $this->getComponent($componentName)->setParameter($parameter, $value);
    }
    public function updateParameter($componentName, $parameter, $value){
        if(!$this->hasComponent($componentName))
            throw new ErrorException('setParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
        $this->getComponent($componentName)->updateParameter($parameter, $value);
    }
    
    //* Change plusieurs paramètres
    public function setParameters($params){
        foreach($params as $comp => $array){
            foreach($array as $key => $value){
                $this->setParameter($comp, $key, $value);
            }
        }
    }
    
    //* Met à jour les paramètres de components d'après une liste d'options
    public function setListenedParameters($options){
        if(isset($options) && $options){
            if(!is_array($options)) return;
            
            //var_dump($options);
            
            foreach($options as $comp => $array){
                foreach($array as $key => $value){
                    //var_dump('check '.$comp.' '.$key);
                    if($this->getComponent($comp) !== null && $this->getComponent($comp)->listens($key)){
                        $this->setParameter($comp, $key, $value);
                        //var_dump('set '.$comp.' '.$key);
                    }
                }
            }
        }
    }
    
    //* Check si le component existe
    public function hasComponent($name){
        return isset($this->_components[$name]);
    }
    
    //* génère un nom de fichier d'après les champs
    public function computeFilename(){
        $this->setFilename($this->hasField('title') ? $this->getField('title') : 'funcard');
    }
    
    //* génère une chaine Json
    public function getJson(){
        $export = [];
        
        $export['template'] = $this->_templateName;
        $export['width'] = $this->_width;
        $export['height'] = $this->_height;
        $export['fields'] = [];
        
        foreach($this->_fields as $key => $val){
            $export['fields'][$key] = $val;
        }
        
        return json_readable_encode($export);
    }
    
    //* Effectue un rendu de la funcard : appelle un à un les différents components
    public function render(){
        // 1 On trie les components par ordre de priorité
        uasort($this->_components, 'FcmFuncardComponent::compare');
        
        // 2 On les applique un à un
        foreach ($this->_components as $name => $component){
            $this->applyComponent($name);
        }
        
        $this->getCanvas()->setImageFormat('png');
        return $this->getCanvas();
    }
    
    //* Applique un component
    public function applyComponent($name){
        
        try{
        
            $options = $this->_components[$name]->apply();
            // apply() a renvoyé un set d'options à mettre à jour
            $this->setListenedParameters($options);
            
        } catch (Exception $e){
            // Rien du tout, c'est une image qu'on génère
            // Mais au moins le scrit plante pas s'il y a une connerie
            // au niveau des paramètres des components
            // Et puis on innone pas le code de try{} 
            if(DEBUG) echo $e->getMessage();
        }
    }
    
    //* Configure les components
    public function configureComponents(){
        foreach ($this->_components as $name => $component){
            $this->configureComponent($name);
        }
    }
    
    //* Configure le component
    public function configureComponent($name){
        try{
        
            $options = $this->_components[$name]->configure();
            // configure() a renvoyé un set d'options à mettre à jour
            $this->setListenedParameters($options);
            
        } catch (Exception $e){
            if(DEBUG) echo $e->getMessage();
        }
    }
    
    //* Importe les données de $data dans la funcard
    public function import($data){
        if(!is_array($data)) return;
        if(isset($data['width']))
            $this->setWidth(intval($data['width']));
        if(isset($data['height']))
            $this->setHeight(intval($data['height']));
        // TODO prévoir le crop de l'image si nécessaire
        
        //var_dump($data);
        
        if(isset($data['fields']) and is_array($data['fields'])){
            foreach($data['fields'] as $key => $value){
                if($value != '')
                    $this->setField($key, $value);
            }
        }
    }
}