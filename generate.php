<?php require_once('include/functions.php');

require_once('lib/PHPImageWorkshop/ImageWorkshop.php'); // Be sure of the path to the class
use PHPImageWorkshop\ImageWorkshop;

require_once('include/FcmModernBasic.php');

$all_types = [
    'modern-basic' => 'FcmModernBasic'
];

//var_dump($_POST);

// Get template name
if(!isset($_POST['template'])){
    exit('Missing template name');
}
$templateName = $_POST['template'];
if(!isset($all_types[$templateName])){
    exit('Unknonw template name');
}

// Get output method
$method = 'json';
if(isset($_POST['method']) && !empty($_POST['method'])){
    if(in_array($_POST['method'], ['json', 'download', 'thumbnail'])){
        $method = $_POST['method'];
    }
}

$funcard = new $all_types[$templateName]($_POST);
$funcard->computeFilename();
$funcard->filenameSpecialChars();

$result = $funcard->render($method);

if(DEBUG) exit();

if($method=='json'){
    $image_b64 = base64_encode($result); 
    echo $image_b64;
} elseif ($method=='download') {
    $computedname = $funcard->getFilename();
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="'.$computedname.'"');
    echo $result;
} elseif ($method=='thumbnail') {
    $funcard->miniaturize();
    $computedname = $funcard->getFilename();
    header('Content-Type: image/jpg');
    header('Content-Disposition: attachment; filename="'.$computedname.'"');
    echo $result;
}

?>