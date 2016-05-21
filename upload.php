<?php require_once('include/functions.php');

$file = null;
if(isset($_FILES['fcm-file-media']))
    $file = $_FILES['fcm-file-media'];

if(!$file){
    echo json_encode(['error' => 'Le fichier est trop volumineux.']);
    exit();
}

// On examine les erreurs
if ($file['error'] !== UPLOAD_ERR_OK) { 
    $err = new UploadException($file['error']);
    echo json_encode(['error' => $err]);
    exit();
}

$oualides_extensions = array( '.jpg' , '.jpeg' , '.gif' , '.png' );

// On vérifie le nom de fichier. Si un fichier du même nom existe déjà, on renomme celui-ci.
$filename = $filename_original = basename(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $file['name']));
$pos = strrpos($filename_original, '.');
$filename_original_without_extension = substr($filename_original, 0, $pos);
$filename_extension_with_dot = strtolower(substr($filename_original, $pos));

if(!in_array($filename_extension_with_dot, $oualides_extensions)){
    echo json_encode(['error' => 'Format de fichier invalide. Voici les formats acceptés : *.png, *.jpg, *.gif']);
    exit();
}

$i = 0;
while(file_exists('uploads/'.$filename)){
    $i++;
    $filename = $filename_original_without_extension . '-' . $i . $filename_extension_with_dot;
}

// Tout est bon, on enregistre ! (Si on peut)
if(!isset($file['tmp_name'])){
    echo json_encode(['error' => 'Erreur serveur. Merci de nous en parler sur le forum. (Erreur : erreur déplacement fichier temporaire)']);
    exit();
}

if(move_uploaded_file($file['tmp_name'], 'uploads/'.$filename)){
    echo json_encode(['filepath' => $filename]);
} else {
    echo json_encode(['error' => 'Erreur serveur. Merci de nous en parler sur le forum. (Erreur : erreur déplacement fichier temporaire)']);
    exit();
}