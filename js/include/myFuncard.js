/**
 * Gestion de la funcard de l'uilisateur
 */
var myFuncard = null;

function loadTemplate(template){
    if(template in existingTemplates)
        myFuncard = Object.create(existingTemplates[template]);
}