var DEBUG = false;


// TODO Clean all the code

// Elements
var generator = $('#fcm-generator');
var loading = $('.loading-wrapper');
var loadingPreview = $('.fcm-preview-loading');
var preview = $('.fcm-preview');
var previewImage = $('.fcm-preview-image');
var previewReloder = $('#fcm-preview-reload');
var previewDebug = $('.fcm-preview-debug');
var previewTime = $('.fcm-preview-generation-time');

// focus element
var focusElement = null;

// innerLoading
var innerLoading = 0;

// Panels
var currentPanel = null;

// Events

/**
 * Changement de panneau lorsqu'on clique sur un bouton étant associé à un panneau
 */
$('#fcm-menu, #fcm-header').on('click', '[data-panel]', function(e){
    // Check if it's a valid link
    if(!$(this).data('panel')) return true;

    changePanel($(this).data('panel'));
    return false;
})

// Panel displayer

/**
 * Efface le panneau courant et affiche celui passé en paramètre.
 */
function changePanel(name){
    // hide the current panel
    if(currentPanel){
        currentPanel.hide();
    }
    // Show the new panel
    var panel = existingPanels[name];
    currentPanel = panel;
    panel.show();
}

/**
 * Retourne le panneau dans lequel est situé un élément du DOM passé en paramètre
 */
function getElementPanel(elem){
    var panelElement = elem.closest('[id^='+Panel.ID_PREFIX+']');
    //console.log(panelElement);
    if(!panelElement.length) return null;
    var panelName = panelElement.attr('id');
    //console.log(panelName);
    panelName = panelName.substr(Panel.ID_PREFIX.length);
    //console.log(panelName);
    if (!(panelName in existingPanels)) return null;
    return existingPanels[panelName];
}

/**
 * Efface la liste des panneaux et charge les nouveaux panneaux
 * à partir des données de la funcard passée en paramètre
 */
function loadPanels(funcard){
    
    showLoading();
    
    var panelsTab = getFuncardPanelNameList(funcard);
    
    // Call getpanels.php
    $.get(
        'getpanels.php',
        { 'panels[]' : panelsTab },
        function(data){
            // Destroy current pannels
            clearPanels();
            showPanels(funcard, data);
            changePanel(panelsTab[0]);
            generator.trigger('panelsLoaded');
            
            setTimeout( function(){
                $('.warning-template-changes').addClass('active');
            }, 10);
        }
    ).fail(function() {
        alert( 'Erreur au chargement des composantes de la funcard. Veuillez réessayer.' );
    })
    .always(function(){
        hideLoading();
    });

}

/**
 * Retourne la liste des noms de panneaux de la funcard
 */
function getFuncardPanelNameList(funcard){
    // On crée un tableau contenant les noms de panneaux à charger
    var panelsTab = [];
    if(funcard != null){
        for(var panel of funcard.panels){
            panelsTab.push(panel.name);
        }
    }
    return panelsTab;
}

/**
 * Efface tous les panneaux
 */
function clearPanels(){
    Panel.TEMPLATE_CONTAINER_ELEMENT.html('');
    Panel.TEMPLATE_MENU_ELEMENT.html('');
}

/**
 * Show Panels : Affiche les panneaux chargés
 */
function showPanels(funcard, data){
    // On désactive les anciens panneaux s'ils existent
    if(funcard != null){
        for(var panel of funcard.panels){
            panel.deactivate();
        }
    }
    
    // On ajoute les panneaux chargés
    Panel.TEMPLATE_CONTAINER_ELEMENT.html(data);
    // On active les panneaux (ça les ajoute dans le menu notamment)
    if(funcard != null){
        for(var panel of funcard.panels){
            panel.activate();
        }
    }
}


/**
 * Charge le template dans myFuncard
 */
Panel.CONTAINER_ELEMENT.on('click', '#fcm-template-selector .fcm-selector-button', function(){
    loadTemplate($(this).data('value'));
    // Une fois le template chargé, on update la preview une fois unique.
    generator.one('panelsLoaded', updatePreview);
    loadPanels(myFuncard);
});


/**
 * Affiche/Cache un icone loading sur le generator
 */
function showLoading(){
    ++innerLoading;
    if(innerLoading > 1) return;
    loading.addClass('active');
    setTimeout( function(elem){
        elem.addClass('opaque');
    }, 10, loading);
}
function hideLoading(){
    --innerLoading;
    if(innerLoading > 0) return;
    loading.removeClass('active');
    setTimeout( function(elem){
        elem.removeClass('opaque');
    }, 10, loading);
}


/**
 * Affiche/Cache un icone loading sur la prévisualisation
 */
function showPreviewLoading(){
    loadingPreview.addClass('active');
    setTimeout( function(elem){
        elem.addClass('opaque');
    }, 10, loadingPreview);
}
function hidePreviewLoading(){
    loadingPreview.removeClass('active');
    setTimeout( function(elem){
        elem.removeClass('opaque');
    }, 10, loadingPreview);
}


/**
 * Met à jour la prévisualition
 */
function updatePreview(){
    if(!myFuncard) return false;
    
    updateFields(myFuncard);
    
    myFuncard.resetSize();
    
    showPreviewLoading();
    //preview.css('width', myFuncard.width);
    //preview.css('height', myFuncard.height);
    // CALL THE GENERATION !!!! HELL YEAH !!!!
    $.post( "generate.php", {
        //  Data to send to the generation algorithm
        
        'width': myFuncard.width,
        'height': myFuncard.height,
        'template' : myFuncard.template,
        'fields' : myFuncard.fields
        
    },function( data ) {
        // success function
        if(DEBUG){
            previewDebug.html(data);
        } else {
            //console.log(data);
            preview.removeClass('nocard');
            previewImage.css('background-image', 'url(data:image/png;base64,'+data.image+')');
            previewTime.html('Généré en ' + data.generationTime.toFixed(3) + ' secondes.');
            myFuncard.width = data.width;
            myFuncard.height = data.height;
            preview.css('width', myFuncard.width);
            preview.css('height', myFuncard.height);
        }
        
    })
    .fail(function(xhr) {
        if(!isUserAborted(xhr)){
            alert( "error" );
        }
    })
    .always(function(){
        hidePreviewLoading();
    });
    
    return false;
}

previewReloder.click(updatePreview);

function isUserAborted(xhr) {
  return !xhr.getAllResponseHeaders();
}


/**
 * Met à jour la myFuncard d'après les valeurs des inputs HTML
 */
function updateFields(funcard){
    var htmlInputs = Panel.CONTAINER_ELEMENT.find('[name|="fcm-field"]');
    funcard.fields = {};
    htmlInputs.each(function(){
        var fieldname = $(this).attr('name');
        fieldname = fieldname.slice(10); // 10 characters in 'fcm-fields-'
        var fieldvalue= $(this).val();
        funcard.fields[fieldname] = fieldvalue;
    })
}


/**
 * Affiche le panel Home au chargement de la page, et au clic sur le logo
 */
if($('body').hasClass('index')){
    changePanel('home');
}

/**
 * Boutons d'insertion de contenu automatique dans le champ
 * Utilisé pour les symboles de mna et les caractères spéciaux
 */
// Il faut constamment enir à jour le dernier champ focussé
Panel.CONTAINER_ELEMENT.on('focus', 'input, textarea', function(){
    focusElement = $(this);
});

Panel.CONTAINER_ELEMENT.on('click', 'button.single-inserter', function(){
    var caretPosStart = focusElement[0].selectionStart;
    var caretPosEnd = focusElement[0].selectionEnd;
    var textAreaTxt = focusElement.val();
    var txtToAdd = $(this).data('insert');
    if(txtToAdd === undefined) return;
    focusElement.val(textAreaTxt.substring(0, caretPosStart)
                   + txtToAdd
                   + textAreaTxt.substring(caretPosEnd) );
    focusElement.focus();
    setCaretPosition(focusElement[0],
                     caretPosEnd + txtToAdd.length,
                     caretPosEnd + txtToAdd.length);
    
});

Panel.CONTAINER_ELEMENT.on('click', 'button.double-inserter', function(){
    var caretPosStart = focusElement[0].selectionStart;
    var caretPosEnd = focusElement[0].selectionEnd;
    var textAreaTxt = focusElement.val();
    var txtToAdd = $(this).data('insert');
    if(txtToAdd === undefined
       || !$.isArray(txtToAdd)
       || txtToAdd[0] === undefined
       || txtToAdd[1] === undefined) return;
    focusElement.val(textAreaTxt.substring(0, caretPosStart)
                   + txtToAdd[0]
                   + textAreaTxt.substring(caretPosStart, caretPosEnd)
                   + txtToAdd[1]
                   + textAreaTxt.substring(caretPosEnd) );
    focusElement.focus();
    setCaretPosition(focusElement[0],
                     caretPosStart,
                     caretPosEnd + txtToAdd[0].length + txtToAdd[1].length);
});

/**
 * Code from eternal : http://stackoverflow.com/questions/512528/set-cursor-position-in-html-textbox
 */
function setCaretPosition(ctrl, pos1, pos2)
{

    if(ctrl.setSelectionRange)
    {
        ctrl.focus();
        ctrl.setSelectionRange(pos1,pos2);
    }
    else if (ctrl.createTextRange) {
        var range = ctrl.createTextRange();
        range.collapse(true);
        range.moveEnd('character', pos2);
        range.moveStart('character', pos1);
        range.select();
    }
}

// Keyboard shortcut to print nbsp
$(document).keydown(function (event) {
    // espace avec Maj
    if (event.which == 32 && event.shiftKey) {
        var caretPosStart = focusElement[0].selectionStart;
        var caretPosEnd = focusElement[0].selectionEnd;
        var textAreaTxt = focusElement.val();
        var txtToAdd = '\u00A0';
        focusElement.val(textAreaTxt.substring(0, caretPosStart)
                       + txtToAdd
                       + textAreaTxt.substring(caretPosEnd) );
        focusElement.focus();
        setCaretPosition(focusElement[0],
                         caretPosEnd + txtToAdd.length,
                         caretPosEnd + txtToAdd.length);
        event.preventDefault();
    }
});


// Media Handling
Panel.CONTAINER_ELEMENT.on('change', '.fcm-media', function(){
    var form = $(this).closest('form');
    var formdata = (window.FormData) ? new FormData(form[0]) : null;
    var data = (formdata !== null) ? formdata : form.serialize();
    var loading = form.find('.file-loading-icon');
    var error = form.find('.file-error');
    var image = form.find('.file-preview');
    var field = form.find('.file-field');
    
    function showMediaUploadError(message){
        error.html(message);
        image.removeClass('active');
        error.addClass('active');
        field.val('');
        form.trigger('uploadFailure');
    }
    function showMediaUploadMessage(message){
        error.html(message);
        error.addClass('active');
    }
    
    loading.addClass('active');
    // début des opérations
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        contentType: false, // obligatoire pour de l'upload
        processData: false, // obligatoire pour de l'upload
        //dataType: 'json', // selon le retour attendu
        data: data
    }).done(function(response){
        response = $.parseJSON(response);
        // On a reçu la réponse du serveur, c'est soit le filepath, soit une erreur. Soit un autre truc inconnu, mais là c'est bizarre.
        if("filepath" in response){
            image.attr('src', 'uploads/'+response.filepath);
            image.addClass('active');
            showMediaUploadMessage('Votre image sera disponible jusqu\'à minuit.');
            field.val(response.filepath);
            form.trigger('uploadSuccess');
        } else if("error" in response){
            showMediaUploadError(response.error);
        } else {
            showMediaUploadError('Erreur inconnue. Merci de nous en parler sur le forum.');
        }
        
    }).fail(function(){
        showMediaUploadError('Erreur lors de l\'envoi du fichier.');
    }).always(function(){
        loading.removeClass('active');
    });
});

/**
 * Gestion des sélecteurs
 */
Panel.CONTAINER_ELEMENT.on('click', '.fcm-selector .fcm-selector-button', function(){
    var container = $(this).closest('.fcm-selector');
    var field = container.find('.fcm-selector-field');
    
    // On décoche le clear button s'il existe
    container.find('.fcm-selector-clear-button.active').removeClass('active');
    
    //clear previous slected element
    container.find('.fcm-selector-button.active').removeClass('active');
    $(this).addClass('active');
    
    //set hidden field
    field.val($(this).data('value'));
});

Panel.CONTAINER_ELEMENT.on('click', '.fcm-toggle-button', function(){
    $(this).toggleClass('active');
});

Panel.CONTAINER_ELEMENT.on('click', '.fcm-toggle-duoselector', function(){
    var container = $(this).closest('.fcm-selector, .fcm-duoselector');
    
    container.toggleClass('fcm-duoselector');
    container.toggleClass('fcm-selector');
    
    if(container.hasClass('fcm-selector')){
        
        var button = $(container.find('.fcm-selector-button.active')[0]);
        container.find('.fcm-selector-button.active').removeClass('active first second');
        button.trigger('click');
        
    } else if(container.hasClass('fcm-duoselector')){
        
        container.find('.fcm-selector-button.active').addClass('first');
        
    }
});

Panel.CONTAINER_ELEMENT.on('click', '.fcm-duoselector .fcm-selector-button', function(){
    var container = $(this).closest('.fcm-duoselector');
    var field = container.find('.fcm-selector-field');
    var str = '';
    var separator = false;
    
    var checked = container.find('.fcm-selector-button.active');
    var nbchecked = checked.length;
    
    // On décoche le clear button s'il existe
    container.find('.fcm-selector-clear-button.active').removeClass('active');
    
    // Si nous avons déjà deux boutons sélectionnés, nous les effaçons
    if(nbchecked >= 2){
        container.find('.fcm-selector-button.active').removeClass('active first second');
        $(this).addClass('active first');
    }
    // Coche si premier bouton
    if(nbchecked == 0){
        $(this).addClass('active first');
    }
    // Coche si deuxième bouton différent du premier
    if(nbchecked == 1 && $(this)[0] != checked[0]){
        $(this).addClass('active second');
    }
    
    // On check quel est le séparateur
    if(container.data('separator')) separator = container.data('separator');
    
    // On calcule la valeur du champ
    var first = container.find('.fcm-selector-button.active.first');
    if(first.length) str += first.data('value');
    var second = container.find('.fcm-selector-button.active.second');
    if(separator != false && first.length && second.length) str += separator;
    if(second.length) str += second.data('value');
    
    field.val(str);
    
});

Panel.CONTAINER_ELEMENT.on('click', '.fcm-selector-clear-button', function(){
    var container = $(this).closest('.fcm-selector, .fcm-duoselector');
    var field = container.find('.fcm-selector-field');
    
    var buttons = container.find('.fcm-selector-button');
    buttons.removeClass('active first second');
    field.val('');
    $(this).addClass('active');
});

/**
 * Télécharge le rendu final
 */
Panel.CONTAINER_ELEMENT.on('click', '#fcm-download-jpg', function(){
    if(!myFuncard){
        alert('Vous ne voudriez pas créer une funcard, d\'abord ?');
        return;
    }
    
    updateFields(myFuncard);
    // CALL THE GENERATION !!!! HELL YEAH !!!!
    openWithPostData('generate.php',{
        //  Data to send to the generation algorithm
        
        'width' : myFuncard.width,
        'height' : myFuncard.height,
        'template' : myFuncard.template,
        'fields' : myFuncard.fields,
        'method' : 'thumbnail'
        
    });
});

Panel.CONTAINER_ELEMENT.on('click', '#fcm-download', function(){
    if(!myFuncard){
        alert('Vous ne voudriez pas créer une funcard, d\'abord ?');
        return;
    }
    
    updateFields(myFuncard);
    // CALL THE GENERATION !!!! HELL YEAH !!!!
    openWithPostData('generate.php',{
        //  Data to send to the generation algorithm
        
        'width' : myFuncard.width,
        'height' : myFuncard.height,
        'template' : myFuncard.template,
        'fields' : myFuncard.fields,
        'method' : 'download'
        
    });
});

function openWithPostData(page,data)
{
    var form = document.createElement('form');
    form.setAttribute('action', page);
    form.setAttribute('method', 'post');
    recursiveCreateFields(form, data, false);
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function recursiveCreateFields(form, data, field){
    for (var n in data)
    {
        //console.log(n, data[n]);
        if(data[n] !== null && typeof data[n] === 'object')
            recursiveCreateFields(form, data[n], field || (n==='fields'));
        else
            createElementField(form, data, n, field);
    }
}

function createElementField(form, data, n, field){
    var inputvar = document.createElement('input');
    inputvar.setAttribute('type', 'hidden');
    inputvar.setAttribute('name', field ? 'fields[' + n + ']' : n);
    inputvar.setAttribute('value', data[n]);
    form.appendChild(inputvar);
}

/**
 * Exporte la funcard dans un fichier json
 */
Panel.CONTAINER_ELEMENT.on('click', '#fcm-export', function(){
    if(!myFuncard) return;
    
    updateFields(myFuncard);
    // CALL THE GENERATION !!!! HELL YEAH !!!!
    openWithPostData('export.php',{
        //  Data to send to the generation algorithm
        
        'width' : myFuncard.width,
        'height' : myFuncard.height,
        'template' : myFuncard.template,
        'fields' : myFuncard.fields,
        'method' : 'download'
        
    });
});

/**
 * Importe la funcard depuis un fichier json
 */

Panel.CONTAINER_ELEMENT.on('change', '#fcm-file-import', function(){
    
    if(!$(this).val()) return;
    
    var form = $(this).closest('form');
    var loading = form.find('.file-loading-icon');
    var error = form.find('.file-error');
    var inputElement = $(this);
    
    loading.addClass('active');
    
    // Code adapted from Trausti Kristjansson's.
    var input, file, fr, json;

    if (typeof window.FileReader !== 'function') {
        alert("Hum... votre navigateur semble un peu vieux, non ? Vous devriez utilisez un navigateur plus récent.");
        return;
    }

    input = $(this)[0];
    if (!input) {
        alert("Um, couldn't find the fileinput element.");
        loading.removeClass('active');
    }
    else if (!input.files) {
        alert("Hum... votre navigateur semble un peu vieux, non ? Vous devriez utilisez un navigateur plus récent.");
        loading.removeClass('active');
    }
    else if (!input.files[0]) {
        alert("Merci de sélectionner un fichier");
        loading.removeClass('active');
    }
    else {
        file = input.files[0];
        fr = new FileReader();
        fr.onload = receivedText;
        fr.readAsText(file);
    }

    function receivedText(e) {
        //loading.removeClass('active');
        var lines = e.target.result;
        //console.log(lines);
        json = JSON.parse(lines); 
        
        importFuncard(json);
        
    }
    
    function importFuncard(json){
        // on prend le bon template
        error.removeClass('active');

        if(!json.hasOwnProperty('template')
          || !json.hasOwnProperty('width')
          || !json.hasOwnProperty('height')
          || !json.hasOwnProperty('fields')){
            importError('Fichier corrompu. Tentez un autre fichier. (Erreur 1)');
            return;
        }

        if(!existingTemplates.indexOf(json.template)){
            importError('Fichier corrompu. Tentez un autre fichier. (Erreur 2)');
            return;
        }
        
        // Une fois les fond chargés, on continue l'importation.
        // BackgroundsLoaded s'est effectué après panelsLoaded
        generator.one('backgroundsLoaded', json.fields, function(event){
            importFuncardFieldsEvent(event);
        });
        loadTemplate(json.template);
        loadPanels(myFuncard);
        
        myFuncard.width = json.width;
        myFuncard.height = json.height;
        myFuncard.fields = json.fields;
    }

    function importError(msg){
        error.addClass('active').html(msg);
        loading.removeClass('active');
        inputElement.val('');
    }
    
    function importFuncardFieldsEvent(event){
        //console.log(event.data);
        var fieldsToImport = event.data;

        for( field in fieldsToImport ) {

            // on se charge du field
            var fieldElement = $('#fcm-field-'+field);
            fieldElement.val(fieldsToImport[field]);

            //on se charge du bouton s'il existe
            var selectorElement = $('.fcm-selector[data-field="fcm-field-'+field+'"]');
            var buttonElement = selectorElement.find('.fcm-selector-button[data-value="'+fieldsToImport[field]+'"]');
            //console.log(buttonElement);
            buttonElement.trigger('click');
        }

        loading.removeClass('active');
        inputElement.val('');
        updatePreview();
    }
    
});

/**
 * Afficher/cacher des paragraphes de l'aide
 */
Panel.CONTAINER_ELEMENT.on('click', '.help-title', function(){
    var paragraph = $(this).next('.help-paragraph');
    $(this).toggleClass('active');
    paragraph.toggleClass('active');
});

/**
 * Affiche / masque le menu principal sur petites résolutions
 */
$('#menu-toggle').click(function(){
    $('#main-menu').toggleClass('active');
    $('#menu-toggle .fa').toggleClass('fa-bars').toggleClass('fa-times');
});