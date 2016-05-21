<?php

require_once('include/functions.php');
require_once('include/FcmFcRender.php');

// slimcard


$RETOUR = '<br/><a href="javascript:history.back()"><- Retour</a>';

$file = null;
if(isset($_FILES['fcm-file-funcard-to-slim']))
    $file = $_FILES['fcm-file-funcard-to-slim'];

if(!$file){
    echo 'Le fichier est trop volumineux.'.$RETOUR;
    exit();
}

// On examine les erreurs
if ($file['error'] !== UPLOAD_ERR_OK) { 
    $err = new UploadException($file['error']);
    echo $err.$RETOUR;
    exit();
}

$oualides_extensions = array( '.jpg' , '.jpeg' , '.gif' , '.png' );

// On vérifie le nom de fichier. Si un fichier du même nom existe déjà, on renomme celui-ci.
$filename = $filename_original = basename(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $file['name']));
$pos = strrpos($filename_original, '.');
$filename_extension_with_dot = strtolower(substr($filename_original, $pos));

if(!in_array($filename_extension_with_dot, $oualides_extensions)){
    echo 'Format de fichier invalide. Voici les formats acceptés : *.png, *.jpg, *.gif'.$RETOUR;
    exit();
}

// Tout est bon, regarde si on a notre fichier
if(!isset($file['tmp_name'])){
    echo 'Erreur serveur. Merci de nous en parler sur le forum. (Erreur : erreur déplacement fichier temporaire)'.$RETOUR;
    exit();
}

// C'est bon, on SLIME !!!!!!!!!!!

$render = new FcmFcRender($file['tmp_name'], $filename);
$render->miniaturize();

header('Content-Type: image/jpg');
header('Content-Disposition: attachment; filename="'.$render->getFilename().'"');

echo $render->render();
