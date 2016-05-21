<section class="fcm-panel" id="fcm-panel-import-export">
    <h2>Importer / Exporter votre travail</h2>
    <p>
        Vous pouvez sauvegarder votre travail pour le reprendre plus tard, ou pour le transférer d'ordinateur.
        Pour cela, il vous suffit d'exporter votre travail en cliquant sur le gros bouton éponyme.
        Vous pouvez importer un fichier exporé de cette manière à l'aide du sélecteur de fichier sobrement intitulé "Importer".
    </p>
    <p>
        Au moment de l'importation, il se peut que les ressources externes (illustration, fond et symbole d'extension perso, ...)
        ne puissent être chargées. Le SMF Funcard Maker 2 vous invitera à les réuploader.
        En effet les ressources externes sont effacées tous les jours à 4h du matin afin de ne pas saturer les serveurs.
    </p>
    <div class="input-container large">
        <div class="fcm-columns">
            
            <button class="xlbutton" id="fcm-export">
                <i class="fa fa-download"></i><br/>
                Exporter
            </button>
            
            <div class="input-container large" id="import-container">
                <h3>Importer</h3>
                <form action="#">
                    <div class="file-input-container">
                        <div class="file-loading-icon"></div>
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo file_upload_max_size(); ?>" />
                        <input type="file" id="fcm-file-import" name="fcm-file-import" accept="text/json">
                        <div id="import-error"></div>
                    </div>
                    <div class="file-preview-wrapper">
                        <div class="file-error"></div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</section>
