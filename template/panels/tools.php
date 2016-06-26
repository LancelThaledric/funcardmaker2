<section class="fcm-panel" id="fcm-panel-tools">
    <h2>Des outils pratiques !</h2>
    <p>
        Voici quelques outils qui pourraient vous aider dans la création de vos funcards
    </p>
    
    <h3>SMF Slimcard</h3>
    <p>
        SMF Slimcard vous permet de créer une miniature de votre funcard afin de la rendre assez petite pour pouvoir la poster sur le site de la SMF.
        Quand vous créez une funcard grâce au SMF Funcard Maker 2, vous pouvez enregistrer une version réduite de 150ko. Il s'agit du même procédé.
        La miniature sera au format jpg sur fond blanc, de 460 pixels de large et de moins de 150ko.
        Utilisez le champ ci-dessous pour uploader votre funcard puis cliquez sur le bouton "Miniaturisation !".
    </p>
    <form id="fcm-form-slimcard" method="post" action="slimcard.php" enctype="multipart/form-data">
        <div class="input-container large file-input-container">
                <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo file_upload_max_size(); ?>" />
                <input type="file" id="fcm-file-funcard-to-slim" name="fcm-file-funcard-to-slim" accept="image/*">
                <input class="file-submit" type="submit" value="Miniaturisation !"/>
        </div>
    </form>
</section>
