<section class="fcm-panel" id="fcm-panel-oldbasicbackground">
    <h2>Le fond de carte</h2>
    <div class="fcm-columns">
    
        <div class="fcm-selector has-inner" id="fcm-background-base-selector" data-field="fcm-field-background-base" data-separator="/">
            <h3 class="has-panel-top">Base</h3>
            <div class="fcm-panel-top-toolbar">
                <button class="fcm-toggle-button fcm-toggle-duoselector">
                    <i class="fa fa-adjust fa-fw"></i>
                    &nbsp; Hybride
                </button>
            </div>
            <input type="hidden" class="fcm-selector-field"
                   name="fcm-field-background-base"
                   id="fcm-field-background-base"
                   value="r" />
            <div class="fcm-selector-inner" id="fcm-background-base-grid">
                <button class="fcm-selector-button" data-value="w" title="Blanc">
                    <div class="fcm-color-button fcm-color-w"></div>
                </button>
                <button class="fcm-selector-button" data-value="u" title="Bleu">
                    <div class="fcm-color-button fcm-color-u"></div>
                </button>
                <button class="fcm-selector-button" data-value="b" title="Noir">
                    <div class="fcm-color-button fcm-color-b"></div>
                </button>
                <button class="fcm-selector-button active" data-value="r" title="Rouge">
                    <div class="fcm-color-button fcm-color-r"></div>
                </button>
                <button class="fcm-selector-button" data-value="g" title="Vert">
                    <div class="fcm-color-button fcm-color-g"></div>
                </button>
                <button class="fcm-selector-button" data-value="m" title="Multicolore">
                    <div class="fcm-color-button fcm-color-m"></div>
                </button>
                <button class="fcm-selector-button" data-value="a" title="Artefact">
                    <div class="fcm-color-button fcm-color-a"></div>
                </button>
                <span class="line-break"></span>
                <button class="fcm-selector-button" data-value="lw" title="Terrain Blanc">
                    <div class="fcm-color-button fcm-color-w fcm-color-land"></div>
                </button>
                <button class="fcm-selector-button" data-value="lu" title="Terrain Bleu">
                    <div class="fcm-color-button fcm-color-u fcm-color-land"></div>
                </button>
                <button class="fcm-selector-button" data-value="lb" title="Terrain Noir">
                    <div class="fcm-color-button fcm-color-b fcm-color-land"></div>
                </button>
                <button class="fcm-selector-button" data-value="lr" title="Terrain Rouge">
                    <div class="fcm-color-button fcm-color-r fcm-color-land"></div>
                </button>
                <button class="fcm-selector-button" data-value="lg" title="Terrain Vert">
                    <div class="fcm-color-button fcm-color-g fcm-color-land"></div>
                </button>
                <button class="fcm-selector-button" data-value="lm" title="Terrain Multicolore">
                    <div class="fcm-color-button fcm-color-m fcm-color-land"></div>
                </button>
            </div>
        </div>
        
        <div class="fcm-background-uploader">
            <h3>Fond personnel</h3>
            <form id="fcm-form-custom-background" method="post" action="upload.php" enctype="multipart/form-data">
                
                <p>
                    La taille idéale du fond personnel est de <b>787 x 1087</b> pixels en incluant 41 pixels de bordure.<br/>
                    Vous pouvez aussi utiliser un fond dont les dimensions sont proportionnelles à celles-ci.<br/>
                </p>
                
                <div class="input-container large file-input-container">
                    <div class="file-loading-icon"></div>
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo file_upload_max_size(); ?>" />
                    <input type="file" class="fcm-media" id="fcm-file-background" name="fcm-file-media" accept="image/*">
                </div>
                
                <div class="input-container large file-preview-wrapper">
                    <div class="file-error"></div>
                    <div class="file-preview-inner-wrapper">
                        <img src="" alt="Prévisualisation" class="file-preview"/>
                    </div>
                </div>

                <input type="hidden" class="file-field" id="fcm-field-background-custom" name="fcm-field-background-custom" value="" />
            </form>
        </div>
        
    </div>
</section>