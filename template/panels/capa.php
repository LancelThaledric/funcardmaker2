<section class="fcm-panel" id="fcm-panel-capa">
    <h2>Capacité et Texte d'ambiance</h2>
    <div class="fcm-columns">
        <div class="input-container">
            <div class="input-container">
                <label for="fcm-field-capa" class="large">Capacité</label>
                <textarea id="fcm-field-capa" class="large" id="fcm-field-capa" name="fcm-field-capa"></textarea>
            </div>
            <div class="input-container">
                <label for="fcm-field-ta" class="large">Texte d'ambiance</label>
                <textarea id="fcm-field-ta" class="large" id="fcm-field-ta" name="fcm-field-ta"></textarea>
            </div>
        </div>
    
        <div class="input-container margin">
            <?php require 'template/formparts/managrid.php'; ?>

            <div class="input-toolbar" id="fcm-capa-ta-italic-toolbar">
                <button id="button-capa-i" class="double-inserter" data-insert='["<i>", "</i>"]'><i class="fa fa-italic fa-fw"></i><i>Italique</i></button>
                <span class="separator"></span>
                <button id="button-capa-nbsp"  class="single-inserter" data-insert="&nbsp;" title="[Maj + Espace] Insère un espace qui ne pourra pas engendrer de retour à la ligne.
Attention : il est visuellement identique à l'espace ordinaire.">Espace insécable</button>
                <span class="separator"></span>
                <button id="button-capa-this"  class="single-inserter" data-insert="~this~">Titre de la carte</button>
            </div>
            
            <div class="input-toolbar" id="fcm-capa-ta-chars-toolbar">
                <button id="button-capa-char-laquo"  class="single-inserter" data-insert="&laquo;">&laquo;</button>
                <button id="button-capa-char-raquo"  class="single-inserter" data-insert="&raquo;">&raquo;</button>
                <button id="button-capa-char-ldquo"  class="single-inserter" data-insert="&ldquo;">&ldquo;</button>
                <button id="button-capa-char-rdquo"  class="single-inserter" data-insert="&rdquo;">&rdquo;</button>
                <button id="button-capa-char-Agrave" class="single-inserter" data-insert="&Agrave;">&Agrave;</button>
                <button id="button-capa-char-Eacute" class="single-inserter" data-insert="&Eacute;">&Eacute;</button>
                <button id="button-capa-char-AElig"  class="single-inserter" data-insert="&AElig;">&AElig;</button>
                <button id="button-capa-char-OElig"  class="single-inserter" data-insert="&#338;">&#338;</button>
                <button id="button-capa-char-aelig"  class="single-inserter" data-insert="&aelig;">&aelig;</button>
                <button id="button-capa-char-oelig"  class="single-inserter" data-insert="&#339;">&#339;</button>
                <button id="button-capa-char-tm"     class="single-inserter" data-insert="&#8482;">&#8482;</button>
                <button id="button-capa-char-amp"    class="single-inserter" data-insert="&amp;">&amp;</button>
                <button id="button-capa-char-copy"   class="single-inserter" data-insert="&copy;">&copy;</button>
                <button id="button-capa-char-dash"   class="single-inserter" data-insert="&#8212;">&#8212;</button>
                <button id="button-capa-char-bullet" class="single-inserter" data-insert="&bull;">&bull;</button>
            </div>
        </div>
    </div>
</section>