<section class="fcm-panel" id="fcm-panel-mpw4-illustration">
    <h2>Ajoutez votre illustration</h2>
    <form id="fcm-form-illustration" method="post" action="upload.php" enctype="multipart/form-data">
        
        <div class="input-container large file-input-container">
            <div class="file-loading-icon"></div>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo file_upload_max_size(); ?>" />
            <input type="file" class="fcm-media" id="fcm-file-illustration" name="fcm-file-media" accept="image/*">
        </div>
        
        <div class="input-container large file-preview-wrapper">
            <div class="file-error"></div>
            <div class="file-preview-inner-wrapper">
                <img src="" alt="Prévisualisation" class="file-preview"/>
            </div>
        </div>
        
        <div class="input-toolbar" id="illustration-toolbar">
            <button id="fcm-illsutration-center-viewport">Centrer</button>
        </div>
        <input type="hidden" class="file-field" id="fcm-field-illustration" name="fcm-field-illustration" value="" />
        <input type="hidden" id="fcm-field-illuscrop-x" name="fcm-field-illuscrop-x" value="" />
        <input type="hidden" id="fcm-field-illuscrop-y" name="fcm-field-illuscrop-y" value="" />
        <input type="hidden" id="fcm-field-illuscrop-w" name="fcm-field-illuscrop-w" value="" />
        <input type="hidden" id="fcm-field-illuscrop-h" name="fcm-field-illuscrop-h" value="" />
    </form>
</section>