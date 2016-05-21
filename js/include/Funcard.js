function Funcard(){
    
    /**
     * Le nom du template
     */
    this.template = '';
    
    /**
     * La liste des champs remplis par l'utilisateur.
     * C'est un table d'objets de type Field.
     * L'exportation transformera donc ça en objet sérialisé.
     */
    this.fields = [];
    
    /**
     * La liste des panneaux permettant à l'utilisateur de modifier les champs
     */
    this.panels = [];
    
    /**
     * Taille de funcard
     */
    this.width = null;
    this.height = null;
    this.ratio = null;      // width/height
    this.illusWidth = null;
    this.illusHeight = null;
    
}
