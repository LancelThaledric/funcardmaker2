// Dictionnaire des templates

var existingTemplates = [];     // contient tous mes templates de base

/**
 * Modern Basic
 */

function ModernBasicTemplate(){
    Funcard.call(this);
    
    this.template = 'modern-basic';
    
    this.panels = [
        existingPanels['modernbasicbackground'],
        existingPanels['illustration'],
        existingPanels['titre-type'],
        existingPanels['cm'],
        existingPanels['capa'],
        existingPanels['fe'],
        existingPanels['se'],
        existingPanels['illus-copy']
    ];
    
    this.width = 791;
    this.height = 1107;
    /*this.width = Math.floor(791./2.);
    this.height = Math.floor(1107./2.);*/
    this.illusWidth = 651;
    this.illusHeight = 480;
    
}
ModernBasicTemplate.prototype = Object.create(Funcard.prototype);

existingTemplates['modern-basic'] = new ModernBasicTemplate();