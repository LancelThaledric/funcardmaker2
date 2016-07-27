<?php

require_once('include/functions.php');
require_once('include/FcmFcRender.php');

/**
 * Représente une funcard créée avec le SMFFCM2.
 * C'est la brique principale, une classe abstraite. Pour créer une funcard, il faut instancier un des templates.
 */

abstract class FcmFuncard extends FcmFcRender{
    
    /***********************************************************************
    * CONSTRUCTEUR
    ************************************************************************/
    
    public function __construct($width, $height, $data = null){
        parent::__construct($width, $height);
        
        // 1 : nom du template
        if(!isset(static::$_templateName) || empty(static::$_templateName)){
            throw new ErrorException('FcmFuncard::__construct() : Template name is not defined or empty.', E_USER_ERROR);
        }
        
        // 2 : Defaults fields
        $this->setDefaultFields();
        
        // 3 : Import user fields
        if(isset($data)){
            $this->import($data);
        }
        
        // 4 : Create components & parameters
        $this->initComponents();
        $this->setDefaultParameters();
        $this->sortComponents();
        
        // 5 : Push fields to components (Create parameters)
        $this->pushComponentsData();
        
        // 6 : Init listening list
        $this->initListeningList();
        
        // 7 : Init Canvas
        $this->beforeInit();
        $this->init();
        
        // 8 : Configure Components
        $this->configureComponents();
        
        // Tout est configuré, la funcard est prête pour être rendue.
    }
    
    //* Fonction appelée avant l'initialisation du canvas, pour redéfinir la taille de la carte. À redéfinir dans les classes filles.
    public function beforeInit(){}
    
    
    /***********************************************************************
    * NOM DU TEMPLATE
    ************************************************************************/
    
    /**
     * Le nom du template de la funcard. Doit être définie en static par la sous-classe.
     */
    protected static $_templateName = null;
    public static function getTemplateName() { return static::$_templateName; }
    
    /***********************************************************************
    * FIELDS
    ************************************************************************/
    
    /**
     * Voici la liste des champs contenant les données pour créer la funcard.
     * Contient toutes els entrées utilisateur + les champs par défaut définies pour chaque template
     */
    
    //* La liste des champs (informations de création) de la funcard. C'est un tableau associatif [champ=>valeur].
    private $_fields;
    
    //* Retourne true si le champ est défini, false sinon.
    public function hasField($field) { return isset($this->_fields[$field]); }
    
    //* Retourne la valeur du champ $field, ou null s'il n'est pas défini.
    public function getField($field) {
        if($this->hasField($field))
            return $this->_fields[$field];
        else
            return null;
    }
    
    //* Met à jour le champ $field pour qu'il ait la valeur $value. Si le champ n'est pas défini, il est créé.
    public function setField($field, $value){ $this->_fields[$field] = $value; }
    
    //* Met à jour les champs du tableau $fields
    public function setFields($fields){
        foreach($fields as $field => $value){
            $this->setField($field, $value);
        }
    }
    
    //* Met à jour le champ $field pour qu'il ait la valeur $value. Si le champ n'est pas défini, ne fait rien et retour false.
    public function updateField($field, $value){
        if($this->hasField($field)){
            $this->setField($field, $value);
            return true;
        }
        return false;
    }
    
    //* Met à jour les champs du tableau $fields seulement s'ils existent.
    public function updateFields($fields){
        foreach($fields as $field => $value){
            $this->updateField($field, $value);
        }
    }
    
    //* Créé le champ $field avec la valeur $value seulement s'il existe pas. Retourne true en cas de succès, false sinon.
    public function createField($field, $value){
        if(!$this->hasField($field)){
            $this->setField($field, $value);
            return true;
        }
        return false;
    }
    
    //* Créé les champs du tableau $fields seulement s'ils n'existent pas déjà.
    public function createFields($fields){
        foreach($fields as $field => $value){
            $this->createField($field, $value);
        }
    }
    
    /**
     * Voici la liste des champs et valeur par défauts de ces champs.
     * À redéfinir dans les classes filles.
     */
    protected static $_defaultFields = [];
    
    //* Importe les champs par défaut dans la funcard
    public function setDefaultFields(){
        $this->setFields(static::$_defaultFields);
    }
    
    /***********************************************************************
    * COMPONENTS
    ************************************************************************/
    
    /**
     * Voici le tableau associatif des components. Il est de la forme [nom=>component].
     * Chaque component utilise les champs utilisateurs de manière indirecte :
     * On doit d'abord "pousser" les champs que l'on veut utiliser dans les "paramètres" des components.
     * En quelque sorte, un paramètre est champ que l'on a incorporé dans un component.
     */
    private $_components;
    
    //* Initialise la liste des composants. À redéfinir dans initComponents().
    public abstract function initComponents();
    
    //* Crée les components de la funcard. À appeler dans initComponents().
    public function resetComponents($components){ $this->_components = $components; }
    
    //* Retourne vrai ou faux selon si le component $name existe ou pas.
    public function hasComponent($name){ return isset($this->_components[$name]); }
    
    //* Retourne le tableau des components.
    public function getComponents() { return $this->_components; }
    
    //* Retourne le component dont le nom est $name, null s'il n'existe pas.
    public function getComponent($name) {
        if($this->hasComponent($name))
            return $this->_components[$name];
        else
            return null;
    }
    
    //* Ajoute ou modifie le component $name.
    public function setComponent($name, $component) { $this->_components[$name] = $component; }
    
    //* Retire le component $name.
    public function removeComponent($name) { unset($this->_components[$name]); }
    
    /***********************************************************************
    * PARAMETERS
    ************************************************************************/
    
    //* Dans toutes ces fonctions, l'argument $safe détermine si l'application doit lancer une exception si le component n'existe pas.
    //* Par défaut cet argument est à true.
    
    //* Retourne vrai ou faux selon si le paramètre $parameter est défini dans le component $componentName.
    public function hasParameter($componentName, $parameter, $safe = true){
        if(!$this->hasComponent($componentName)){
            if ($safe) throw new ErrorException('hasParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
            else return false;
        }
        return $this->getComponent($componentName)->hasParameter($parameter);
    }
    
    //* Retourne la valeur du paramètre
    public function getParameter($componentName, $parameter, $safe = true){
        if(!$this->hasComponent($componentName)){
            if ($safe) throw new ErrorException('getParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
            else return null;
        }
        return $this->getComponent($componentName)->getParameter($parameter);
    }
    
    //* Ajoute ou modifie le paramètre $parameter du component $componentName avec la value $value.
    public function setParameter($componentName, $parameter, $value, $safe = true){
        if(!$this->hasComponent($componentName)){
            if ($safe) throw new ErrorException('setParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
            else return;
        }
        $this->getComponent($componentName)->setParameter($parameter, $value);
    }
    
    //* Modifie la valeur du paramètre $parameter du component $componentName, sauf si la valeur est indéfinie.
    public function pushParameter($componentName, $parameter, $value, $safe = true){
        if(!$this->hasComponent($componentName)){
            if ($safe) throw new ErrorException('pushParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
            else return;
        }
        $this->getComponent($componentName)->pushParameter($parameter, $value);
    }
    
    //* Modifie la valeur du paramètre $parameter du component $componentName, seulement si le paramètre existe.
    public function updateParameter($componentName, $parameter, $value, $safe = true){
        if(!$this->hasComponent($componentName)){
            if ($safe) throw new ErrorException('updateParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
            else return;
        }    
        $this->getComponent($componentName)->updateParameter($parameter, $value);
    }
    
    //* Créé le paramètre $parameter du component $componentName, seulement si le paramètre n'existe pas déjà.
    public function createParameter($componentName, $parameter, $value, $safe = true){
        if(!$this->hasComponent($componentName)){
            if ($safe) throw new ErrorException('createParameter() : Missing component "'.$componentName.'".', E_USER_WARNING);
            else return;
        }
        $this->getComponent($componentName)->createParameter($parameter, $value);
    }
    
    //* Ajoute ou modifie plusieurs paramètres. $params est de la forme ['nomComponent' => ['nomParametre' => 'valeurParamètre']].
    public function setParameters($params, $safe = true){
        foreach($params as $comp => $array){
            foreach($array as $key => $value){
                $this->setParameter($comp, $key, $value, $safe);
            }
        }
    }
    
    //* Modifie plusieurs paramètres, seulement s'ils sont déjà définis. $params est de la forme ['nomComponent' => ['nomParametre' => 'valeurParamètre']].
    public function pushParameters($params, $safe = true){
        foreach($params as $comp => $array){
            foreach($array as $key => $value){
                $this->pushParameter($comp, $key, $value, $safe);
            }
        }
    }
    
    //* Ajoute plusieurs paramètres, seulement s'ils ne sont pas déjà définis. $params est de la forme ['nomComponent' => ['nomParametre' => 'valeurParamètre']].
    public function createParameters($params, $safe = true){
        foreach($params as $comp => $array){
            foreach($array as $key => $value){
                $this->createParameter($comp, $key, $value, $safe);
            }
        }
    }
    
    //* Les paramètres par défaut de chaque component. C'est un double array de la forme [nom => [paramètre => valeur]]
    //* À redéfinir dans les classes filles.
    protected static $_defaultParameters = [];
    
    //* Importe les paramètres par défaut
    public function setDefaultParameters(){
        // 2e paramètre à false car si le component n'existe pas on veut juste passer au suivant sans lancer d'exception.
        $this->setParameters(static::$_defaultParameters, false);
    }
    
    //* Importe les paramètres selon les champs de la funcard. À redéfinir dans les classes filles.
    public abstract function pushComponentsData();
    
    /***********************************************************************
    * LISTENING LIST
    ************************************************************************/
    
    /* La liste d'écoute liste tous les paramètres des components
     * qui seront éventuellement modifiés par d'autres components lors de leur configuration ou de leur application.
     * Les listes d'écoute sont stockées dans chaque component.
     */
    
    /* Ajoute les paramètres listés dans $options dans les bons components, sauf s'ils sont déjà définis.
     * Cette opération est effectuée après chaque configuration ou application d'un component.
     * $options est de la forme [nomComponent => [nomParameter => value]]
     */
    public function createListenedParameters($options){
        if(isset($options) && $options){
            if(!is_array($options)) return;
            
            foreach($options as $comp => $array){
                foreach($array as $key => $value){
                    if($this->getComponent($comp) !== null && $this->getComponent($comp)->listens($key)){
                        $this->createParameter($comp, $key, $value);
                    }
                }
            }
        }
    }
    
    /* Modifie ou créé les paramètres listés dans $options dans les bons components.
     * Cette opération est effectuée après chaque configuration ou application d'un component.
     * $options est de la forme [nomComponent => [nomParameter => value]]
     * Cette fonction met à jour les paramètres déjà définis, remplaçant alors les paramètres issus des champs utilisateurs.
     */
    public function setListenedParameters($options){
        if(isset($options) && $options){
            if(!is_array($options)) return;
            
            foreach($options as $comp => $array){
                foreach($array as $key => $value){
                    if($this->getComponent($comp) !== null && $this->getComponent($comp)->listens($key)){
                        $this->setParameter($comp, $key, $value);
                    }
                }
            }
        }
    }
    
    //* Configure la liste d'écoute. À redéfinir dans les classes filles.
    public abstract function initListeningList();
    
    /***********************************************************************
    * GESTION DE FICHIER
    ************************************************************************/
    
    //* génère un nom de fichier d'après le champ 'titre', s'il est défini. Sinon le nom de fichier sera 'funcard'. Le nom de fichier ne contient pas l'extension.
    public function computeFilename(){
        $this->setFilename($this->hasField('title') ? $this->getField('title') : 'funcard');
    }
    
    //* génère une chaine Json d'après les informations et les champs.
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
    
    
    /***********************************************************************
    * CONFIGURATION & APPLICATION DES COMPONENTS
    ************************************************************************/
    
    //* Variable sachant si les components ont été configurés.
    private $_configured = false;
    
    //* Variable sachant si les components ont été appliqués.
    private $_rendered = false;
    
    //* Tri des components. Les clés sont préservées.
    public function sortComponents(){
        uasort($this->_components, 'FcmFuncardComponent::compare');
    }
    
    //* Configure le component
    public function configureComponent($name){
        try{
            $options = $this->_components[$name]->configure();
            // configure() a renvoyé un set d'options à mettre à jour
            $this->createListenedParameters($options);
            
        } catch (Exception $e){
            if(DEBUG){
                echo $e->getMessage(), '<br/>';
                debug_print_backtrace();
            }
        }
    }
    
    //* Configure les components de la funcard. Ils doivent être triés auparavant avec sortComponents().
    public function configureComponents(){
        foreach ($this->_components as $name => $component){
            $this->configureComponent($name);
        }
        $this->_configured = true;
    }
    
    //* Applique le component
    public function applyComponent($name){
        try{
            $options = $this->_components[$name]->apply();
            // apply() a renvoyé un set d'options à mettre à jour
            $this->createListenedParameters($options);
            
        } catch (Exception $e){
            if(DEBUG){
                echo $e->getMessage(), '<br/>';
                debug_print_backtrace();
            }
        }
    }
    
    //* Applique les components de la funcard. Ils doivent être triés auparavant avec sortComponents().
    public function applyComponents(){
        foreach ($this->_components as $name => $component){
            $this->applyComponent($name);
        }
        $this->_rendered = true;
    }
    
    
    //* Effectue un rendu de la funcard : appelle un à un d'application des différents components. Ils doivent avoir été configurés.
    public function render(){
        
        if(!$this->_configured){
            throw new ErrorException('render() : components haven\'t been configured.', E_USER_ERROR);
        }
        
        // 1 On trie les components par ordre de priorité
        //$this->sortComponents(); // TODO le tri se fait avant la configuration des components.
        
        // 2 On les applique un à un
        $this->applyComponents();
        
        // 3 Jusque-là on traitait des images au format MIFF pour la performance. Il nous la faut en PNG !
        $this->getCanvas()->setImageFormat('png');
        return $this->getCanvas();
    }
    
    /***********************************************************************
    * DONNEES UTILISATEUR
    ************************************************************************/
    
    //* Importe les données de $data dans la funcard
    /* Importe les données $data dans la funcard.
     * $ data doit être un array contenir : 
     * - 'width' => largeur espérée de la funcard (éventuellement modifiée par les component)
     * - 'height' => hauteur espérée de la funcard (éventuellement modifiée par les component)
     * - 'fields' => liste des champs utilisateurs. C'est un sous-array de la forme ['champ' => 'valeur']
     */
    public function import($data){
        if(!is_array($data)){
            throw new ErrorException('import() : delivered data is not an array.', E_USER_ERROR);
        }
        if(!isset($data['width'])){
            throw new ErrorException('import() : missing width in delivered data.', E_USER_ERROR);
        }
        if(!isset($data['height'])){
            throw new ErrorException('import() : missing height in delivered data.', E_USER_ERROR);
        }
        if(!isset($data['fields'])){
            throw new ErrorException('import() : missing fields in delivered data.', E_USER_ERROR);
        }
        if(!is_array($data['fields'])){
            throw new ErrorException('import() : fields are not array in delivered data.', E_USER_ERROR);
        }
        
        $this->setWidth(intval($data['width']));
        $this->setHeight(intval($data['height']));
        
        foreach($data['fields'] as $key => $value){
            if($value != '') // La chaine '0' est considérée comme vide. Donc on fait comme != ''.
                $this->setField($key, $value);
        }
    }
    
    /***********************************************************************
    * GESTION DES RESOURCES
    ************************************************************************/
    
    //* Charge une image de ressource à la bonne taille
    public function loadResource($type, $subtype, $name, $sizex = 0, $sizey = 0){
        
        // Par défaut la ressource fera la taille de la carte
        if($sizex === 0) $sizex = $this->getWidth();
        if($sizey === 0) $sizey = $this->getHeight();
        
        // Si le width ou height est auto, on let ensuite à 0 (signifie auto pour Imagick)
        if($sizex === 'auto') $sizex = 0;
        if($sizey === 'auto') $sizey = 0;
        
        // On détermine le chemin de fichier
        $filepath = 'resource/'.$type.'/'.$this->getTemplateName().'/';
        if($subtype !== null && !empty($subtype))
            $filepath .= $subtype.'/';
        $filepath .= $name;
        
        // On charge l'image
        $image = new Imagick(realpath($filepath));
        
        // On redimmensionne la resource si nécessaire
        $imagewidth = $image->getImageWidth();
        $imageheight = $image->getImageHeight();
        
        if($imagewidth != $sizex || $imageheight != $sizey){
            $image->resizeImage($sizex, $sizey, Imagick::FILTER_TRIANGLE, 1, false);
        }
        
        return $image;
        
    }
}