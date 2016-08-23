<?php require_once('include/functions.php');

require_once('include/FcmModernBasic.php');
require_once('include/FcmOldBasic.php');
require_once('include/FcmModernPlaneswalker3.php');
require_once('include/FcmModernPlaneswalker4.php');

$all_types = [
    'modern-basic' => 'FcmModernBasic',
    'old-basic' => 'FcmOldBasic',
    'modern-planeswalker3' => 'FcmModernPlaneswalker3',
    'modern-planeswalker4' => 'FcmModernPlaneswalker4'
];

//var_dump($_POST);

// Get template name
if(!isset($_POST['template'])){
    exit('Missing template name');
}
$templateName = $_POST['template'];
if(!isset($all_types[$templateName])){
    exit('Unknown template name');
}

// Get output method
$method = 'json';
if(isset($_POST['method']) && !empty($_POST['method'])){
    if(in_array($_POST['method'], ['json', 'download', 'thumbnail'])){
        $method = $_POST['method'];
    }
}

$time = microtime(true);

try{
    
    $funcard = new $all_types[$templateName]($_POST);
    $funcard->computeFilename();
    $funcard->filenameSpecialChars();
    
    $result = $funcard->render($method);
    
} catch (Exception $e){
    if(DEBUG){
        echo $e->getMessage(), '<br/>';
        debug_print_backtrace();
    }
}


$time = microtime(true) - $time;


if(DEBUG) exit();

if($method=='json'){
    $image_b64 = base64_encode($result);
    
    $array = [
        'image' => $image_b64,
        'generationTime' => $time,
        'width' => $funcard->getWidth(),
        'height' => $funcard->getHeight()
    ];
    $output = json_readable_encode($array);
    header('Content-Type: text/json; charset=utf-8');
    echo $output;
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