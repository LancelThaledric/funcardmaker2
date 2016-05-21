/**
 * type abstrait.
 * Définit un des paramètres de la funcard associé à un champ html nom=>valeur 
 */
function Field(){
    this.name = null;
    this.value = null;
};

/**
 * écrit le Field dans le champ html du même nom.
 * Cette fonction doit être surchargée dans chaque nouveau type de Field
 */
Field.prototype.writeForm = function(){
    // Fonction à surcharger
}


/**
 * input de type text
 */
function TextField(){
    Field.call(this);
};
TextField.prototype = Object.create(Field.prototype);

//Override
TextField.prototype.writeForm = function(){
    Field.prototype.writeForm.apply(this, arguments);
    // Fonction à surcharger
}