<?php require_once('include/functions.php');

require_once('lib/PHPImageWorkshop/ImageWorkshop.php'); // Be sure of the path to the class
use PHPImageWorkshop\ImageWorkshop;

require_once('include/ModernBasic.php');
require_once('include/FcmModernBasic.php');

$testcard = new FcmModernBasic([
    'fields' => [
        'title' => 'Allez tous vous faire mettre',
        'fe' => '*/*'
    ]
]);

var_dump($testcard);
var_dump($testcard->getComponents());

$testcard->render();

$all_types = [
    'modern-basic' => 'ModernBasic'
];

//var_dump($_POST);

if(!isset($_POST['template'])){
    exit('Missing template name');
}
$templateName = $_POST['template'];
if(!isset($all_types[$templateName])){
    exit('Unknonw template name');
}

$method = 'json';
if(isset($_POST['method']) && !empty($_POST['method'])){
    if(in_array($_POST['method'], ['json', 'download', 'thumbnail'])){
        $method = $_POST['method'];
    }
}

$funcard = new $all_types[$templateName](200, 200);      // Default size

$funcard->loadFromData($_POST);

$result = $funcard->render($method);

if(DEBUG) exit();

if($method=='json'){
    $image_b64 = base64_encode($result); 
    echo $image_b64;
} elseif ($method=='download') {
    $computedname = $funcard->computeFileName('png');
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="'.$computedname.'"');
    echo $result;
} elseif ($method=='thumbnail') {
    $computedname = $funcard->computeFileName('jpg','-thumb');
    header('Content-Type: image/jpg');
    header('Content-Disposition: attachment; filename="'.$computedname.'"');
    echo $result;
}

?>