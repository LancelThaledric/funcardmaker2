/**
 * Gestion de l'affichage des panneaux
 */

function Panel(n, t){
    this.name = n;
    this.title = t;
}

Panel.ID_PREFIX = 'fcm-panel-';
Panel.CLASS_TOGGLE = 'active';

Panel.CONTAINER_ELEMENT = $('.fcm-panel-container');
Panel.MENU_ELEMENT = $('#fcm-menu');
Panel.TEMPLATE_CONTAINER_ELEMENT = $('#fcm-template-panel');
Panel.TEMPLATE_MENU_ELEMENT = $('#fcm-template-menu');

Panel.prototype.getId = function(){ return Panel.ID_PREFIX + this.name; }

// Retourne l'élément jQuery du panneau /!\Il peut être vide, si le panneau n'a pas été chargé
Panel.prototype.get = function(){ return Panel.CONTAINER_ELEMENT.find('#' + this.getId()); }

// Appelé quand le panneau est affiché
Panel.prototype.onFocus = function(){
    // À redéfinir
}

// Appelé quand le panneau est caché
Panel.prototype.onBlur = function(){
    // À redéfinir
}

// Appelé quand le panneau est activé
Panel.prototype.onActivate = function(){
    // À redéfinir
}

/**
 * Affiche le panneau et met son lien du menu en surbrillance
 */
Panel.prototype.show = function(){
    this.get().addClass(Panel.CLASS_TOGGLE);
    Panel.MENU_ELEMENT.find('[data-panel="'+this.name+'"]').parent('li').addClass(Panel.CLASS_TOGGLE);
    this.onFocus();
}

/**
 * Cache le panel
 */
Panel.prototype.hide = function(){
    this.get().removeClass(Panel.CLASS_TOGGLE);
    Panel.MENU_ELEMENT.find('[data-panel="'+this.name+'"]').parent('li').removeClass(Panel.CLASS_TOGGLE);
    this.onBlur();
}

/**
 * Ajoute le panel au menu et l'active
 * (Le contenu du panel doit déjà avoir été ajouté au DOM)
 */
Panel.prototype.activate = function(){
    Panel.TEMPLATE_MENU_ELEMENT.append('<li><a href="#" data-panel="'+this.name+'">'+this.title+'</li>');
    this.onActivate();
}





function IllustrationPanel(n, t){
    Panel.call(this, n, t);
    
    this.cropSelector = null;
}
IllustrationPanel.prototype = Object.create(Panel.prototype);

IllustrationPanel.prototype.onFocus = function(){
    // Gestion du plugin ImgAreaSelect
    //console.log('illustration onFocus');
    if(this.cropSelector != null){
        this.cropSelector.setOptions({show:true, hide:false});
        this.cropSelector.update();
    }
}

IllustrationPanel.prototype.onBlur = function(){
    // Gestion du plugin ImgAreaSelect
    //console.log('illustration onBlur');
    if(this.cropSelector != null){
        this.cropSelector.setOptions({show:false, hide:true});
        this.cropSelector.update();
    }
}







function BackgroundPanel(n, t){
    Panel.call(this, n, t);
    
    
}
BackgroundPanel.prototype = Object.create(Panel.prototype);

BackgroundPanel.prototype.onActivate = function(){
    BackgroundPanel.SELECTOR_INNER_ELEMENT = $('#fcm-background-content');
    BackgroundPanel.LOADING_ICON = $('#fcm-background-loading-icon'); 
    
    this.loadBackgrounds();
}

BackgroundPanel.prototype.loadBackgrounds = function(){
    BackgroundPanel.LOADING_ICON.addClass('active');
    //showLoading();
    // Call getbackgrounds.php
    var thisvar = this;
    $.get(
        'getbackgrounds.php',
        {
            'template' : myFuncard.template,
            'titles' : myFuncard.titles
        },
        function(data){
            BackgroundPanel.SELECTOR_INNER_ELEMENT.html(data);
            thisvar.get().trigger('backgroundsLoaded');
            // Autoselect red skin
            thisvar.get().find('[id*="base"] button[data-value="r"]').trigger('click');
            // And none for skin parts
            thisvar.get().find('button[data-value=""]').trigger('click');
        }
    ).fail(function() {
        alert( "error" );
    })
    .always(function(){
        BackgroundPanel.LOADING_ICON.removeClass('active');
        //hideLoading();
    });
    
}







function ModernBasicBackgroundPanel(n, t){
    Panel.call(this, n, t);
}
ModernBasicBackgroundPanel.prototype = Object.create(Panel.prototype);

ModernBasicBackgroundPanel.prototype.onActivate = function(){
    ModernBasicBackgroundPanel.FORM_CUSTOM_BG = $('#fcm-form-custom-background');
    ModernBasicBackgroundPanel.BUTTONS_GENERATED_BG = $('.fcm-selector-button'); 
    ModernBasicBackgroundPanel.FORM_CUSTOM_BG_ERROR = ModernBasicBackgroundPanel.FORM_CUSTOM_BG.find('.file-error');
    ModernBasicBackgroundPanel.FORM_CUSTOM_BG_IMAGE = ModernBasicBackgroundPanel.FORM_CUSTOM_BG.find('.file-preview');
    
    ModernBasicBackgroundPanel.BUTTONS_GENERATED_BG.click(function(){
        ModernBasicBackgroundPanel.FORM_CUSTOM_BG.find('#fcm-file-background').val('');
        ModernBasicBackgroundPanel.FORM_CUSTOM_BG_ERROR.removeClass('active');
        ModernBasicBackgroundPanel.FORM_CUSTOM_BG_IMAGE.removeClass('active');
        ModernBasicBackgroundPanel.FORM_CUSTOM_BG.find('#fcm-field-background-custom').val('');
    });
}






function ExtensionSymbolPanel(n, t){
    Panel.call(this, n, t);
}
ExtensionSymbolPanel.prototype = Object.create(Panel.prototype);

ExtensionSymbolPanel.prototype.onActivate = function(){
    var varthis = this;
    
    ExtensionSymbolPanel.RARITY_SELECTOR = $('#fcm-se-rarity-selector');
    ExtensionSymbolPanel.RARITY_SELECTOR_BUTTONS = ExtensionSymbolPanel.RARITY_SELECTOR.find('.fcm-selector-button');
    ExtensionSymbolPanel.EXTENSION_SELECTOR = $('#fcm-se-extension-selector');
    ExtensionSymbolPanel.EXTENSION_SELECTOR_BUTTONS = ExtensionSymbolPanel.EXTENSION_SELECTOR.find('.fcm-selector-button');
    ExtensionSymbolPanel.SE_CLEAR_BUTTON = $('#fcm-se-clear-button');
    
    ExtensionSymbolPanel.FIELD_EXTENSION = $('#fcm-field-se-extension');
    ExtensionSymbolPanel.FIELD_RARITY = $('#fcm-field-se-rarity');
    ExtensionSymbolPanel.FIELD_CUSTOM = $('#fcm-field-se-custom');
    
    ExtensionSymbolPanel.CUSTOM_PREVIEW = $('#fcm-se-file-preview');
    ExtensionSymbolPanel.FILE_SELECTOR = $('#fcm-file-se');
    ExtensionSymbolPanel.UPLOAD_FORM = $('#fcm-form-custom-se');
    
    ExtensionSymbolPanel.EXTENSION_SELECTOR_BUTTONS.click(function(){
        ExtensionSymbolPanel.RARITY_SELECTOR.addClass('active');
        
        var img;
        // common
        img = ExtensionSymbolPanel.RARITY_SELECTOR.find('button[data-value="c"]>img');
        img.attr('src', 'resource/seThumb/'+$(this).data('value')+'-c.png');
        // uncommon
        img = ExtensionSymbolPanel.RARITY_SELECTOR.find('button[data-value="u"]>img');
        img.attr('src', 'resource/seThumb/'+$(this).data('value')+'-u.png');
        // rare
        img = ExtensionSymbolPanel.RARITY_SELECTOR.find('button[data-value="r"]>img');
        img.attr('src', 'resource/seThumb/'+$(this).data('value')+'-r.png');
        // mythic
        img = ExtensionSymbolPanel.RARITY_SELECTOR.find('button[data-value="m"]>img');
        img.attr('src', 'resource/seThumb/'+$(this).data('value')+'-m.png');
        // shifted
        img = ExtensionSymbolPanel.RARITY_SELECTOR.find('button[data-value="s"]>img');
        img.attr('src', 'resource/seThumb/'+$(this).data('value')+'-s.png');

        // auto rarity
        var field = ExtensionSymbolPanel.RARITY_SELECTOR.find('.fcm-selector-field');
        if(!field.val()){
            ExtensionSymbolPanel.RARITY_SELECTOR.find('button[data-value="c"]').trigger('click');
        }
        
    });
    
    ExtensionSymbolPanel.SE_CLEAR_BUTTON.click(function(){
        varthis.clearOfficialSE();
        varthis.clearCustomSE();
    });
    
    ExtensionSymbolPanel.UPLOAD_FORM.on('uploadSuccess', function(){
        varthis.clearOfficialSE();
    })
    
    ExtensionSymbolPanel.RARITY_SELECTOR_BUTTONS.click(function(){
        varthis.clearCustomSE();
    });
    ExtensionSymbolPanel.EXTENSION_SELECTOR_BUTTONS.click(function(){
        varthis.clearCustomSE();
    });
    
    
}

ExtensionSymbolPanel.prototype.clearOfficialSE = function(){
    // Clear rarity selector
    ExtensionSymbolPanel.RARITY_SELECTOR_BUTTONS.filter('.active').removeClass('active');
    ExtensionSymbolPanel.RARITY_SELECTOR.removeClass('active');
    ExtensionSymbolPanel.EXTENSION_SELECTOR_BUTTONS.filter('.active').removeClass('active');

    // hidden fields
    ExtensionSymbolPanel.FIELD_EXTENSION.val('');
    ExtensionSymbolPanel.FIELD_RARITY.val('');
}

ExtensionSymbolPanel.prototype.clearCustomSE = function(){
    // image
    ExtensionSymbolPanel.CUSTOM_PREVIEW.removeClass('active');
    ExtensionSymbolPanel.CUSTOM_PREVIEW.attr('src', '');
    ExtensionSymbolPanel.FILE_SELECTOR.val('');
    
    // hidden field
    ExtensionSymbolPanel.FIELD_CUSTOM.val('');
}




function getPanelByName(name){
    return existingPanels[name];
}


var existingPanels = [];
// Sections du header
existingPanels['home'] = new Panel('home', 'Accueil');
existingPanels['newcard'] = new Panel('newCard', 'Nouvelle carte');
existingPanels['help'] = new Panel('help', 'Aide');
existingPanels['credits'] = new Panel('credits', 'Crédits');
existingPanels['changelog'] = new Panel('changelog', 'Changelog');
existingPanels['tools'] = new Panel('tools', 'Outils');

// Sections du menu permanentes
existingPanels['template'] = new Panel('template', 'Template');
existingPanels['import-export'] = new Panel('import-export', 'Import / Export');
existingPanels['done'] = new Panel('done', 'Terminé !');

// Sections de génération de fonds
existingPanels['modernbasicbackground'] = new ModernBasicBackgroundPanel('modernbasicbackground', 'Fond de carte');

// Sections de fabrication de carte
existingPanels['illustration'] = new IllustrationPanel('illustration', 'Illustration');
existingPanels['titre-type'] = new Panel('titre-type', 'Titre et type');
existingPanels['cm'] = new Panel('cm', 'Coût de mana');
existingPanels['capa'] = new Panel('capa', 'Capacité<br/>Texte d\'ambiance');
existingPanels['fe'] = new Panel('fe', 'Force / Endurance');
existingPanels['se'] = new ExtensionSymbolPanel('se', 'Symbole d\'extension');
existingPanels['illus-copy'] = new Panel('illus-copy', 'Illustrateur<br/>Copyright');


// Ici, il n'y a pas de classes particulières pour ces panneaux, alors on ne passe pas par le prototype
existingPanels['done'].onFocus = function(){ updatePreview(); }
existingPanels['titre-type'].onFocus = function(){ $('#fcm-field-title').focus(); }
existingPanels['cm'].onFocus = function(){ $('#fcm-field-cm').focus(); }
existingPanels['capa'].onFocus = function(){ $('#fcm-field-capa').focus(); }
existingPanels['fe'].onFocus = function(){ $('#fcm-field-fe').focus(); }
existingPanels['illus-copy'].onFocus = function(){ $('#fcm-field-illustrator').focus(); }


