<?php require_once('include/functions.php');

// export funcard to json

require_once('include/ModernBasic.php');

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

$funcard = new $all_types[$templateName](200, 200, false);      // Default size

$funcard->loadFromData($_POST);


header('Content-Type: text/json; charset=utf-8');
$computedname = $funcard->computeFileName('json','-export');
header('Content-Disposition: attachment; filename="'.$computedname.'"');

// On exporte la funcard !
echo $funcard->getJsonData();